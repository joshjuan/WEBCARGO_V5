<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\User;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '');
$this->params['breadcrumbs'][] = 'Users';
?>
<p style="padding-top: 5px"/>
<div class="user-index">

    <div class="row">
        <div class="col-md-6">
            <strong class="lead" style="color: #01214d;font-family: Tahoma"> <i class="fa fa-th-list text-blue"></i>
                SYSTEM USERS - Office Staff Users</strong>
        </div>
        <div class="col-md-3">

        </div>
        <div class="col-md-2">

            <?php if (Yii::$app->user->can('createUser')) { ?>
                <?= Html::a(Yii::t('app', '<i class="fa fa-user"></i> New User'), ['create'], ['class' => 'btn btn-primary waves-effect waves-light']) ?>
            <?php } ?>


        </div>
    </div>
    <p style="padding-top: 3%"/>
    <?= \fedemotta\datatables\DataTables::widget([
        'dataProvider' => $dataProvider,
          'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'full_name',
            'username',
         //   'mobile',
            'email',
            'role',
            [
                'attribute' => 'branch',
                'value' => 'branch0.name',
            ],
            [
                'attribute' => 'user_type',
                'value' => function ($model) {

                    if ($model->user_type == User::ADMIN) {
                        return 'ADMINISTRATOR';
                    } elseif ($model->user_type == User::OFFICE_STAFF) {
                        return 'OFFICE STAFF';
                    }elseif ($model->user_type == User::PORT_STAFF) {
                        return 'PORT STAFF';
                    }elseif ($model->user_type == User::BORDER_STAFF) {
                        return 'BORDER STAFF';
                    }
                }

            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {

                    if ($model->status == User::STATUS_ACTIVE) {
                        return 'Active';
                    } elseif ($model->status == User::STATUS_DELETED) {
                        return 'Disabled';
                    } elseif ($model->status == User::STATUS_INACTIVE) {
                        return 'Disabled';
                    }
                }

            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Actions',
                'template' => '{view}',
                //   'visible' => Yii::$app->user->can('super_admin'),
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
        ],
        'clientOptions' => [
            "lengthMenu"=> [[100,-1], [100,Yii::t('app',"All")]],
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
