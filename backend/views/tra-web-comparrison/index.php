<?php

use backend\models\TraWebComparrison;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\TraWebComparrisonSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Tra Web Comparrisons';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tra-web-comparrison-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Tra Web Comparrison', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'serial_no',
            'tzdl:ntext',
            'tra_count',
            'web_count',
            //'count_status',
            //'compared_by',
            //'datetime',
            //'status',
            //'route_id',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, TraWebComparrison $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
