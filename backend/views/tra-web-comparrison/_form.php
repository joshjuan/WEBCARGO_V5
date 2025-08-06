<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\TraWebComparrison $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="tra-web-comparrison-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'serial_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tzdl')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'tra_count')->textInput() ?>

    <?= $form->field($model, 'web_count')->textInput() ?>

    <?= $form->field($model, 'count_status')->textInput() ?>

    <?= $form->field($model, 'compared_by')->textInput() ?>

    <?= $form->field($model, 'datetime')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'route_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
