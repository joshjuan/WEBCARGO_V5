<?php

use backend\models\CompareTrips;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\CompareTripsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Compare Trips';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="compare-trips-index">


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'id',
          //  'document_path',
            'document_name',
            'date_from',
            'date_to',
            'total_activation',
            'status',
            'upload_by',
            'upload_date',
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
    ]); ?>


</div>
