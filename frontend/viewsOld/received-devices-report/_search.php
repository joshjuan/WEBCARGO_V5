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
                    <div class="col-md-3">
                        <?= $form->field($model, 'serial_no')->textarea(['id' => 'serial', 'rows' => 10]) ?>
                    </div>
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
                           'data' => \backend\models\Location::getLocation(),
                            'options' => ['placeholder' => '-- select location  --'],
                            'pluginOptions' => [
                                'allowClear' => true,

                            ],
                        ]);
                        ?>

                    </div>
                    <div class="col-md-3">

                        <?= $form->field($model, 'received_by')->widget(Select2::classname(), [
                            'data' => \backend\models\User::getBorderPortUser(),
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
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
