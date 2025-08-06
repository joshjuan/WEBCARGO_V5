<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\BorderPortSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '';
$this->params['breadcrumbs'][] = 'Border Ports';
?>
<div class="border-port-index" style="padding-top: 3%">

    <div class="row">
        <div class="col-md-6">
            <strong class="lead"  style="color: #01214d;font-family: Tahoma"> <i class="fa fa-check-square text-green"></i> ECTS Portal - PORT POINTS </strong>
        </div>

        <div class="col-md-6">
            <?php if (Yii::$app->user->can('newPortGate')) { ?>
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
            'name',
       /*     [
                    'attribute'=>'location',
            'value'=>'location0.location_name',
            ],*/

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Actions',
                'template' => '{view} {update} ',
                //   'visible' => Yii::$app->user->can('super_admin'),
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
            "lengthMenu"=> [[20,-1], [20,Yii::t('app',"All")]],
            "info"=>false,
            "responsive"=>true,
            "dom"=> 'lfTrtip',
            "tableTools"=>[
                "aButtons"=> [
                    [
                        "sExtends"=> "copy",
                        "sButtonText"=> Yii::t('app',"Copy to clipboard")
                    ],[
                        "sExtends"=> "csv",
                        "sButtonText"=> Yii::t('app',"Save to CSV")
                    ],[
                        "sExtends"=> "xls",
                        "oSelectorOpts"=> ["page"=> 'current']
                    ],[
                        "sExtends"=> "pdf",
                        "sButtonText"=> Yii::t('app',"Save to PDF")
                    ],[
                        "sExtends"=> "print",
                        "sButtonText"=> Yii::t('app',"Print")
                    ],
                ]
            ]
        ],
    ]); ?>


</div>
