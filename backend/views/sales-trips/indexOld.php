<?php

use kartik\dynagrid\DynaGrid;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SalesTripsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sales Trips';
$this->params['breadcrumbs'][] = $this->title;
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
        ],
        'sale_date',

        [
            'attribute' => 'tzl',
            'contentOptions' => ['class' => 'truncate'],
        ],
        'start_date_time',
        'end_date',
        'vehicle_number',
        'driver_name',

        [
            'attribute' => 'border_id',
            'value' => 'borderPort.name',
        ],
        /*        [
                    'attribute' => 'trip_status',
                    //  'value'=>'borderPort.name',
                ],*/
        'driver_number',
        'serial_no',
        [
            'attribute' => 'amount',
            'value' => 'amount',
            'visible' => Yii::$app->user->can('checkAmount'),
        ],
        [
            'attribute' => 'gate_number',
            'value' => 'port.name',
        ],
        'company_name',
        [
            'attribute' => 'sales_person',
            'value' => 'salesPerson.username',
        ],
        'receipt_number',
        'passport',
        'container_number',
        [
            'attribute' => 'customer_type_id',
            'label' => 'Payment Method',
            'value' => function ($model) {
                if ($model->customer_type_id == 2) {

                    return 'BILL PAYMENT';
                } elseif ($model->customer_type_id == 1) {
                    return 'CASH PAYMENT';
                }
            }
        ],
        [
            'attribute' => 'customer_id',
            'label' => 'Bill Customer',
            'value' =>'customer.company_name',
        ],
      //  'customer_name',
        'agent',


        [
            'attribute' => 'edited_by',
            'value' => 'editedBy.username',
        ],
        'edited_at',
        [
            'attribute' => 'cancelled_by',
            'value' => 'canceledBy.username',
        ],
        'date_cancelled',
        [
            //'header' => ' Actions ',
            'format' => 'raw',
            'visible' => Yii::$app->user->can('deleteSalesTrip'),
            'value' => function ($model) {
                return

                    Html::a(Yii::t('app', '<span class="glyphicon glyphicon-remove"></span>'), ['cancel', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                            'method' => 'post',
                        ],
                    ]);
            }
        ],
    ];


    DynaGrid::begin([
        //'dataProvider'=> $dataProvider,
        // 'filterModel' => $searchModel,

        'columns' => $gridColumns,
        'theme' => 'panel-info',
        'showPersonalize' => true,

        'storage' => 'session',
        'gridOptions' => [
            'rowOptions' => function ($model2) {
                if ($model2->edited_by != '' || $model2->edited_at != '') {
                    return ['style' => 'background-color:#ffff00;'];
                } elseif ($model2->cancelled_by != '') {
                    return ['style' => 'background-color:#ff4d4d;'];
                }
            },
            'dataProvider' => $dataProvider,
            //'filterModel'=>$searchModel,
            'striped' => true,
            //  'showPageSummary' => true,
            'hover' => true,
            'toolbar' => [

                ['content' => '{dynagridFilter}{dynagridSort}{dynagrid}'],
                '{export}',
            ],
            'export' => [
                'fontAwesome' => true
            ],
            'pjaxSettings' => [
                'neverTimeout' => true,
                // 'beforeGrid'=>'My fancy content before.',
                //'afterGrid'=>'My fancy content after.',
            ],

            'panel' => [
                'type' => GridView::TYPE_INFO,
                'heading' => 'Sales Report for All devices',
                // 'before' => '<span class ="text text-orange">* Wanatakiwa kuthibitishwa kama wanakubaliwa ama wanakataliwa *</span>'
            ],
            'persistResize' => false,
            'toggleDataOptions' => ['minCount' => 50],
            'exportConfig' => [
                GridView::CSV => [
                    'label' => 'CSV',
                    'filename' => 'WEB - SALES',
                    'options' => ['title' => 'No Serial'],
                ],
            ],
        ],
        'options' => ['id' => 'dynagrid-1'] // a unique identifier is important
    ]);


    DynaGrid::end();

    ?>
</div>

<style>
    .truncate {
        max-width: 150px !important;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .truncate:hover{
        overflow: visible;
        white-space: normal;
        width: auto;
    }
</style>