<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\DeviceCategory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="device-category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bland')->textInput(['maxlength' => true]) ?>
    <div class="row">
        <div class="col-sm-2">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-block btn-primary' : 'btn btn-block btn-primary']) ?>
        </div>
        <div class="col-sm-2">
            <?= Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-primary btn-block']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
