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
            <strong class="lead"  style="color: #01214d;font-family: Tahoma"> <i class="fa fa-check-square text-green"></i> ECTS Portal - BORDER PORT</strong>
        </div>

        <div class="col-md-6">
            <?php if (Yii::$app->user->can('admin')||Yii::$app->user->can('addBorder')) { ?>
            <?= Html::a(Yii::t('app', '<i class="fa fa-map-marker"></i> Add New'), ['create'], ['class' => 'btn btn-primary waves-effect waves-light']) ?>
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
            [
                    'attribute'=>'location',
            'value'=>'location0.location_name',
            ],

            [
               // 'visible' => Yii::$app->user->can('admin')||Yii::$app->user->can('addBorder'),
                    'class' => 'yii\grid\ActionColumn'
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
