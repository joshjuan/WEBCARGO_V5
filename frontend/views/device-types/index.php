<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DeviceTypesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Device Types';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="device-types-index">


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summary'=>'',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'id',
            'name',

          //  ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
