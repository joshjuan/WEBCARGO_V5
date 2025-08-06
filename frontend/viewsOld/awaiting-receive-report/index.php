<?php

use kartik\dynagrid\DynaGrid;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ReceivedDevicesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '';
$this->params['breadcrumbs'][] = 'Received Devices';
?>
    <div class="received-devices-index" style="padding-top: 2%">


<?php echo $this->render('_search', ['model' => $searchModel]); ?>


    <p style="padding-bottom: 2%" />
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
        'attribute' => 'serial_no',

    ],

    [
        'attribute'=>'border_port',
        'value'=>'borderPort.name',
    ],
    [
        'attribute' => 'received_from',
        'value' => 'location.location_name',
    ],
    [
        'attribute' => 'received_at',
    ],

    [
        'attribute'=>'received_by',
        'value'=>'receivedBy.username',
        'label'=>'Sent By'
    ],
    [
        'attribute' => 'remark',
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
        'type' => GridView::TYPE_SUCCESS,
        'heading' => 'LIST OF RECEIVED DEVICES',

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
