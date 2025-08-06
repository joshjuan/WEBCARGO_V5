<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\AuthItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '';
$this->params['breadcrumbs'][] = 'Permissions';
?>
<div class="auth-item-index">
    <center>
        <h3>
            <i class="fa fa-th-large text-default">
                <strong> PERMISSIONS LIST </strong>
            </i>
        </h3>
    </center>
    <h1><?php // Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= \fedemotta\datatables\DataTables::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            //'type',
            'description:ntext',
            /*    [
                        'attribute' => 'module',
                        'value' => 'modul.name'
                ],*/
            //'data:ntext',
            // 'created_at',
            // 'updated_at',

            [
                'visible' => Yii::$app->user->can('admin'),
                'class' => 'yii\grid\ActionColumn', 'header' => 'Actions'
            ],
        ],
        'clientOptions' => [
            "lengthMenu"=> [[50,-1], [50,Yii::t('app',"All")]],
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
