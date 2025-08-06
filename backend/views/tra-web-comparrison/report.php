<?php
ini_set('memory_limit', '8048M');

use backend\models\Branches;
use backend\models\Devices;
use backend\models\TraWebComparrison;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\DevicesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tra Web Comparisons';
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


    <?php

    $gridColumns = [
        [
            'class' => 'kartik\grid\SerialColumn',
            'contentOptions' => ['class' => 'kartik-sheet-style'],
            'width' => '36px',
            'headerOptions' => ['class' => 'kartik-sheet-style']
        ],
        'serial_no',
        'tzdl:ntext',
        'tra_count',
        'web_count',
        [
            'filter' => Html::activeDropDownList($searchModel, 'branch', Branches::getAll(), ['class' => 'form-control', 'prompt' => '']),
            'attribute' => 'branch',
            'value' => function ($model) {
                $branch=Branches::find()
                    ->where(['id'=>$model->branch])
                    ->one();
                if ($branch) {
                    return $branch->name;
                }
                else{
                    return "NO BRANCH FOUND";
                }

            }

        ],
        [
            'filter' => Html::activeDropDownList($searchModel, 'count_status', TraWebComparrison::getArray(), ['class' => 'form-control', 'prompt' => '']),

            'attribute' => 'count_status',
            'value' => function ($model) {
                if ($model->count_status == 1) {
                    return "BOTH";
                }
                elseif ($model->count_status ==2){
                    return "TRA ONLY";
                } elseif ($model->count_status ==3){
                    return "WEB ONLY";
                }elseif ($model->count_status ==4){
                    return "NO";
                }
                else{
                    return "";
                }

            }

        ],
       // 'compared_by',
        'datetime',
        //'status',
        //'route_id',

    ];


    echo \kartik\grid\GridView::widget([
        'dataProvider' => $dataProvider,
         'filterModel' => $searchModel,
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
            'heading' => 'List of Device serial numbers compared between TRA and WEB',

        ],
        'rowOptions' => function ($model) {
            return ['data-id' => $model->id];
        },
        'exportConfig' => [
            GridView::EXCEL => [
                'filename' => Yii::t('app', 'report-') . date('Y-m-d'),
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