<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\DevicesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '';
$this->params['breadcrumbs'][] = 'Devices';
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
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php

    $gridColumns = [
        [
            'class' => 'kartik\grid\SerialColumn',
            'contentOptions' => ['class' => 'kartik-sheet-style'],
            'width' => '36px',
            'headerOptions' => ['class' => 'kartik-sheet-style']
        ],
        'serial',
        'sim_card',
        [
            'attribute' => 'border_port',
            'value' => 'borderPort.name',
        ],
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
            'attribute' => 'created_by',
            'value' => 'createdBy.username',
        ],
        'created_at',

        [
            'attribute' => 'remark',
            'value' => function ($model) {
                if ($model->remark != null) {
                    return $model->remark;
                } else {
                    return " ";
                }
            },
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
            'heading' => 'List of products serial numbers',
            'before' => Html::a(Yii::t('app', 'Create New Devices', '<i class="fa fa-plus"></i>'), ['create'], ['class' => 'btn btn-primary', 'data-toggle' => "tooltip", 'rel' => "tooltip", 'title' => "Add employee",]),

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