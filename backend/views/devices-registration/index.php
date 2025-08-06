<?php

use backend\models\DevicesRegistration;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\DevicesRegistrationSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Devices Registrations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="devices-registration-index">

    <p>
        <?= Html::a('New Devices', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'id',
            'serial_no',
           // 'sim_card',
            //'received_from',
            //'border_port',
            //'received_from_staff',
            //'received_at',
            //'received_status',
            //'received_by',
            //'remark:ntext',
            //'created_by',
            //'device_from',
            //'stock_status',
            //'created_at',
            //'status',
            'branch',
            //'type',
            //'device_category',
            //'released_by',
            //'released_to',
            //'transferred_from',
            //'transferred_to',
            //'transferred_date',
            //'transferred_by',
            //'released_date',
            //'sales_person',
            //'tzl',
            //'vehicle_no',
            //'container_number',
            //'sale_id',
            //'view_status',
           // 'partiner',
            'registration_date',
            'registration_by',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, DevicesRegistration $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
