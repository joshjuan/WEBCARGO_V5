<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\SalesTrips */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sales-trips-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'datetime')->textInput() ?>

    <?= $form->field($model, 'trip_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'start_date_time')->textInput() ?>

    <?= $form->field($model, 'vehicle_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'driver_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'border')->textInput() ?>

    <?= $form->field($model, 'trip_status')->textInput() ?>

    <?= $form->field($model, 'driver_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'serial_no')->textInput() ?>

    <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gate_number')->textInput() ?>

    <?= $form->field($model, 'stop_date_time')->textInput() ?>

    <?= $form->field($model, 'sales_person')->textInput() ?>

    <?= $form->field($model, 'receipt_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'passport')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'container_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payment_method')->textInput() ?>

    <?= $form->field($model, 'vehicle_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'customer_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'agent')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cancelled_by')->textInput() ?>

    <?= $form->field($model, 'edited_by')->textInput() ?>

    <?= $form->field($model, 'edited_at')->textInput() ?>

    <?= $form->field($model, 'date_cancelled')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
