<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\InTransitSlaveSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'In Transit Slaves';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="in-transit-slave-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create In Transit Slave', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'serial_no',
            'intansit_id',
            'branch',
            'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
