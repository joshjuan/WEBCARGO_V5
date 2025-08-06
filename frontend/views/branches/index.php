
<?php


use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;


$this->title ='';
$this->params['breadcrumbs'][] = Yii::t('yii', 'lIST OF BRANCHES');
?>
<div class="clerk-deni-index" style="padding-top: 10px">

    <?php $gridColumns = [
        [
            'class' => 'kartik\grid\SerialColumn',
            'contentOptions' => ['class' => 'kartik-sheet-style'],
            'width' => '36px',
            'headerOptions' => ['class' => 'kartik-sheet-style']
        ],
        'name',
        [
            'attribute' => 'branch_type',
            'format'=>'html',
            'value' => function ($model) {
                if ($model->branch_type =='1') {
                    return '<span style="color:limegreen;">OPERATIONAL</span>';
                } elseif ($model->branch_type =='0') {
                    return '<span style="color:red;">NON OPERATIONAL </span>';
                } else {
                    return '<span style="color:red;">NON OPERATIONAL</span>';
                }
            }
        ],
        [
            'attribute' => 'status',
            'format'=>'html',
            'value' => function ($model) {
                if ($model->status =='1') {
                    return '<span style="color:limegreen;">ACTIVATED</span>';
                } elseif ($model->status =='0') {
                    return '<span style="color:red;">DEACTIVATED</span>';
                } else {
                    return '<span style="color:red;">DEACTIVATED</span>';
                }
            }
        ],
        'created_at',
        'created_by',
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => 'Actions',
            'template' => '{update}',
            'buttons' => [
                'view' => function ($url, $model) {
                    $url = ['view', 'id' => $model->id];
                    return Html::a('<span class="fa fa-eye"></span>', $url, [
                        'title' => 'View',
                        'data-toggle' => 'tooltip', 'data-original-title' => 'Save',
                        'class' => 'btn btn-primary',

                    ]);

                },


            ]
        ],

    ];

    echo \kartik\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'rowOptions' => function ($model, $key, $index, $grid) {
            return ['data-id' => $model->id];
        },
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
        'floatHeader' => false,
        'floatHeaderOptions' => ['scrollingTop' => true],
        'showPageSummary' => true,
        'panel' => [
            'heading' => '<i class="fa fa-bars"></i>'.Yii::t('yii','LIST OF BRANCHES'),
            'type' => GridView::TYPE_SUCCESS,
            'before' => Html::a('New Branch', ['create'], ['class' => 'btn btn-success']),

        ],
        'exportConfig' => [
            GridView::EXCEL => [
                'label' => Yii::t('yii', 'Export All Data'),
                'showHeader' => true,
                'showPageSummary' => true,
                'showFooter' => true,
                'filename' => Yii::t('yii', 'LIST OF BRANCHES'),
                'alertMsg' => Yii::t('yii', 'The PDF export file will be generated for download.'),
                'options' => ['title' => Yii::t('yii', 'Portable Document Format')],
                'config' => [
                    'mode' => 'c',
                    'marginTop' => 50,
                    'marginBottom' => 50,
                    'cssInline' => '.kv-wrap{padding:20px;}' .
                        '.kv-align-center{text-align:center;}' .
                        '.kv-align-left{text-align:left;}' .
                        '.kv-align-right{text-align:right;}' .
                        '.kv-align-top{vertical-align:top!important;}' .
                        '.kv-align-bottom{vertical-align:bottom!important;}' .
                        '.kv-align-middle{vertical-align:middle!important;}' .
                        '.kv-page-summary{border-top:4px double #ddd;font-weight: bold;}' .
                        '.kv-table-footer{border-top:4px double #ddd;font-weight: bold;}' .
                        '.kv-table-caption{font-size:1.5em;padding:8px;border:1px solid #ddd;border-bottom:none;}',

                    'methods' => [
                        'SetHeader' => [
                            ['odd' => '', 'even' => '']
                        ],
                        'SetFooter' => [
                            ['odd' => '', 'even' => '']
                        ],
                    ],

                ]
            ],
        ]


    ]);

    ?>
</div>
<style>
    .truncate {
        max-width: 250px !important;
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

