<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Rfidcard */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rfidcard-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'card_no')->textInput() ?>

    <?= $form->field($model, 'assigned_to')->textInput() ?>

    <?= $form->field($model, 'assigned_by')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
