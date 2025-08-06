<?php

use frontend\models\Devices;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\DevicesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="received-devices-index" style="padding-top: 2%">
    <?php echo $this->render('_searchAccounts', ['model' => $searchModel]); ?>

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
    <?= Html::beginForm(['accounts-to-awaitstore'], 'post'); ?>
    <?php

    $gridColumns = [
        [
            'class' => 'kartik\grid\SerialColumn',
            'contentOptions' => ['class' => 'kartik-sheet-style'],
            'width' => '36px',
            'headerOptions' => ['class' => 'kartik-sheet-style']
        ],
//        [
//            'class' => 'kartik\grid\CheckboxColumn',
//        ],
        'serial_no',
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
            'attribute' => 'branch',
            'value' => 'branch0.name',
        ],
        [
            'attribute' => 'device_category',
            'value' => 'cat0.name',
        ],
//        [
//            'attribute' => 'type',
//            'value' => 'type0.name',
//        ],
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
            'heading' => 'List of Devices serial numbers in accounts',
//            'before' => Html::submitButton('<i class="fa fa-check"></i> Await Storage', ['class' => 'btn btn-success', 'data' => [
//                'confirm' => Yii::t('app', 'Are you sure you want to process ?'),
//                'method' => 'post',
//            ],]),
        ],
        'rowOptions' => function ($model) {
            return ['data-id' => $model->id];
        },
        'exportConfig' => [
            GridView::EXCEL => [
                'filename' => Yii::t('app', 'accounts device-') . date('Y-m-d'),
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