<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\DeviceCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Device Categories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="device-category-index">

    <p>
        <?= Html::a('New Category', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

         //   'id',
            'name',
            'bland',
            'created_at',
            'created_by',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}&nbsp;&nbsp;{view}',
                'header' => 'Actions',
                'buttons' => [
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="btn btn-sm btn-info"><b class="fa fa-edit"></b></span>',
                            ['update', 'id' => $model['id']],
                            ['title' => 'update', 'class' => '',
                                'data' => ['confirm' => 'Are you absolutely sure you want to update it', 'method' => 'post', 'data-pjax' => false],]);
                    },
                    'view' => function ($url, $model) {
                        return Html::a('<span class="btn btn-sm btn-info"><b class="fa fa-eye"></b></span>',
                            ['view', 'id' => $model['id']],
                            ['title' => 'View', 'class' => '']);
                    },

                ]
            ],
        ],
    ]); ?>


</div>
