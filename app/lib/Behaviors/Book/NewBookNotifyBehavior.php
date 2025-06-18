<?php

namespace App\Behaviors\Book;

use App\Jobs\Book\NewBookSmsNotifyJob;
use App\Models\SubscribeAuthor;
use Yii;
use yii\base\Behavior;
use yii\db\BaseActiveRecord;

/** Поведение для отправки уведомления о новой книге всем подписчикам автора
 * Class NewBookNotifyBehavior
 */
class NewBookNotifyBehavior extends Behavior
{
    public const ID = 'NewBookNotifyBehavior';

    /**
     * Привязка вызова методов по событиям
     * тут можно добавить привязку событий вызывая
     *
     * @return string[]
     */
    public function events(): array
    {
        return [
            BaseActiveRecord::EVENT_AFTER_INSERT => 'handlerInsert', /** @see self::handlerInsert() */
        ];
    }

    /**
     * @param $event
     * @return void
     */
    public function handlerInsert($event): void
    {
        $subscribers = SubscribeAuthor::find()->select(['user_id'])->where(['author_id' => array_column($this->owner->authors, 'id')])->column();
        if (empty($subscribers)) {
            return;
        }

        foreach ($subscribers as $userId) {
            $this->createNewJob($userId, $this->owner->id);
        }
    }

    /** Создаёт и отправляет в очередь задачу об уведомлении
     * @param int $userId
     * @return void
     */
    private function createNewJob(int $userId, int $bookId): void
    {
        $job = new NewBookSmsNotifyJob([
            'user_id' => $userId,
            'book_id' => $bookId
        ]);

        Yii::$app->queue->push($job);
    }
}