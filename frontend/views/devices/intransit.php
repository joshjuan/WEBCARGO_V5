<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\InTransitSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'In Transit';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="received-devices-index" style="padding-top: 2%">


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
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
    <?php echo $this->render('_searchIntransit', ['model' => $searchModel]); ?>
    <?= Html::beginForm(['intransit-to-await-storage'], 'post'); ?>
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
            'attribute' => 'device_category',
            'value' => 'cat0.name',
        ],
        'tzl',
        [
            'label' => 'Port Gate',
            'attribute' => 'gate_number',
            'value' => 'gate0.name',
        ],
        [
            'label' => 'Border',
            'attribute' => 'border_port',
            'value' => 'borderPort.name',
        ],
        [
            'label' => 'Tagger',
            'attribute' => 'sales_person',
            'value' => 'released0.username',
        ],
        [
            'attribute' => 'created_at',
            // 'value'=>'transferred0.username'
        ],
        [
            'attribute' => 'vehicle_no',
            // 'value'=>'transferredTo0.username'
        ],
        [
            'attribute' => 'container_number',
            // 'value'=>'transferredTo0.username'
        ],
        [
            'attribute' => 'created_at',
            'label' => 'Days Intransit',
            'class' => 'kartik\grid\FormulaColumn',
            'value' => function ($model, $key, $index, $widget) {
                $time = new \DateTime('now');
                $datetime2 = new DateTime($model->created_at);
                $interval = $time->diff($datetime2)->days;
                return $interval;
            },
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
            'type' => GridView::TYPE_INFO,
            'heading' => 'List of devices In transit (NOT DEACTIVATED)',
            'before' => Yii::$app->user->can('intransitToAwaitingStorage') ?Html::submitButton('<i class="fa fa-check"></i>Send to Await storage', ['class' => 'btn btn-success', 'data' => [
                'confirm' => Yii::t('app', 'Are you sure ?'),
                'method' => 'post',
            ],]): '',
        ],
        'rowOptions' => function ($model) {
            return ['data-id' => $model->id];
        },
        'exportConfig' => [
            GridView::EXCEL => [
                'filename' => Yii::t('app', 'intransit report-') . date('Y-m-d'),
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

