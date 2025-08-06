<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Location */

$this->title = '';
$this->params['breadcrumbs'][] = ['label' => 'Locations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="location-view">

    <div class="row" style="padding-top: 3%">
        <div class="col-md-6">
            <strong class="lead"  style="color: #01214d;font-family: Tahoma"> <i class="fa fa-map-marker"></i> ECTS Portal - LOCATIONS</strong>
        </div>

        <div class="col-md-6">
            <?php if (Yii::$app->user->can('admin')||Yii::$app->user->can('addLocation')) { ?>
                <?= Html::a(Yii::t('app', '<i class="fa fa-map-marker"></i> New Location'), ['create'], ['class' => 'btn btn-primary waves-effect waves-light']) ?>
            <?php } ?>
            <?= Html::a(Yii::t('app', '<i class="fa fa-th-list"></i> Location List'), ['index'], ['class' => 'btn btn-primary waves-effect waves-light']) ?>
        </div>
    </div>
    <p style="padding-top: 3%"/>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
           // 'id',
            'location_name',
        ],
    ]) ?>

</div>
