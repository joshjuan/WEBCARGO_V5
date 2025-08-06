<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;


/* @var $this yii\web\View */
/* @var $model backend\models\Admin */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <?php
    $form = ActiveForm::begin([
        'enableClientValidation' => true,
        /*'enableAjaxValidation' => true,
        'validateOnSubmit'=>true,
        'validateOnChange' => false*/
        'id' => 'login-form',
        'type' => ActiveForm::TYPE_VERTICAL,
        'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL]
    ]);
    ?>
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Yii::t('app', 'Role'); ?>
            </div>
            <div class="panel-body">

                <?php
                echo $form->field($model, 'name')->textInput($model->isNewRecord ? [] : ['disabled' => 'disabled']) .
                     $form->field($model, 'description')->textarea(['style' => 'height: 100px']) .
                     Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), [
                        'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'
                     ]);
                ?>
            </div>

        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Yii::t('app', 'Permissions'); ?>
            </div>

            <div class="panel-body">
                <?php $form->field($model, '_permissions')->checkboxList($permissions)->label('', ['hidden' => 'hidden']); ?>

                <?= $form->field($model, '_permissions')->checkboxList($permissions,[

                    'itemOptions' => [

                        'labelOptions' => ['class' => 'col-md-6']

                    ]

                ]) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
