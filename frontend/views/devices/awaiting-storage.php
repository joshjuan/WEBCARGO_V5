<?php

use frontend\models\Devices;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\DevicesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Devices';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="received-devices-index" style="padding-top: 2%">


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
            'content' => '&copy; WEB CORPORATION',
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
    <?php echo $this->render('_searchAwaitingStorage', ['model' => $searchModel]); ?>
    <?= Html::beginForm(['awaiting-to-store'], 'post'); ?>

    <div style="padding-top: 10px; padding-bottom: 10px">
        <?php if (Yii::$app->user->can('awaitStorageToStoreToIntransitToFault')) { ?>
            <?= Html::beginForm(['devices/awaiting-to-store'], 'post'); ?>
            <?= Html::dropDownList('action', '', ['' => 'Select Option: ', '1' => 'Send to Store', '2' => 'Send to Maintenance', '3' => 'Sent to Intransit'], ['class' => 'dropdown', 'style' => ['height' => '33px']]) ?>
            <?= Html::submitButton('Process',
                ['class' => 'btn btn-success',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to process ?'),
                        'method' => 'post',
                    ],]); ?>
        <?php } ?>
    </div>



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
            'attribute' => 'border_port',
            'value' => 'borderPort.name',
        ],
        [
            'attribute' => 'view_status',
            'value' => function ($model) {
                if ($model->view_status == Devices::registration) {
                    return "Registered";
                } else if ($model->view_status == Devices::accounts) {
                    return "Accounts";
                } else if ($model->view_status == Devices::new_items) {
                    return "New Items";
                } else if ($model->view_status == Devices::awaiting_store) {
                    return "Awaiting Storage";
                } else if ($model->view_status == Devices::store) {
                    return "Store";
                } else if ($model->view_status == Devices::awaiting_allocation) {
                    return "Awaiting Allocation";
                } else if ($model->view_status == Devices::released) {
                    return "Release";
                } else if ($model->view_status == Devices::on_road) {
                    return "In Transit";
                } else if ($model->view_status == Devices::return_to_office) {
                    return "Return to office";
                } else if ($model->view_status == Devices::in_transit) {
                    return "In transit";
                } else if ($model->view_status == Devices::fault_devices) {
                    return "Fault";
                } else if ($model->view_status == Devices::damaged) {
                    return "Damaged";
                } else {
                    return 'current status not found';
                }
            },
        ],
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
            'attribute' => 'branch',
            'value' => 'branch0.name',
        ],
        [
            'attribute' => 'device_category',
            'value' => 'cat0.name',
        ],
        [
            'attribute' => 'type',
            'value' => 'type0.name',
        ],
        [
            'attribute' => 'created_by',
            'value' => 'create0.username',
        ],
        'created_at',


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
            'heading' => 'List of Devices serial numbers in awaiting storage',
//            'before' => Html::submitButton('<i class="fa fa-check"></i> Process to Store', ['class' => 'btn btn-success', 'data' => [
//                'confirm' => Yii::t('app', 'Are you sure you want to process ?'),
//                'method' => 'post',
//            ],]),
        ],
        'rowOptions' => function ($model) {
            return ['data-id' => $model->id];
        },
        'exportConfig' => [
            GridView::EXCEL => [
                'filename' => Yii::t('app', 'await-storage device-') . date('Y-m-d'),
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