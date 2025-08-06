<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\BorderUser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="border-user-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'border')->dropDownList(\frontend\models\Border::getAllBorders(), ['prompt' => 'Select Border ----']) ?>


    <?= $form->field($model, 'user')->dropDownList(\frontend\models\User::getAllUser(), ['prompt' => 'Select user ----']) ?>


    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
