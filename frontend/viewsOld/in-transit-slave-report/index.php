<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\InTransitSlaveReportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'In Transit Slave Reports';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="in-transit-slave-report-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create In Transit Slave Report', ['create'], ['class' => 'btn btn-success']) ?>
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
            'created_at',
            'branch',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
