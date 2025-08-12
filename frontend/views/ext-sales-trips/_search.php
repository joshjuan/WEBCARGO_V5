<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\ExtSalesTripsSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="ext-sales-trips-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'customer') ?>

    <?= $form->field($model, 'master') ?>

    <?= $form->field($model, 'slaves') ?>

    <?= $form->field($model, 'gate_id') ?>

    <?php // echo $form->field($model, 'border_id') ?>

    <?php // echo $form->field($model, 'trip_no') ?>

    <?php // echo $form->field($model, 'vehicle_no') ?>

    <?php // echo $form->field($model, 'trailer_no') ?>

    <?php // echo $form->field($model, 'merchant_no') ?>

    <?php // echo $form->field($model, 'receipt_no') ?>

    <?php // echo $form->field($model, 'agent') ?>

    <?php // echo $form->field($model, 'driver') ?>

    <?php // echo $form->field($model, 'cargo_type_id') ?>

    <?php // echo $form->field($model, 'cargo_no') ?>

    <?php // echo $form->field($model, 'chassis_no') ?>

    <?php // echo $form->field($model, 'vehicle_type') ?>

    <?php // echo $form->field($model, 'container_no') ?>

    <?php // echo $form->field($model, 'device_price') ?>

    <?php // echo $form->field($model, 'trip_status') ?>

    <?php // echo $form->field($model, 'start_date') ?>

    <?php // echo $form->field($model, 'end_date') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'branch') ?>

    <?php // echo $form->field($model, 'cancelled_at') ?>

    <?php // echo $form->field($model, 'cancelled_by') ?>

    <?php // echo $form->field($model, 'editted_at') ?>

    <?php // echo $form->field($model, 'editted_by') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
