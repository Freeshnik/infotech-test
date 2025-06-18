<?php

namespace Console\Controllers;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use App\Jobs\Book\NewBookSmsNotifyJob;

class HelloController extends Controller
{

    public function actionInit()
    {
        $this->stdout("Hello World!" . PHP_EOL, Console::BOLD);
    }


    /**
     * @CLI docker exec -it yii2-php php yii hello
     * @return void
     */
    public function actionIndex()
    {
        $job = new NewBookSmsNotifyJob([
            'user_id' => 1,
            'book_id' => 47,
        ]);

        Yii::$app->queue->push($job);
    }
}