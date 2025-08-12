<?php

use common\models\ExtSalesTrips;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\ExtSalesTripsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Ext Sales Trips');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ext-sales-trips-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Ext Sales Trips'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'customer:ntext',
            'master',
            'slaves:ntext',
            'gate_id',
            //'border_id',
            //'trip_no',
            //'vehicle_no',
            //'trailer_no',
            //'merchant_no',
            //'receipt_no',
            //'agent:ntext',
            //'driver:ntext',
            //'cargo_type_id',
            //'cargo_no',
            //'chassis_no',
            //'vehicle_type',
            //'container_no',
            //'device_price:ntext',
            //'trip_status',
            //'start_date',
            //'end_date',
            //'created_at',
            //'created_by',
            //'branch',
            //'cancelled_at',
            //'cancelled_by',
            //'editted_at',
            //'editted_by',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, ExtSalesTrips $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
