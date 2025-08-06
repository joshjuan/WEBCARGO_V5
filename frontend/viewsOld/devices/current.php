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
    <?php echo $this->render('_searchCurrent', ['model' => $searchModel]); ?>
    <?php

    $gridColumns = [
        [
            'class' => 'kartik\grid\SerialColumn',
            'contentOptions' => ['class' => 'kartik-sheet-style'],
            'width' => '36px',
            'headerOptions' => ['class' => 'kartik-sheet-style']
        ],
        'serial_no',
     //   'sim_card',
//        [
//            'attribute' => 'device_from',
//            'value' => 'borderPort.name',
//        ],
       // 'received_from_staff',
      //  'received_at',
        //'status',

//        [
//            'attribute' => 'stock_status',
//            'value' => function ($model) {
//                if ($model->received_from != 0) {
//                    return $model->location->location_name;
//                } else if ($model->received_from == -1) {
//                    return "FROM MANUFACTURE";
//                } else {
//                    return "REVRESED";
//                }
//            },
//        ],
        [
            'label' => 'Current Status',
            'value' => function ($model) {
                if ($model->view_status == Devices::awaiting_receive) {
                    return "AWAITING RECEIVE";
                } else if ($model->view_status == Devices::received_devices) {
                    return "RECEIVED";
                } else if ($model->view_status == Devices::stock_devices) {
                    return "AVAILABLE OR IN TRANSIT";
                }else if ($model->view_status == Devices::released_devices) {
                    return "RELEASED";
                } else if ($model->view_status == Devices::in_transit) {
                    return "IN TRANSIT";
                }else if ($model->view_status == Devices::fault_devices) {
                    return "FAULT";
                }else{
                    return '';
                }
            },
        ],
        [
            'attribute' => 'branch',
            'value' => 'branch0.name',
        ],
        [
            'attribute' => 'type',
            'value' => 'type0.name',
        ],
        [
            'attribute' => 'device_category',
            'value' => 'category0.name',
        ],
        [
            'attribute' => 'created_by',
            'value' => 'createdBy.username',
            'label' => 'Sales By',
        ],
        [
            'attribute' => 'created_at',
            'label' => 'Sales Date',
        ],

        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view}',
            'header' => 'Actions',
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a('<span class="btn btn-sm btn-primary"><b class="fa fa-feed"></b></span>',
                        ['activate', 'id' => $model['id']],
                        ['title' => 'Activate', 'class' => '']);
                },

            ]
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
            'heading' => 'List of  devices and current status',
         //   'before' => Html::a(Yii::t('app', 'Create New Devices', '<i class="fa fa-plus"></i>'), ['create'], ['class' => 'btn btn-primary', 'data-toggle' => "tooltip", 'rel' => "tooltip", 'title' => "Add employee",]),

        ],
        'rowOptions' => function ($model) {
            return ['data-id' => $model->id];
        },
        'exportConfig' => [
            GridView::EXCEL => [
                'filename' => Yii::t('app', 'Curret Status report-') . date('Y-m-d'),
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