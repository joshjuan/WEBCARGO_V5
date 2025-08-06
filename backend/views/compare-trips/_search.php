<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\CompareTripsSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="compare-trips-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'document_path') ?>

    <?= $form->field($model, 'document_name') ?>

    <?= $form->field($model, 'date_from') ?>

    <?= $form->field($model, 'date_to') ?>

    <?php // echo $form->field($model, 'total_activation') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'upload_by') ?>

    <?php // echo $form->field($model, 'upload_date') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
