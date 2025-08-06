<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Location */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="location-form">

    <div class="panel panel-primary">
        <div class="panel panel-heading">Location Form</div>
        <div class="panel panel-body">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'location_name')->textInput(['maxlength' => true,'placeholder'=>'Enter Location ']) ?>


            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>


