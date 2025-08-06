<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AuthItemChildSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '';
$this->params['breadcrumbs'][] = 'Auth Item Children';
?>
<div class="row" style="padding-top: 10px">
    <div class="col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">List of Multiple System Roles</h4>
            </div>
            <div class="panel-body">

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    //'filterModel' => $searchModel,
                    'summary' => '',
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'parent',
                        'child',
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'header' => 'Action',
                            'visible' => yii::$app->user->can('Super_Administrator') || yii::$app->user->can('Administrator'),
                            'template' => '{update}',
                            'buttons' => [
                                'update' => function ($url, $model) {
                                    //  $url = ['update', 'id' => $model->id];
                                    return Html::a('<span class="fa fa-pencil"></span>', $url, [
                                        'title' => 'Update',
                                        'data-toggle' => 'tooltip', 'data-original-title' => 'Save',
                                        'class' => 'btn btn-primary',

                                    ]);

                                },


                            ]
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>


