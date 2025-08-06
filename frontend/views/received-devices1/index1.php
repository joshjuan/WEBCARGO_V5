<?php

use kartik\dynagrid\DynaGrid;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ReceivedDevicesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Received Devices';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="received-devices-index">

    <p>
        <?= Html::a('Create Received Devices', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <div style="padding-top: 20px">
        <?= Html::beginForm(['received-devices/store'], 'post'); ?>
        <?= Html::dropDownList('action', '', ['' => 'Select Receiver: ', '1' => 'AVAILABLE STOCK ', '2' => 'ICT DEPT'], ['class' => 'dropdown',]) ?>
        <?= Html::submitButton('Allocated Serials',
            ['class' => 'btn btn-info',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to Transfer ?'),
                    'method' => 'post',
                ],]); ?>
    </div>
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
    //  ['class' => 'yii\grid\SerialColumn'],
    [
        'class' => 'kartik\grid\SerialColumn',
        'contentOptions' => ['class' => 'kartik-sheet-style'],
        'width' => '36px',
        'headerOptions' => ['class' => 'kartik-sheet-style']
    ],

    [
        'class'=>'kartik\grid\CheckboxColumn',
       // 'id',
        //'headerOptions'=>['class'=>'kartik-sheet-style'],
    ],
    'id',
    [
        'attribute' => 'serial_no',

    ],
    [
        'attribute' => 'border_port',
    ],

    [
        'attribute' => 'received_from',
    ],
    [
        'attribute' => 'received_at',
    ],
    [
        'attribute' => 'received_status',
    ],
    [
        'attribute' => 'received_by',
    ],
    [
        'attribute' => 'remark',
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
        'dataProvider' => $dataProvider,
        'filterModel'=>$searchModel,
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
            'heading' => 'List of products serial numbers',
            // 'before' => '<span class ="text text-orange">* Wanatakiwa kuthibitishwa kama wanakubaliwa ama wanakataliwa *</span>'
        ],
        'persistResize' => false,
        'toggleDataOptions' => ['minCount' => 50],

    ],
    'options' => ['id' => 'dynagrid-1'] // a unique identifier is important
]);


DynaGrid::end();
?>