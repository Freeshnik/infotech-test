<?php

namespace Main\Controllers\Actions\Book;

use App\Base\WebAction;
use App\Models\Author;
use App\Models\Book;
use yii\db\Exception;
use yii\web\Response;

class BookCreate extends WebAction
{
    /**
     * @return Response|string
     * @throws Exception
     */
    public function run(): Response|string
    {
        $model = new Book();

        if ($this->getRequest()->isPost) {
            if ($model->load($this->getRequest()->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        $allAuthors = Author::find()->select(['fio'])->indexBy('id')->asArray()->column();

        return $this->render('create', [
            'model' => $model,
            'allAuthors' => $allAuthors,
        ]);
    }
}