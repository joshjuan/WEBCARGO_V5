<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\InTransitSlaveReport */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="in-transit-slave-report-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'serial_no')->textInput() ?>

    <?= $form->field($model, 'intansit_id')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'branch')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
