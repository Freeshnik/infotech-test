<?php

namespace Console\Controllers;

use App\Models\User;
use App\RBAC\UserRule;
use yii\console\Controller;
use yii\helpers\Console;
use yii\rbac\DbManager;

class HelloController extends Controller
{

    public function actionInit()
    {
        $this->stdout("Hello World!" . PHP_EOL, Console::BOLD);
    }

}