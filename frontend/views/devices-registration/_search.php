<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\DevicesRegistrationSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="devices-registration-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'serial_no') ?>

    <?= $form->field($model, 'sim_card') ?>

    <?= $form->field($model, 'received_from') ?>

    <?= $form->field($model, 'border_port') ?>

    <?php // echo $form->field($model, 'received_from_staff') ?>

    <?php // echo $form->field($model, 'received_at') ?>

    <?php // echo $form->field($model, 'received_status') ?>

    <?php // echo $form->field($model, 'received_by') ?>

    <?php // echo $form->field($model, 'remark') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'device_from') ?>

    <?php // echo $form->field($model, 'stock_status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'branch') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'device_category') ?>

    <?php // echo $form->field($model, 'released_by') ?>

    <?php // echo $form->field($model, 'released_to') ?>

    <?php // echo $form->field($model, 'transferred_from') ?>

    <?php // echo $form->field($model, 'transferred_to') ?>

    <?php // echo $form->field($model, 'transferred_date') ?>

    <?php // echo $form->field($model, 'transferred_by') ?>

    <?php // echo $form->field($model, 'released_date') ?>

    <?php // echo $form->field($model, 'sales_person') ?>

    <?php // echo $form->field($model, 'tzl') ?>

    <?php // echo $form->field($model, 'vehicle_no') ?>

    <?php // echo $form->field($model, 'container_number') ?>

    <?php // echo $form->field($model, 'sale_id') ?>

    <?php // echo $form->field($model, 'view_status') ?>

    <?php // echo $form->field($model, 'partiner') ?>

    <?php // echo $form->field($model, 'registration_date') ?>

    <?php // echo $form->field($model, 'registration_by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
