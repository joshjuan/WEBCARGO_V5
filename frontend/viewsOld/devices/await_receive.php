<?php

use kartik\dynagrid\DynaGrid;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ReceivedDevicesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '';
$this->params['breadcrumbs'][] = 'Awaiting Receive devices';
?>
    <div class="received-devices-index" style="padding-top: 2%">


<?php echo $this->render('_searchAwaiting', ['model' => $searchModel]); ?>

    <center style="padding-top: 20px">
        <?php if (Yii::$app->user->can('awaitingReceiveDevices')) { ?>
            <?= Html::beginForm(['devices/receive'], 'post'); ?>
            <?= Html::dropDownList('action', '', ['' => 'Select Option: ', '1' => 'RECEIVE DEVICES', '2' => 'IN TRANSIT'], ['class' => 'dropdown', 'style' => ['height' => '40px']]) ?>
            <?= Html::submitButton('Receive Serials',
                ['class' => 'btn btn-warning',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to Receive Devices ?'),
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
        'attribute' => 'border_port',
        'value' => 'borderPort.name',
    ],
    // 'received_from_staff',
    //  'received_at',
    //'status',
    //'view_status',
    [
        'attribute' => 'received_from',
        'value' => function ($model) {
            if ($model->received_from != 0) {
                return $model->location->location_name;
            } else if ($model->received_from == -1) {
                return "FROM MANUFACTURE";
            } else {
                return "REVRESED";
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
        'attribute' => 'received_by',
        'value' => 'receivedBy.username',
    ],
    [
        'attribute' => 'received_at',
    ],

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