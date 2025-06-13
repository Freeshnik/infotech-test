<?php

namespace Main\Controllers\Actions\Book;

use App\Base\WebAction;
use App\Models\BookSearch;
use App\Models\User;
use Yii;


class BookIndex extends WebAction
{
    /**
     * @return string
     */
    public function run(): string
    {
        $searchModel = new BookSearch();
        $dataProvider = $searchModel->search($this->getRequest()->getQueryParams());

        $canManage = !Yii::$app->user->isGuest && Yii::$app->user->identity->type === User::TYPE_USER;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'canManage' => $canManage,
        ]);
    }
}