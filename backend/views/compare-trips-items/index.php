<?php

use backend\models\CompareTripsItems;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\CompareTripsItemsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Compare Trips Items';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="compare-trips-items-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Compare Trips Items', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'tzdl',
            'serial_no',
            'route',
            'vehicle_no',
            //'departure',
            //'destination',
            //'vendor',
            //'cargo_type',
            //'activation_date',
            //'activated_by',
            //'deactivated_by',
            //'deactivate_date',
            //'status',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, CompareTripsItems $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
