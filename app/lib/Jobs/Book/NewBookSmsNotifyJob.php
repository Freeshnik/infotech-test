<?php

namespace App\Jobs\Book;

use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use App\Models\Book;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class NewBookSmsNotifyJob extends BaseObject implements JobInterface
{
    /** API KEY в .env по хорошему надо */
    public const API_KEY = 'XXXXXXXXXXXXYYYYYYYYYYYYZZZZZZZZXXXXXXXXXXXXYYYYYYYYYYYYZZZZZZZZ';

    public int $user_id;
    public int $book_id;

    public function execute($queue): void
    {
        $user = User::findOne($this->user_id);
        /** @var  $book Book */
        $book = Book::find()->where(['id' => $this->book_id])->with('author')->one();

        if (!$user || !$book) {
            return;
        }



        $client = new Client();
        $query = $this->buildQuery($book, $user);
        
        try {
            $response = $client->get('https://smspilot.ru/api.php', [
                'query' => $query,
                'timeout' => 10,
                'connect_timeout' => 5,
            ]);
            
            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();
            
            Yii::info([
                'status' => $statusCode,
                'response' => $body,
                'phone' => $user->phone,
                'book_id' => $this->book_id
            ], 'sms_book_notification');



        } catch (GuzzleException $e) {
            Yii::error([
                'error' => $e->getMessage(),
                'phone' => $user->phone,
                'book_id' => $this->book_id
            ], 'sms_book_notification_error');
        }
    }

    /**
     * @param Book $book
     * @param User $user
     * @return array
     */
    public function buildQuery(Book $book, User $user): array
    {
        return [
            'send' => 'Вышла новая книга "' . $book->title . '" от автора: ' . $book->author->fio,
            'to' => str_replace('+', '', $user->phone),
            'apikey' => self::API_KEY,
            'format' => 'json'
        ];
    }
}