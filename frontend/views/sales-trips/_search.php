<?php

use dosamigos\datepicker\DatePicker;
use dosamigos\datepicker\DateRangePicker;

use kartik\datetime\DateTimePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\SalesTripsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

    <div class="application-search">

<?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',

]);
//  $model = new \backend\models\ReceivedDevicesSearch()

?>
<?php
$branch = \frontend\models\Branches::find()
    ->where(['id' => Yii::$app->user->identity->branch])
    ->one();


if ($branch->branch_type == 1) {

    ?>
    <div class="panel panel-info" style="background: #EEE">
        <div class="panel panel-heading">
            <a data-toggle="collapse" href="#collapse1"> Data Search</a>
        </div>
        <div id="collapse1" class="panel-collapse collapse">
            <div class="panel panel-body" style="background: #EEE">
                <div class="row">
                    <div class="col-md-4">
                        <?= $form->field($model, 'serial_no')->textarea(['id' => 'serial', 'rows' => 10, 'placeholder' => 'Search serial number']) ?>
                        <?=
                        $form->field($model, 'branch')->widget(Select2::classname(), [
                            'data' => \backend\models\Branches::getAll(),
                            'options' => ['placeholder' => 'Select Branch '],
                            'pluginOptions' => [
                                'allowClear' => true,

                            ],
                        ]);
                        ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'tzl')->textInput() ?>
                        <?php
                        echo DateTimePicker::widget([
                            'model' => $model,
                            'attribute' => 'date_from',
                            'options' => ['placeholder' => 'Select Start Time ...'],
                            'convertFormat' => true,
                            'pluginOptions' => [
                                'format' => 'yyyy-MM-dd HH:i:ss',
                                // 'startDate' => '01-Mar-2014 12:00 AM',
                                'todayHighlight' => true
                            ]
                        ]);
                        ?>

                        <br>
                        <?=
                        $form->field($model, 'sales_person')->widget(Select2::classname(), [
                            'data' => \backend\models\User::getPortUser(),
                            'options' => ['placeholder' => 'Select Sales Person '],
                            'pluginOptions' => [
                                'allowClear' => true,

                            ],
                        ]);
                        ?>
                        <?=
                        $form->field($model, 'customer_id')->widget(Select2::classname(), [
                            'data' => \backend\models\User::getBillCustomer(),
                            'options' => ['placeholder' => 'Select Sales Person '],
                            'pluginOptions' => [
                                'allowClear' => true,

                            ],
                        ]);
                        ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'vehicle_number')->textInput() ?>
                        <?php
                        echo DateTimePicker::widget([
                            'model' => $model,
                            'attribute' => 'date_to',
                            'options' => ['placeholder' => 'Select End Time ...'],
                            'convertFormat' => true,
                            'pluginOptions' => [
                                'format' => 'yyyy-MM-dd HH:i:ss',
                                // 'startDate' => '01-Mar-2014 12:00 AM',
                                'todayHighlight' => true
                            ]
                        ]);
                        ?>

                        <br>

                        <?=
                        $form->field($model, 'customer_type_id')->widget(Select2::classname(), [
                            'data' => \backend\models\SalesTrips::getArrayStatus(),
                            'options' => ['placeholder' => 'Select Payment'],
                            'pluginOptions' => [
                                'allowClear' => true,

                            ],
                        ]);
                        ?>
                        <?=
                        $form->field($model, 'trip_status')->widget(Select2::classname(), [
                            'data' => \backend\models\SalesTrips::getTripStatus(),
                            'options' => ['placeholder' => 'Select Status'],
                            'pluginOptions' => [
                                'allowClear' => true,

                            ],
                        ]);
                        ?>
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
<?php } else { ?>

    <div class="panel panel-info" style="background: #EEE">
        <div class="panel panel-heading">
            <a data-toggle="collapse" href="#collapse1"> Data Search</a>
        </div>
        <div id="collapse1" class="panel-collapse collapse">
            <div class="panel panel-body" style="background: #EEE">
                <div class="row">
                    <div class="col-md-3">
                        <?= $form->field($model, 'serial_no')->textarea(['id' => 'serial', 'rows' => 10, 'placeholder' => 'Search serial number']) ?>
                    </div>
                    <div class="col-md-3">
                        <?php
                        echo DateTimePicker::widget([
                            'model' => $model,
                            'attribute' => 'date_from',
                            'options' => ['placeholder' => 'Select Start Time ...'],
                            'convertFormat' => true,
                            'pluginOptions' => [
                                'format' => 'yyyy-MM-dd HH:i:ss',
                                // 'startDate' => '01-Mar-2014 12:00 AM',
                                'todayHighlight' => true
                            ]
                        ]);
                        ?>

                        <br>

                    </div>
                    <div class="col-md-3">
                        <?php
                        echo DateTimePicker::widget([
                            'model' => $model,
                            'attribute' => 'date_to',
                            'options' => ['placeholder' => 'Select End Time ...'],
                            'convertFormat' => true,
                            'pluginOptions' => [
                                'format' => 'yyyy-MM-dd HH:i:ss',
                                // 'startDate' => '01-Mar-2014 12:00 AM',
                                'todayHighlight' => true
                            ]
                        ]);
                        ?>
                    </div>

                </div>
                <div class="form-group" style="float: right">
                    <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
<?php } ?>