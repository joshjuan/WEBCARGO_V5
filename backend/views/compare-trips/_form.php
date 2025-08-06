<?php

use kartik\date\DatePicker;
use yii\helpers\Html;
use kartik\form\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\CompareTrips $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="compare-trips-form">

    <?php $form = ActiveForm::begin([
        'id' => 'devices',
        'options' => ['enctype' => 'multipart/form-data'],
    ]); ?>

    <?= $form->field($model, 'file')->fileInput() ?>

    <?= Html::a('Sample template(make sure you follow the format of each provided item)', '../../uploads/devices/UPLOAD-201908031456.csv', ['target' => '_blank', 'class' => 'box_button fl download_link']) ?>
<br/>
    <?php
    echo '<label class="form-label">Start Date</label>';

    echo DatePicker::widget([
        'model' => $model,
        'attribute' => 'date_from',
        'type' => DatePicker::TYPE_COMPONENT_PREPEND,
        'options' => ['placeholder' => 'Enter Start date ...'],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
    ]);
    ?>
<br/>
    <?php
    echo '<label class="form-label">End Date</label>';
    echo DatePicker::widget([
        'model' => $model,
        'attribute' => 'date_to',
        'type' => DatePicker::TYPE_COMPONENT_PREPEND,
        'options' => ['placeholder' => 'Enter end date ...'],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
    ]);

    ?>
<br/>
    <?= $form->field($model, 'upload_date')->textInput(['readOnly'=>true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Upload Doc', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
