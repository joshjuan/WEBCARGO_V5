<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\FaultDevicesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Fault Devices';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="received-devices-index" style="padding-top: 2%">


    <?php echo $this->render('_searchFault', ['model' => $searchModel]); ?>

    <center style="padding-top: 20px">
        <?php if (Yii::$app->user->can('makeAvailableDevices')) { ?>
            <?= Html::beginForm(['devices/await'], 'post'); ?>
            <?= Html::dropDownList('action', '', ['' => 'Select Receiver: ', '1' => 'Available Devices '], ['class' => 'dropdown','style'=>['height'=>'40px']]) ?>
            <?= Html::submitButton('Make Available',
                ['class' => 'btn btn-warning',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to Transfer ?'),
                        'method' => 'post',
                    ],]); ?>
        <?php } ?>
    </center>
    <p style="padding-bottom: 2%"/>
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
        [
            'attribute' => 'created_by',
            'value' => 'createdBy.username',
        ],
        'created_at',
//        [
//            'attribute' => 'remarks',
//            'width' => '200px',
//            'contentOptions' => ['class' => 'truncate'],
//            'value' => function ($model) {
//                if ($model->remarks != null) {
//
//                    return $model->remarks;
//
//                } else {
//                    return 'No any comment';
//                }
//            },
//        ],


        [
            'attribute' => 'status',
            'value' => function () {
                if (\frontend\models\FaultDevices::fault_device) {
                    return "FAULT DEVICE";
                }
            }
        ],

    ];


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
            'heading' => 'List of products serial numbers',

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
    <style>
        .truncate {
            max-width: 150px !important;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .truncate:hover {
            overflow: visible;
            white-space: normal;
            width: auto;
        }
    </style>
</div>