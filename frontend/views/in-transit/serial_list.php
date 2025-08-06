<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SalesTripSlavesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ' ';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sales-trip-slaves-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
       // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'id',
           // 'sale_id',
            'serial_no',
            'created_at',

          //  ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
