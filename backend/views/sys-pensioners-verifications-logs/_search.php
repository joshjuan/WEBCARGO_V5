<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\SysPensionersVerificationsLogsSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="sys-pensioners-verifications-logs-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'date_time') ?>

    <?= $form->field($model, 'uid') ?>

    <?= $form->field($model, 'pension_number') ?>

    <?= $form->field($model, 'full_names') ?>

    <?php // echo $form->field($model, 'verification_code') ?>

    <?php // echo $form->field($model, 'code_expiry_date') ?>

    <?php // echo $form->field($model, 'sent_date_time') ?>

    <?php // echo $form->field($model, 'batch_code') ?>

    <?php // echo $form->field($model, 'kin_mobile_number') ?>

    <?php // echo $form->field($model, 'verified_by_uid') ?>

    <?php // echo $form->field($model, 'verified_date_time') ?>

    <?php // echo $form->field($model, 'verification_status') ?>

    <?php // echo $form->field($model, 'checker_remarks') ?>

    <?php // echo $form->field($model, 'date_renewed') ?>

    <?php // echo $form->field($model, 'start_date') ?>

    <?php // echo $form->field($model, 'expiry_date') ?>

    <?php // echo $form->field($model, 'verified_location') ?>

    <?php // echo $form->field($model, 'verified_by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
