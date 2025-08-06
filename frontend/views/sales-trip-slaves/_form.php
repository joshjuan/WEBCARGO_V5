<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\SalesTripSlaves */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sales-trip-slaves-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'sale_id')->textInput() ?>

    <?= $form->field($model, 'serial_no')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
