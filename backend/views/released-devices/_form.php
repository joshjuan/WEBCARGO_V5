<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ReleasedDevices */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="released-devices-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'serial_no')->textInput() ?>

    <?= $form->field($model, 'released_date')->textInput() ?>

    <?= $form->field($model, 'released_by')->textInput() ?>

    <?= $form->field($model, 'released_to')->textInput() ?>

    <?= $form->field($model, 'transferred_from')->textInput() ?>

    <?= $form->field($model, 'transferred_to')->textInput() ?>

    <?= $form->field($model, 'transferred_date')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
