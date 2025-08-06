<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\BorderPort */

$this->title = '';
$this->params['breadcrumbs'][] = ['label' => 'Border Ports', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Create Border Port';
?>
<div class="border-port-create" style="padding-top: 3%">

    <div class="row">
        <div class="col-md-6">
            <strong class="lead"  style="color: #01214d;font-family: Tahoma"> <i class="fa fa-check-square text-green"></i> ECTS Portal - BORDER PORT</strong>
        </div>

        <div class="col-md-6">

            <?= Html::a(Yii::t('app', '<i class="fa fa-map-marker"></i> Add New'), ['create'], ['class' => 'btn btn-primary waves-effect waves-light']) ?>
            <?= Html::a(Yii::t('app', '<i class="fa fa-th-list"></i> BORDER PORT LIST'), ['index'], ['class' => 'btn btn-primary waves-effect waves-light']) ?>

        </div>
    </div>
<p style="padding-top: 1%"/>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
