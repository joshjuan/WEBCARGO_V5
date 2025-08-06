<?php

use frontend\models\BorderPort;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\StockDevicesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '';
$this->params['breadcrumbs'][] = 'Stock Devices';
?>
<div class="received-devices-index" style="padding-top: 2%">


    <?php echo $this->render('_searchReleased', ['model' => $searchModel]); ?>
    <?php
    $catList = \frontend\models\User::getPortUser();
    ?>

    <?php if (Yii::$app->user->can('transferDevicesToOtherTaggers')) { ?>
        <?= Html::beginForm(['devices/released-to-tagger'], 'post'); ?>

        <?php
        $office=[
            //  'id'=>0,
            '0'=>'RETURN TO AWAITING STORAGE'
        ];
        $users=ArrayHelper::map(\frontend\models\User::find()
            ->where(['user_type' => \frontend\models\User::PORT_STAFF])
            ->andWhere(['status' =>10])
            ->andWhere(['branch' =>Yii::$app->user->identity->branch])
            ->all(), 'id', 'username');
        $all=$office+ $users;


        echo Html::dropDownList("action", "",$all , ['prompt' => '--- Sales Person ---','style'=>['height'=>'33px']]); ?>
        <?php  Html::dropDownList("points","",ArrayHelper::map(\backend\models\BorderPort::find()
            // ->where(['location'=>2])
            //  ->andWhere(['branch'=>Yii::$app->user->identity->branch])
            ->all(),'id','name'),['prompt' => '--- Sales Point ---','style'=>['height'=>'33px']]) ; ?>

        <?= Html::submitButton('Transfer Devices',
            ['class' => 'btn btn-warning',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to Transfer Devices ?'),
                    'method' => 'post',
                ],]); ?>
    <?php } ?>

    <?php
    $pdfHeader = [
        'L' => [
            'content' => 'WEB CARGO',
        ],
        'C' => [
            'content' => 'WEB CARGO ' . date('m'),
            'font-size' => 10,
            'font-style' => 'B',
            'font-family' => 'arial',
            'color' => '#333333'
        ],
        'R' => [
            'content' => 'Sales:' . date('Y-m-d H:i:s'),
        ],
        'line' => true,
    ];

    $pdfFooter = [
        'L' => [
            'content' => '&copy; WEB CORP',
            'font-size' => 10,
            'color' => '#333333',
            'font-family' => 'arial',
        ],
        'C' => [
            'content' => '',
        ],
        'R' => [
            //'content' => 'RIGHT CONTENT (FOOTER)',
            'font-size' => 10,
            'color' => '#333333',
            'font-family' => 'arial',
        ],
        'line' => true,
    ];
    ?>

    <?php

    $gridColumns = [
        [
            'class' => 'kartik\grid\SerialColumn',
            'contentOptions' => ['class' => 'kartik-sheet-style'],
            'width' => '36px',
            'headerOptions' => ['class' => 'kartik-sheet-style']
        ],
        [
            'class' => 'kartik\grid\CheckboxColumn',
        ],

        [
            'attribute' => 'serial_no',

        ],
        [
            'attribute' => 'device_category',
            'value' => 'cat0.name',
        ],
        [
            'attribute' => 'type',
            'value' => 'type0.name',
        ],
        'released_date',
        [
            'attribute' => 'released_by',
            'value'=>'released0.username'
        ],
        [
            'attribute' => 'released_to',
            'value'=>'releasedTo0.username'
        ],
//        [
//            'attribute' => 'border_port',
//            'value' => 'borderPort.name',
//        ],
        [
            'attribute' => 'transferred_from',
            'value'=>'transferred0.username'
        ],
        [
            'attribute' => 'transferred_to',
            'value'=>'transferredTo0.username'
        ],
        [
            'attribute' => 'transferred_by',
            'value'=>'transferredBy.username'
        ],

        'transferred_date',
        [
            'attribute' => 'created_at',
            'label' => 'Days on Release',
            'class' => 'kartik\grid\FormulaColumn',
            'value' => function ($model, $key, $index, $widget) {
                $time = new \DateTime('now');
                $datetime2 = new DateTime($model->created_at);
                $interval = $time->diff($datetime2)->days;
                return $interval;
            },
        ],


    ];

    // the GridView widget (you must use kartik\grid\GridView)
    echo \kartik\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'id' => 'grid',
        'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
        'beforeHeader' => [
            [
                'options' => ['class' => 'skip-export'] // remove this row from export
            ]
        ],

        'pjax' => true,
        'bordered' => true,
        'striped' => true,
        'condensed' => true,
        'responsive' => true,
        'hover' => true,
        //   'floatHeader' => true,

        //   'floatHeaderOptions' => ['scrollingTop' => true],
        'showPageSummary' => true,
        'panel' => [
            'type' => GridView::TYPE_INFO,
            'heading' => 'List of devices released to Tagger',
            'before' => '<span class ="text text-orange"></span>'
        ],
        'rowOptions' => function ($model) {
            return ['data-id' => $model->id];
        },
        'exportConfig' => [
            GridView::EXCEL => [
                'filename' => Yii::t('app', 'released Devices-') . date('Y-m-d'),
                'showPageSummary' => true,
                'options' => [
                    'title' => 'Custom Title',
                    'subject' => 'PDF export',
                    'keywords' => 'pdf'
                ],

            ],

        ],

    ]); ?>
