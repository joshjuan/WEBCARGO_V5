<?php

use frontend\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use dosamigos\datetimepicker\DateTimePicker;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>

    <div class="user-form" style="padding-top: 20px">
        <?php $form = ActiveForm::begin([
            'id' => 'branch-form',
            'options' => ['enctype' => 'multipart/form-data'],
        ]); ?>
        <div class="panel panel-info">
            <div class="panel-heading">
                <?= Yii::t('app', 'payment Information'); ?>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-4">
                        <?php //  $form->field($model, 'client')->dropDownList(\backend\models\Clients::getAll(), ['id'=>'rate_per_unit']);

                        ?>
                        <?= $form->field($model, 'customer_id')->widget(Select2::classname(), [
                            // 'data' => \backend\models\Customer::getAll(),
                            'data' =>User::getBillCustomer(),

                            'options' => ['placeholder' => 'Select Client', 'id' => 'client','required'=>true,],
                            'pluginOptions' => [
                                'allowClear' => true,

                            ],
                        ]);
                        ?>
                    </div>
                    <div class="col-sm-4">

                        <?= $form->field($model, 'created')->textInput(['readOnly'=>true]) ?>

                    </div>
                    <div class="col-sm-3">
                        <?= $form->field($model, 'file')->fileInput(['required'=>true]) ?>
                        <?= Html::a('Sample template(make sure you follow the format of each provided item)', 'uploads/devices/sample.csv', ['target' => '_blank', 'class' => 'box_button fl download_link']) ?>
                    </div>
                </div>
                <div class="form-group col-xs-12 col-sm-6 col-lg-4 no-padding ">
                    <div class="col-xs-6">
                        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success']) ?>
                        <?= Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-default']) ?>
                    </div>
                    <div class="col-xs-6">

                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>

