<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

use kartik\depdrop\DepDrop;


$catList = \backend\models\Location::getAllLocation();
/* @var $this yii\web\View */
/* @var $model backend\models\ReceivedDevices */
/* @var $form yii\widgets\ActiveForm */
?>
<p style="padding-top: 15px"/>
<center>
    <h3>
        <i class="fa fa-cogs text-default">
            <strong> RECEIVE DEVICE FORM</strong>
        </i>
    </h3>
</center>

<div class="received-devices-form" style="border-style: double;">
    <div class="col-sm-12 no-padding">
        <div class="col-sm-12" style="padding-top: 3%">
            <p>Total Numbers: <span id="total"></span></p>
            <p>Duplicate Numbers: <span id="duplicate"></span></p>
            <p>Valid Numbers: <span id="valid"></span></p>
        </div>
    </div>
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-sm-12 no-padding">
        <div class="col-sm-6" style="padding-top: 1%">
            <?= $form->field($model, 'serial_no')->textarea(['id' => 'serial', 'rows' => 10, 'placeholder' => 'Enter a devices serial number']) ?>
        </div>
        <div class="col-sm-6" style="padding-top: 1%">
            <?= $form->field($model, 'remark')->textarea(['rows' => 10]) ?>
        </div>
    </div>

    <div class="col-sm-12 no-padding">
        <div class="col-sm-6">
            <?= $form->field($model, 'received_from')->dropDownList($catList, ['id' => 'cat-id', 'prompt' => 'Select Received from']); ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'border_port')->widget(DepDrop::classname(), [
                'options' => ['id' => 'subcat-id'],
                'pluginOptions' => [
                    'depends' => ['cat-id'],
                    'placeholder' => 'Select...',
                    'url' => Url::to(['received-devices/border-port'])
                ]
            ]); ?>
        </div>
        <div class="col-sm-3">
            <?php  $form->field($model, 'received_from_staff')->widget(DepDrop::classname(), [
                'pluginOptions' => [
                    'depends' => ['cat-id', 'subcat-id'],
                    'placeholder' => 'Select...',
                    'url' => Url::to(['received-devices/user-location'])
                ]
            ]); ?>
        </div>
    </div>

    <div class="row" style="margin-bottom: 10px; padding-right: 2%">
        <div class="form-group">
            <div class="col-md-3 col-sm-3 col-xs-3 pull-right">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', '<i class="fa fa-arrow-right"></i> Next') : Yii::t('app', 'Next'), ['class' => $model->isNewRecord ? 'btn btn-success btn-block' : 'btn btn-primary btn-block']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

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