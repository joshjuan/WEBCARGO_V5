<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\FaultDevicesReportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '';
$this->params['breadcrumbs'][] = 'Fault Devices Reports';
?>
<div class="received-devices-index" style="padding-top: 2%">


    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
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

        //  'id',
        'serial_no',

        [
            'attribute' => 'created_by',
            'value' => 'createdBy.username',
        ],

        'attribute' => 'created_at',
        [
            'attribute' => 'remarks',
            'width' => '400px',
            'contentOptions' => ['class' => 'truncate'],
        ],

        [
            'attribute' => 'status',
            'value' => function ($model) {

                if ($model->status == \backend\models\FaultDevicesReport::FAULT_DEVICE) {
                    return 'FAULT';
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
            'type' => GridView::TYPE_DANGER,
            'heading' => 'LIST OF FAULT DEVICES',

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


