<?php

use App\Models\Book;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var App\Models\BookSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Books';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Book', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'year',
            [
                'attribute' => 'author_id',
                'label' => 'Автор',
                'format' => 'raw',
                'value' => static function ($model) {
                    if ($model->author) {
                        return '[' . $model->author_id . '] ' . Html::a($model->author->fio, '/author/view?id=' . $model->author->id, ['target' => '_blank']);
                    }

                    return '';
                },
             ],
            'description:ntext',
            'isbn',
            [
                'attribute' => 'photo_path',
                'label' => 'Обложка',
                'format' => 'raw', // ВАЖНО: 'raw' или 'html', чтобы теги не экранировались
                'value' => static function ($model) {
                    if ($model->photo_path) {
                        return Html::img($model->photo_path, [
                            'alt' => 'Обложка книги',
                            'style' => 'width:100px; height:auto;' // или используйте 'class'
                        ]);
                    }

                    return '';
                },
            ],
            'date_created',
            //'date_updated',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Book $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
