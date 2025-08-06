<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\BorderPortUser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="border-port-user-form">
    <p style="padding-top: 40px"/>
    <center>
        <h3>
            <i class="fa fa-user-circle text-default">
                <strong>USER ALLOCATED FORM</strong>
            </i>
        </h3>
    </center>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'border_port')->dropDownList(\backend\models\BorderPort::getBordersPorts(), ['prompt' => 'Select Border ----']) ?>


    <?php
    echo $form->field($model, 'name')->dropDownList(\backend\models\User::getBorderUserBranch(), ['prompt' => 'Select user ----']);

    ?>

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
