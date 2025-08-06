<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\DevicesReports $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="devices-reports-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'serial_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sim_card')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'received_from')->textInput() ?>

    <?= $form->field($model, 'received_to')->textInput() ?>

    <?= $form->field($model, 'border_port')->textInput() ?>

    <?= $form->field($model, 'received_from_staff')->textInput() ?>

    <?= $form->field($model, 'received_at')->textInput() ?>

    <?= $form->field($model, 'received_status')->textInput() ?>

    <?= $form->field($model, 'received_by')->textInput() ?>

    <?= $form->field($model, 'remark')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'branch')->textInput() ?>

    <?= $form->field($model, 'type')->textInput() ?>

    <?= $form->field($model, 'device_category')->textInput() ?>

    <?= $form->field($model, 'released_by')->textInput() ?>

    <?= $form->field($model, 'released_to')->textInput() ?>

    <?= $form->field($model, 'transferred_from')->textInput() ?>

    <?= $form->field($model, 'transferred_to')->textInput() ?>

    <?= $form->field($model, 'transferred_date')->textInput() ?>

    <?= $form->field($model, 'transferred_by')->textInput() ?>

    <?= $form->field($model, 'released_date')->textInput() ?>

    <?= $form->field($model, 'sales_person')->textInput() ?>

    <?= $form->field($model, 'tzl')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vehicle_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'container_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sale_id')->textInput() ?>

    <?= $form->field($model, 'view_status')->textInput() ?>

    <?= $form->field($model, 'registration_date')->textInput() ?>

    <?= $form->field($model, 'registration_by')->textInput() ?>

    <?= $form->field($model, 'movement_unique_id')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
