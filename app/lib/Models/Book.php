<?php

namespace App\Models;

use App\ActiveRecord;
use App\Behaviors\Book\NewBookNotifyBehavior;
use App\Behaviors\Timestamp;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "book".
 *
 * @property int $id
 * @property string $title
 * @property int $year Год выпуска
 * @property string|null $description
 * @property string $isbn ISBN
 * @property string|null $photo_path Путь фото обложки
 * @property string|null $date_created
 * @property string|null $date_updated
 * @property-read Author[] $authors
 */
class Book extends ActiveRecord
{
    public array $author_ids = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'book';
    }

    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            Timestamp::class,
            NewBookNotifyBehavior::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['description', 'photo_path', 'date_updated'], 'default', 'value' => null],
            [['title', 'year', 'isbn'], 'required'],
            [['year'], 'integer'],
            [['description'], 'string'],
            [['date_created', 'date_updated'], 'safe'],
            [['title', 'isbn', 'photo_path'], 'string', 'max' => 255],
            ['author_ids', 'each', 'rule' => ['integer']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'year' => 'Год выпуска',
            'description' => 'Описание',
            'isbn' => 'ISBN',
            'photo_path' => 'Путь к фото обложки',
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated',
        ];
    }

    /**
     * Gets query for [[Author]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getAuthors(): ActiveQuery
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])
            ->viaTable(BookAuthor::tableName(), ['book_id' => 'id']);
    }

    /**
     * @return void
     * @throws InvalidConfigException
     */
    public function afterFind(): void
    {
        parent::afterFind();
        // Загружаем массив ID связанных авторов в наше свойство
        $this->author_ids = $this->getAuthors()->select('id')->column();
    }

    /**
     * @param $insert
     * @param $changedAttributes
     * @return void
     */
    public function afterSave($insert, $changedAttributes): void
    {
        parent::afterSave($insert, $changedAttributes);

        $this->unlinkAll('authors', true);

        if (!empty($this->author_ids)) {
            foreach ($this->author_ids as $author_id) {
                $author = Author::findOne($author_id);
                if ($author) {
                    $this->link('authors', $author);
                }
            }
        }
    }
}
