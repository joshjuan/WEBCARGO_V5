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
                <strong>USER ALLOCATED FORM</strong>
            </i>
        </h3>
    </center>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'border_port')->dropDownList(\frontend\models\BorderPort::getBordersPorts(), ['prompt' => 'Select Border ----']) ?>


    <?php
    if (\Yii::$app->user->identity->branch == 1){
     echo   $form->field($model, 'name')->dropDownList(\frontend\models\User::getBorderUser(), ['prompt' => 'Select user ----']) ;
    }
    else{
    echo    $form->field($model, 'name')->dropDownList(\frontend\models\User::getBorderPortUser(), ['prompt' => 'Select user ----']);
    }

    ?>

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
