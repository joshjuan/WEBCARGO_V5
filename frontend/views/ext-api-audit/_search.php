<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\ExtApiAuditSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="ext-api-audit-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'request_to_backend') ?>

    <?= $form->field($model, 'request_data') ?>

    <?= $form->field($model, 'endpoint') ?>

    <?= $form->field($model, 'endpoint_type') ?>

    <?php // echo $form->field($model, 'status_code') ?>

    <?php // echo $form->field($model, 'response_data') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
