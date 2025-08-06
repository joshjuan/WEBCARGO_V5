<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\CompareTripsItems $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="compare-trips-items-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tzdl')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'serial_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'route')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vehicle_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'departure')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'destination')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vendor')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cargo_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'activation_date')->textInput() ?>

    <?= $form->field($model, 'activated_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deactivated_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deactivate_date')->textInput() ?>

    <?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
