<?php

namespace App\Models;

use App\ActiveRecord;
use App\Behaviors\Book\NewBookNotifyBehavior;
use App\Behaviors\Timestamp;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "book".
 *
 * @property int $id
 * @property string $title
 * @property int $year Год выпуска
 * @property int $author_id ID автора
 * @property string|null $description
 * @property string $isbn ISBN
 * @property string|null $photo_path Путь фото обложки
 * @property string|null $date_created
 * @property string|null $date_updated
 *
 * @property Author $author
 */
class Book extends ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%book}}';
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
            [['title', 'year', 'author_id', 'isbn'], 'required'],
            [['year', 'author_id'], 'integer'],
            [['description'], 'string'],
            [['date_created', 'date_updated'], 'safe'],
            [['title', 'isbn', 'photo_path'], 'string', 'max' => 255],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Author::class, 'targetAttribute' => ['author_id' => 'id']],
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
            'author_id' => 'Автор',
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
     */
    public function getAuthor(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }

}
