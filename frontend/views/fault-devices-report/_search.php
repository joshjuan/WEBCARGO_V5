<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\FaultDevicesReportSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="fault-devices-report-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',

    ]);
    //  $model = new \backend\models\ReceivedDevicesSearch()

    ?>
    <div class="panel panel-danger" style="background: #EEE">
        <div class="panel panel-heading">
            <a data-toggle="collapse" href="#collapse1"> Data Search</a>
        </div>
        <div id="collapse1" class="panel-collapse collapse">
            <div class="panel panel-body" style="background: #EEE">
                <div class="row">
                    <div class="col-md-4">
                        <?= $form->field($model, 'serial_no')->textarea(['id'=>'serial','rows'=>10,'placeholder'=>'Search serial number']) ?>
                    </div>
                    <div class="col-md-8">
                        <?= $form->field($model, 'created_by')->dropDownList(\backend\models\User::getAllUser(), ['prompt' => 'Select user ----']) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'date_from')->widget(
                            DatePicker::className(), [
                            // inline too, not bad
                            'inline' => false,
                            // modify template for custom rendering
                            //'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
                            'clientOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd',

                            ],
                            'options'=>['placeholder'=>'Date From']
                        ])->label(false);?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'date_to')->widget(
                            DatePicker::className(), [
                            // inline too, not bad
                            'inline' => false,
                            // modify template for custom rendering
                            //'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
                            'clientOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd',

                            ],
                            'options'=>['placeholder'=>'Date To']
                        ])->label(false);?>
                    </div>
                </div>

                <div class="form-group" style="float: right">
                    <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
                    <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>