<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DateRangePicker;

/* @var $this yii\web\View */
/* @var $model frontend\models\BanafitsAllSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="all-expenses-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]);

    $model = new \frontend\models\BenefitsAllSearch()
    ?>
    <div class="row">
        <div class="col-sm-4">
            <?= $form->field($model, 'date_from')->widget(DateRangePicker::className(), [
                'attributeTo' => 'date_to',
                'form' => $form, // best for correct client validation
                'language' => 'en',
                'size' => 'sm',

                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ]); ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
