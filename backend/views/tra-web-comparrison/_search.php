<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\TraWebComparrisonSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="tra-web-comparrison-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'serial_no') ?>

    <?= $form->field($model, 'tzdl') ?>

    <?= $form->field($model, 'tra_count') ?>

    <?= $form->field($model, 'web_count') ?>

    <?php // echo $form->field($model, 'count_status') ?>

    <?php // echo $form->field($model, 'compared_by') ?>

    <?php // echo $form->field($model, 'datetime') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'route_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
