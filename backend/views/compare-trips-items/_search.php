<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\CompareTripsItemsSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="compare-trips-items-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'tzdl') ?>

    <?= $form->field($model, 'serial_no') ?>

    <?= $form->field($model, 'route') ?>

    <?= $form->field($model, 'vehicle_no') ?>

    <?php // echo $form->field($model, 'departure') ?>

    <?php // echo $form->field($model, 'destination') ?>

    <?php // echo $form->field($model, 'vendor') ?>

    <?php // echo $form->field($model, 'cargo_type') ?>

    <?php // echo $form->field($model, 'activation_date') ?>

    <?php // echo $form->field($model, 'activated_by') ?>

    <?php // echo $form->field($model, 'deactivated_by') ?>

    <?php // echo $form->field($model, 'deactivate_date') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
