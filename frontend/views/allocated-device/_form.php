<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AllocatedDevice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="allocated-device-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'serial_no')->textInput() ?>

    <?= $form->field($model, 'allocated_date')->textInput() ?>

    <?= $form->field($model, 'allocated_to')->textInput() ?>

    <?= $form->field($model, 'allocated_by')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
