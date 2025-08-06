<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\SysPensionersVerificationsLogs $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="sys-pensioners-verifications-logs-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'date_time')->textInput() ?>

    <?= $form->field($model, 'uid')->textInput() ?>

    <?= $form->field($model, 'pension_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'full_names')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'verification_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'code_expiry_date')->textInput() ?>

    <?= $form->field($model, 'sent_date_time')->textInput() ?>

    <?= $form->field($model, 'batch_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kin_mobile_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'verified_by_uid')->textInput() ?>

    <?= $form->field($model, 'verified_date_time')->textInput() ?>

    <?= $form->field($model, 'verification_status')->textInput() ?>

    <?= $form->field($model, 'checker_remarks')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'date_renewed')->textInput() ?>

    <?= $form->field($model, 'start_date')->textInput() ?>

    <?= $form->field($model, 'expiry_date')->textInput() ?>

    <?= $form->field($model, 'verified_location')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'verified_by')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
