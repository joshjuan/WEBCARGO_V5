
<?php

use kartik\dynagrid\DynaGrid;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\InTransitSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Devices Moving To Borders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="received-devices-index" style="padding-top: 2%">


    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
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
    <?=Html::beginForm(['confirm'],'post');?>

    <?php

    $gridColumns = [
        [
            'class' => 'kartik\grid\ExpandRowColumn',
            'value' => function ($model, $key, $index, $column) {
                return GridView::ROW_COLLAPSED;
            },

            'detail' => function ($model, $key, $index, $column) {
                $searchModel = new \backend\models\SalesTripSlavesSearch();
                $searchModel->sale_id = $model->sale_id;
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

                return Yii::$app->controller->renderPartial('serial_list', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            },
        ],

        [
            'class' => 'kartik\grid\CheckboxColumn',
        ],
        'serial_no',
        'tzl',

        [
            'attribute'=>'border',
            'value'=>'borderPort.name',
        ],
        [
            'attribute'=>'sales_person',
            'value'=>'salesPerson.username',
        ],
        'created_at',
        [
            'attribute'=>'created_by',
            'value'=>'createdBy.username',
        ],
        'vehicle_no',
        'container_number',
        [
            'label' => 'type',
            'value' => 'type0.type0.name',
        ],
        [
            'label' => 'type',
            'value' => 'type0.type0.name',
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
                'heading' => 'IN TRANSIT DEVICES REPORT',
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

