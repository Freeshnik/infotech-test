<?php

namespace App\Models;

use App\ActiveRecord;
use App\Behaviors\Timestamp;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "author".
 *
 * @property int $id
 * @property string $fio
 * @property string|null $description
 * @property string|null $date_created
 * @property string|null $date_updated
 *
 * @property-read Book[] $books
 */
class Author extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'author';
    }

    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            Timestamp::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['description', 'date_updated'], 'default', 'value' => null],
            [['fio'], 'required'],
            [['description'], 'string'],
            [['date_created', 'date_updated'], 'safe'],
            [['fio'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'fio' => 'ФИО',
            'description' => 'Описание',
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated',
        ];
    }

    /**
     * Gets query for [[Books]].
     *
     * @return ActiveQuery
     */
    public function getBooks(): ActiveQuery
    {
        return $this->hasMany(Book::class, ['author_id' => 'id']);
    }

}
