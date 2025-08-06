<?php

use dosamigos\datepicker\DateRangePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DevicesReportsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="application-search">

    <?php $form = ActiveForm::begin([
        'action' => ['active'],
        'method' => 'get',

    ]);
    //  $model = new \backend\models\ReceivedDevicesSearch()

    ?>
    <div class="panel panel-warning" style="background: #EEE">
        <div class="panel panel-heading">
            <a data-toggle="collapse" href="#collapse1"> Data Search</a>
        </div>
        <div id="collapse1" class="panel-collapse collapse">
            <div class="panel panel-body" style="background: #EEE">
                <div class="row">
                    <div class="col-md-3">
                        <?= $form->field($model, 'serial_no')->textarea(['id' => 'serial', 'rows' => 10]) ?>
                    </div>
                    <div class="col-md-9 no-padding">
                        <div class="col-sm-12" style="padding-top: 3%">
                            <p>Total Numbers: <span id="total"></span></p>
                            <p>Duplicate Numbers: <span id="duplicate"></span></p>
                            <p>Valid Numbers: <span id="valid"></span></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">

                        <?= $form->field($model, 'border_port')->widget(Select2::classname(), [
                            'data' => \backend\models\BorderPort::getBordersPorts(),
                            'options' => ['placeholder' => '-- select border port --'],
                            'pluginOptions' => [
                                'allowClear' => true,

                            ],
                        ]);
                        ?>
                    </div>
                    <div class="col-md-3">

                        <?= $form->field($model, 'received_from')->widget(Select2::classname(), [
                            'data' => \backend\models\User::getBorderPortUser(),
                            'options' => ['placeholder' => '-- select received user --'],
                            'pluginOptions' => [
                                'allowClear' => true,

                            ],
                        ]);
                        ?>

                    </div>
                    <div class="col-md-3">

                        <?= $form->field($model, 'received_from_staff')->widget(Select2::classname(), [
                            'data' => \backend\models\User::getAllUser(),
                            'options' => ['placeholder' => '-- select received user --'],
                            'pluginOptions' => [
                                'allowClear' => true,

                            ],
                        ]);
                        ?>
                    </div>

                </div>
                <div class="form-group" style="float: right; padding-right: 8%">
                    <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
                    <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
