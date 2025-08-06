<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\AuthAssignmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Auth Assignments';
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="container clearfix main_section" style="padding-top: 15px">
    <div id="main_content_outer" class="clearfix">
        <div id="main_content">
            <div class="row">
                <div class="col-sm-11">
                    <div class="col-md-12">
                        <h5 style="color: #003b4c;font-family: Tahoma">
                            <i class="fa fa-server text-default">
                            </i><strong>USERS  ROLES  LIST</strong></h5>
                    </div>
                    <div class="panel panel-default">
                        <div class="user-index">
                            <p style="padding-top: 40px;padding-left: 10px">
                                <?php if (Yii::$app->user->can("admin")) { ?>
                                    <?= Html::a('Assign Role', ['create'], ['class' => 'btn btn-success']) ?>
                                <?php } ?>
                            </p>
                            <div class="box-body table-responsive">
                                <?php \yii\widgets\Pjax::begin(); ?>
                                <?= \fedemotta\datatables\DataTables::widget([
                                    'dataProvider' => $dataProvider,
                                     'filterModel' => $searchModel,
                                    'summary'=>'',
                                    'columns' => [
                                        ['class' => 'yii\grid\SerialColumn'],

                                        'item_name',
                                        [
                                            'attribute' => 'user_id',
                                            'label' => 'Assigned User',
                                            'value' => function ($model){
                                                if($model->user != null){
                                                    return $model->user->username;
                                                }else{
                                                    return;
                                                }
                                            }

                                        ],
                                        // 'created_at',

                                        ['class' => 'yii\grid\ActionColumn',
                                            'header'=>'actions',
                                        ],
                                    ],
                                ]); ?>
                                <?php \yii\widgets\Pjax::end(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


