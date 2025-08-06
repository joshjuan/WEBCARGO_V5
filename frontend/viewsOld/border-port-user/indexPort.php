<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\BorderPortUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '';
$this->params['breadcrumbs'][] = 'Border Port Users';
?>
<div class="border-port-user-index" style="padding-top: 3%">

    <div class="row">
        <div class="col-md-6">
            <strong class="lead" style="color: #01214d;font-family: Tahoma"> <i
                        class="fa fa-check-square text-green"></i> CARGO MIS - USER ALLOCATED TO SALES POINTS</strong>
        </div>

        <div class="col-md-6">
            <?php if (Yii::$app->user->can('admin') || Yii::$app->user->can('addUserAllocated')) { ?>
                <?= Html::a(Yii::t('app', '<i class="fa fa-map-marker"></i> Add New'), ['create-port'], ['class' => 'btn btn-primary waves-effect waves-light']) ?>
            <?php } ?>

        </div>
    </div>

    <p style="padding-top: 2%"/>

    <?= \fedemotta\datatables\DataTables::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //  'id',
            [
                'attribute' => 'border_port',
                'value' => 'borderPort.name',
            ],


            [
                'attribute' => 'name',
                'value' => 'userAssigned.username',
            ],
            'assigned_date',
            [
                'attribute' => 'assigned_by',
                'value' => 'userAssignedBy.username',
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Actions',
                'template' => '{view} {update} ',

                'buttons' => [
                    'view' => function ($url, $model) {
                        $url = ['view-port', 'id' => $model->id];
                        return Html::a('<span class="fa fa-eye"></span>', $url, [
                            'title' => 'View',
                            'data-toggle' => 'tooltip', 'data-original-title' => 'Save',
                            'class' => 'btn btn-primary',

                        ]);


                    },
                    'update' => function ($url, $model) {
                        $url = ['update-port', 'id' => $model->id];
                        return Html::a('<span class="fa fa-pencil"></span>', $url, [
                            'title' => 'Edit',
                            'data-toggle' => 'tooltip', 'data-original-title' => 'Save',
                            'class' => 'btn btn-info',

                        ]);


                    },

                    'delete' => function ($url, $model) {
                        $url = ['delete-port', 'id' => $model->id];
                        return Html::a('<span class="fa fa-remove"></span>', $url, [
                            'title' => 'Delete',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],
                            'data-toggle' => 'tooltip', 'data-original-title' => 'Save',
                            'class' => 'btn btn-danger',


                        ]);


                    },


                ]
            ],
        ],
        'clientOptions' => [
            "lengthMenu" => [[100, -1], [100, Yii::t('app', "All")]],
            "info" => false,
            "responsive" => true,
            "dom" => 'lfTrtip',
            "tableTools" => [
                "aButtons" => [
                    [
                        "sExtends" => "copy",
                        "sButtonText" => Yii::t('app', "Copy to clipboard")
                    ], [
                        "sExtends" => "csv",
                        "sButtonText" => Yii::t('app', "Save to CSV")
                    ], [
                        "sExtends" => "xls",
                        "oSelectorOpts" => ["page" => 'current']
                    ], [
                        "sExtends" => "pdf",
                        "sButtonText" => Yii::t('app', "Save to PDF")
                    ], [
                        "sExtends" => "print",
                        "sButtonText" => Yii::t('app', "Print")
                    ],
                ]
            ]
        ],
    ]); ?>


</div>
