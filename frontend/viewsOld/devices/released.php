<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ReleasedDevicesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Released Devices';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="received-devices-index" style="padding-top: 2%">


    <?php echo $this->render('_searchReleased', ['model' => $searchModel]); ?>
    <?php
    $pdfHeader = [
        'L' => [
            'content' => 'WEB SALES',
        ],
        'C' => [
            'content' => 'WEB SALES ' . date('m'),
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
            'content' => '&copy; WEB TECHNOLOGIES',
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
    <?php if(Yii::$app->user->can('transferDevices')){ ?>
    <?= Html::beginForm(['devices/transfer'], 'post'); ?>
    <?=  Html::dropDownList("action","",ArrayHelper::map(\frontend\models\User::find()
            ->where(['user_type'=>\frontend\models\User::PORT_STAFF])
            ->andWhere(['branch'=>Yii::$app->user->identity->branch])
            ->all(),'id','username'),['prompt' => '--- Sales Person ---','style'=>['height'=>'40px']]) ; ?>
        <?=  Html::dropDownList("points","",ArrayHelper::map(\backend\models\BorderPort::find()
            ->where(['location'=>2])
            ->andWhere(['branch'=>Yii::$app->user->identity->branch])->all(),'id','name'),['prompt' => '--- Sales Point ---','style'=>['height'=>'40px']]) ; ?>

        <?= Html::submitButton('Transfer Devices',
        ['class' => 'btn btn-warning',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to transfer Devices ?'),
                'method' => 'post',
            ],]); ?>
    <?php } ?>
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
        'serial_no',
        'released_date',
        [
            'attribute' => 'released_by',
            'value'=>'released0.username'
        ],
        [
            'attribute' => 'released_to',
            'value'=>'releasedTo0.username'
        ],
        [
            'attribute' => 'border_port',
            'value' => 'borderPort.name',
        ],
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
            'attribute' => 'type',
            'value' => 'type0.name',
        ],
        [
            'attribute'=>'status',
            'value'=>function($model){
                if($model->status == \frontend\models\ReleasedDevices::RELEASED){
                    return "RELEASED";
                }
                elseif($model->status == \frontend\models\ReleasedDevices::TRANSFERRED){
                    return "TRANSFERRED";
                }
            }
        ],

    ];


    echo \kartik\grid\GridView::widget([
        'dataProvider' => $dataProvider,
      //  'filterModel' => $searchModel,
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
            'heading' => 'List of products serial numbers',
           // 'before' =>  Html::a(Yii::t('app','Transfer Devices', '<i class="fa fa-plus"></i>'),  ['create'], ['class' => 'btn btn-primary', 'data-toggle' => "tooltip", 'rel' => "tooltip", 'title' => "Add DEVICE",]),

        ],
        'rowOptions' => function ($model) {
            return ['data-id' => $model->id];
        },
        'exportConfig' => [
            GridView::EXCEL => [
                'filename' => Yii::t('app', 'sales report-') . date('Y-m-d'),
                'showPageSummary' => true,
                'options' => [
                    'title' => 'Custom Title',
                    'subject' => 'PDF export',
                    'keywords' => 'pdf'
                ],

            ],

        ],

    ]);

    ?>
</div>

