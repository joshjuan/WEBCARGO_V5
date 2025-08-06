<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\LocationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '';
$this->params['breadcrumbs'][] = 'Locations';
?>
<div class="location-index">


    <div class="row" style="padding-top: 3%">
        <div class="col-md-6">
            <strong class="lead" style="color: #01214d;font-family: Tahoma"> <i class="fa fa-map-marker"></i> ECTS Portal - LOCATIONS</strong>
        </div>

        <div class="col-md-6">

            <?php if (Yii::$app->user->can('newLocation')) { ?>
                <?= Html::a(Yii::t('app', '<i class="fa fa-map-marker"></i> New Location'), ['create'], ['class' => 'btn btn-primary waves-effect waves-light']) ?>
            <?php } ?>


        </div>
    </div>
    <p style="padding-top: 3%"/>
    <?= \fedemotta\datatables\DataTables::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'location_name',

            [
                    'template'=>'{view}{update}',
                'class' => 'yii\grid\ActionColumn',
                //'visible' => Yii::$app->user->can('admin')||Yii::$app->user->can('addLocation'),
            ],
        ],
    ]); ?>


</div>
