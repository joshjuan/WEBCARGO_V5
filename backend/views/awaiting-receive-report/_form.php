<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ReceivedDevicesReport */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="received-devices-report-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'serial_no')->textInput() ?>

    <?= $form->field($model, 'received_from')->textInput() ?>

    <?= $form->field($model, 'border_port')->textInput() ?>

    <?= $form->field($model, 'received_from_staff')->textInput() ?>

    <?= $form->field($model, 'received_at')->textInput() ?>

    <?= $form->field($model, 'received_status')->textInput() ?>

    <?= $form->field($model, 'remark')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'received_by')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
