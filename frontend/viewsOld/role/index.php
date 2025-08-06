<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\icons\Icon;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\AdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', ' ');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-index" style="padding-top: 20px">

    <center>
        <h3>
            <i class="fa fa-th-large text-default">
                <strong> ROLES LIST </strong>
            </i>
        </h3>
    </center>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'name',
                ],
                'description',
                [

                    'class' => 'yii\grid\ActionColumn','header'=>'Actions',
                    'visible'=>Yii::$app->user->can('Admin'),
                    'urlCreator' => function ($action, $model, $key, $index) {
                            $link = '#';
                            switch ($action) {
                                case 'view':
                                    $link = Yii::$app->getUrlManager()->createUrl(['role/view', 'name' => $model->name]);
                                    break;
                                case 'update':
                                    $link = Yii::$app->getUrlManager()->createUrl(['role/update', 'name' => $model->name]);
                                    break;
                                case 'delete':
                                    $link = Yii::$app->getUrlManager()->createUrl(['role/delete', 'name' => $model->name]);
                                    break;
                            }
                            return $link;
                        },

                ],

        ],
    ]); ?>

</div>
