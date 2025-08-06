<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AuditSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '');
$this->params['breadcrumbs'][] = 'System Audit Trails';
?>
<p style="padding-top: 15px"/>
<div class="audit-index">

    <?php // $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute' => 'activity',
                'width' => '400px',
                'contentOptions' => ['class' => 'truncate'],
            ],

            //'module',
            'action',
            //   'old',
            //  'new',
            'maker',
            //'maker_time',
            [
                'attribute' => 'maker_time',
                'value' => 'maker_time',
                'width' => '220px',
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => [
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'autoclose' => true,
                        'todayHighlight' => true,
                    ]
                ],
            ],

            //['class' => 'yii\grid\ActionColumn'],
        ],
        'pjax' => true,
        'toolbar' => [
            ['content' =>
            // Html::button('<i class="glyphicon glyphicon-plus"></i>', ['type' => 'button', 'title' => Yii::t('kvgrid', 'Add Book'), 'class' => 'btn btn-success', 'onclick' => 'alert("This will launch the book creation form.\n\nDisabled for this demo!");']) . ' '.
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => Yii::t('app', 'Reset Grid')])
            ],
            '{export}',
            '{toggleData}',
        ],
        // set export properties
        'export' => [
            'fontAwesome' => true
        ],
        'pjaxSettings' => [
            'neverTimeout' => true,


        ],
        'panel' => [
            'type' => GridView::TYPE_INFO,
            'heading' => '<i class="fa fa-check-square text-green"></i> ECTS Portal - SYSTEM AUDIT TRAILS',
            //'before'=>'<span class="text text-primary">Hii ni orodha ya malipo ya wazee ndani ya miezi mitatu:</span>',
        ],
        'persistResize' => false,
        'toggleDataOptions' => ['minCount' => 10],
        // 'exportConfig' => $gridColumns

    ]);
    ?>
    <style>
        .truncate {
            max-width: 150px !important;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

         .truncate:hover {
            overflow: visible;
            white-space: normal;
            width: auto;
        }
    </style>
</div>
