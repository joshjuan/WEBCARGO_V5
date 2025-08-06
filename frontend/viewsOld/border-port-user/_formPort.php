<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\BorderPortUser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="border-port-user-form">
    <p style="padding-top: 40px"/>
    <center>
        <h3>
            <i class="fa fa-user-circle text-default">
                <strong>USER ALLOCATE FORM</strong>
            </i>
        </h3>
    </center>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'border_port')->dropDownList(\frontend\models\BorderPort::getSalesPoints(), ['prompt' => 'Select Sales Point ----'])->label('Sales Point',['class'=>'label-class']) ?>

  <?=   $form->field($model, 'name')->dropDownList(\frontend\models\User::getPortUser(), ['prompt' => 'Select user ----']);

    ?>

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
