<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Branches */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="branches-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'branch_type')->dropDownList(
        ['1'=>'OPERATIONAL','0'=>'NON OPERATIONAL'],           // Flat array ('id'=>'label')
        ['prompt'=>'Select Operation Status']    // options
    ); ?>
    <?= $form->field($model, 'status')->dropDownList(
        ['1'=>'ACTIVATE','0'=>'INACTIVE'],           // Flat array ('id'=>'label')
        ['prompt'=>'Select Active/Inactive']    // options
    ); ?>

    <?= $form->field($model, 'created_at')->textInput(['readOnly'=>true]) ?>

    <?= $form->field($model, 'created_by')->textInput(['maxlength' => true,'readOnly'=>true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
