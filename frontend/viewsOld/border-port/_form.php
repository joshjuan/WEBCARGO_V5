<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\BorderPort */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="border-port-form">
    <div class="panel panel-success">
        <div class="panel panel-heading">Border Port Form</div>
        <div class="panel panel-body">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder'=>'Enter name']) ?>
            <?php $form->field($model, 'location')->dropDownList(\frontend\models\Location::getAllLocation(), ['prompt' => 'Choose status --']) ?>


            <div class="row">
                <div class="form-group">
                    <div class="col-md-3 col-sm-3 col-xs-3 pull-right">
                        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Submit') : Yii::t('app', 'Submit'), ['class' => $model->isNewRecord ? 'btn btn-success btn-block' : 'btn btn-primary btn-block']) ?>
                    </div>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
