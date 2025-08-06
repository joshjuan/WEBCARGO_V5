<?php

use backend\models\DevicesReports;
use frontend\models\Devices;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\DevicesReportsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Devices Reports';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="received-devices-index" style="padding-top: 2%">


    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

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
            'content' => '&copy; WEB CARGO',
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
        'serial_no',
       // 'sim_card',
        [
            'attribute' => 'received_from',
            'value' => function ($model) {
                if ($model->received_from == Devices::registration) {
                    return "Registered";
                } else if ($model->received_from == Devices::accounts) {
                    return "Accounts";
                } else if ($model->received_from == Devices::new_items) {
                    return "New Items";
                } else if ($model->received_from == Devices::awaiting_store) {
                    return "Awaiting Storage";
                } else if ($model->received_from == Devices::store) {
                    return "Store";
                } else if ($model->received_from == Devices::awaiting_allocation) {
                    return "Awaiting Allocation";
                } else if ($model->received_from == Devices::released) {
                    return "Release";
                } else if ($model->received_from == Devices::on_road) {
                    return "In Transit";
                } else if ($model->received_from == Devices::return_to_office) {
                    return "Return to office";
                } else if ($model->received_from == Devices::in_transit) {
                    return "In transit";
                } else if ($model->received_from == Devices::fault_devices) {
                    return "Fault";
                } else if ($model->received_from == Devices::damaged) {
                    return "Damaged";
                } else {
                    return 'current status not found';
                }
            },
        ],
        [
            'attribute' => 'received_to',
            'value' => function ($model) {
                if ($model->received_to == Devices::registration) {
                    return "Registered";
                } else if ($model->received_to == Devices::accounts) {
                    return "Accounts";
                } else if ($model->received_to == Devices::new_items) {
                    return "New Items";
                } else if ($model->received_to == Devices::awaiting_store) {
                    return "Awaiting Storage";
                } else if ($model->received_to == Devices::store) {
                    return "Store";
                } else if ($model->received_to == Devices::awaiting_allocation) {
                    return "Awaiting Allocation";
                } else if ($model->received_to == Devices::released) {
                    return "Release";
                } else if ($model->received_to == Devices::on_road) {
                    return "In Transit";
                } else if ($model->received_to == Devices::return_to_office) {
                    return "Return to office";
                } else if ($model->received_to == Devices::in_transit) {
                    return "In transit";
                } else if ($model->received_to == Devices::fault_devices) {
                    return "Fault";
                } else if ($model->received_to == Devices::damaged) {
                    return "Damaged";
                } else {
                    return 'current status not found';
                }
            },
        ],
       // 'received_from',
       // 'received_to',
       // 'border_port',
        [
            'attribute' => 'border_port',
            'value' => 'borderPort.name',
        ],
        [
            'attribute' => 'created_by',
            'value' => 'createdBy.username',
        ],
        'created_at',
        [
            'attribute' => 'device_category',
            'value' => 'cat0.name',
        ],
        [
            'attribute' => 'type',
            'value' => 'type0.name',
        ],
        //'received_from_staff',
       // 'received_at',
        //'received_status',
        //'received_by',
        'remark:ntext',
       // 'created_by',

       // 'branch',
       // 'type',
        //'device_category',
        //'released_by',
        //'released_to',
        //'transferred_from',
        //'transferred_to',
        //'transferred_date',
        //'transferred_by',
        //'released_date',
        //'sales_person',
        //'tzl',
        //'vehicle_no',
        //'container_number',
        //'sale_id',
        //'received_from',
        //'registration_date',
        //'registration_by',
        //'movement_unique_id',

    ];


    echo \kartik\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
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
            'type' => GridView::TYPE_WARNING,
            'heading' => 'List of devices movements logs',

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
