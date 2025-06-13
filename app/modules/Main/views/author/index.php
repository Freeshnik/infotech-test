<?php

use App\Models\Author;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var App\Models\AuthorSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var bool $canManage */

$this->title = 'Авторы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if ($canManage): ?>
            <?= Html::a('Create Author', ['create'], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'fio',
            'description:text',
            'date_created',
            'date_updated',
            [
                'class' => ActionColumn::class,
                'urlCreator' => static function ($action, Author $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 },
                'visibleButtons' => [
                    'update' => $canManage,
                    'delete' => $canManage,
                    // кнопка 'view' видна всегда по умолчанию
                ]
            ],
        ],
    ]); ?>


</div>
