<?php

use backend\models\SysPensionersVerificationsLogs;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\SysPensionersVerificationsLogsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Sys Pensioners Verifications Logs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sys-pensioners-verifications-logs-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Sys Pensioners Verifications Logs', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'date_time',
            'uid',
            'pension_number',
            'full_names',
            //'verification_code',
            //'code_expiry_date',
            //'sent_date_time',
            //'batch_code',
            //'kin_mobile_number',
            //'verified_by_uid',
            //'verified_date_time',
            //'verification_status',
            //'checker_remarks:ntext',
            //'date_renewed',
            //'start_date',
            //'expiry_date',
            //'verified_location',
            //'verified_by',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, SysPensionersVerificationsLogs $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
