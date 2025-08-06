<?php

use dosamigos\datepicker\DateRangePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\ApplicationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="application-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',

    ]);
    //  $model = new \backend\models\ReceivedDevicesSearch()

    ?>
    <div class="panel panel-success" style="background: #EEE">
        <div class="panel panel-heading">
            <a data-toggle="collapse" href="#collapse1"> Data Search</a>
        </div>
        <div id="collapse1" class="panel-collapse collapse">
            <div class="panel panel-body" style="background: #EEE">
                <div class="row">
                    <div class="col-md-2">
                        <?= $form->field($model, 'serial_no')->textarea(['id' => 'serial', 'rows' => 8, 'placeholder' => 'Search serial number ']) ?>
                    </div>

                    <div class="col-md-5">
                        <?= $form->field($model, 'border_port')->dropDownList(\backend\models\BorderPort::getBordersPorts(), ['prompt' => 'select Border ----']) ?>

                    </div>
                    <div class="col-md-5">
                        <?= $form->field($model, 'received_from')->dropDownList(\backend\models\User::getAllUser(), ['prompt' => 'Select user ----']) ?>
                    </div>
                    <div class="col-md-5">
                        <?= $form->field($model, 'received_from_staff')->dropDownList(\backend\models\User::getAllUser(), ['prompt' => 'Select user ----']) ?>
                    </div>
                    <div class="col-md-5">
                        <?= $form->field($model, 'received_by')->dropDownList(\backend\models\User::getAllUser(), ['prompt' => 'Select user ----']) ?>

                    </div>

                    <div class="col-md-5">
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
                    <div class="col-md-5">
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function () {
        validateNumbers();
        $('#serial').keyup(function () {
            if (/\D/g.test(this.value)) {
                this.value = this.value.replace(/\D/g, '');
            }
            this.value = this.value
                .replace(/[\n\r]+/g, "")
                .replace(/(.{10})/g, "$1\n");
            validateNumbers();
        });

        function validateNumbers() {
            var value = $("#serial").val();
            var numbersArray = value.split('\n');
            var validNumbers = [];
            var duplicateNumbers = [];
            var inValidNumbers = [];

            // remove empty elements
            var index = numbersArray.indexOf("");
            while (index !== -1) {
                numbersArray.splice(index, 1);
                index = numbersArray.indexOf("");
            }

            for (var $i = 0; $i < numbersArray.length; $i++) {
                var number = numbersArray[$i];
                if (validNumbers.indexOf(number) !== -1 || inValidNumbers.indexOf(number) !== -1) {
                    duplicateNumbers.push(number);
                } else if (number.match(/\d{10}/)) {
                    validNumbers.push(number);
                } else {
                    inValidNumbers.push(number);
                }
            }
            $("#total").text(numbersArray.length);
            $("#duplicate").text(duplicateNumbers.length);
            $("#valid").text(validNumbers.length);
            $("#invalid").text(inValidNumbers.length);
        }
    });
</script>
