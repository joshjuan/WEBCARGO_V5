<?php

use backend\models\Branches;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\helpers\Url;
use kartik\depdrop\DepDrop;

/* @var $this yii\web\View */
/* @var $model frontend\models\Devices */
/* @var $form yii\widgets\ActiveForm */

$catList = \frontend\models\Location::getAllLocation();

?>

<div class="device-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="col-sm-12 no-padding">
        <div class="col-sm-4" style="padding-top: 1%">
            <?= $form->field($model, 'serial_no')->textarea([ 'id' => 'serial', 'rows' => 6, 'placeholder'=>'Put serial number']) ?>
        </div>
        <div class="col-sm-4" style="padding-top: 1%">
            <?= $form->field($model, 'branch')->dropDownList(
                Branches::getAllBranches(),           // Flat array ('id'=>'label')
                ['prompt'=>'Select branch','required'=>true]    // options
            ); ?>
            <?= $form->field($model, 'device_category')->dropDownList(
                \frontend\models\DeviceCategory::getAllCategory(),           // Flat array ('id'=>'label')
                ['prompt'=>'Select Device Category','required'=>true]    // options
            ); ?>
        </div>
        <div class="col-sm-4" style="padding-top: 1%">
            <?= $form->field($model, 'remark')->textarea(['rows' => 6,'placeholder'=>'Comment']) ?>
        </div>
        <div class="col-sm-12 no-padding">
            <div class="col-sm-4">
                <?php $form->field($model, 'received_from')->dropDownList(
                    $catList, ['id' => 'cat-id', 'prompt' => 'Select Received from','required'=>true]); ?>
            </div>
            <div class="col-sm-4">
                <?php $form->field($model, 'border_port')->widget(DepDrop::classname(), [
                    'options' => ['id' => 'subcat-id', 'required'=>true,],
                    'pluginOptions' => [
                        'depends' => ['cat-id'],
                        'placeholder' => 'Select...',
                        'url' => Url::to(['received-devices/border-port'])
                    ]
                ])->label('From'); ?>
            </div>
            <div class="col-sm-4">
                <?= $form->field($model, 'type')->dropDownList(
                    \frontend\models\DeviceTypes::getAll(),           // Flat array ('id'=>'label')
                    ['prompt'=>'Select type','required'=>true]    // options
                ); ?>

            </div>
        </div>

        <div class="row" style="margin-bottom: 10px; padding-right: 1%">
            <div class="form-group">
                <div class="col-md-3 col-sm-3 col-xs-3 pull-right">
                    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', '<i class="fa fa-arrow-right"></i> Submit') : Yii::t('app', 'Next'), ['class' => $model->isNewRecord ? 'btn btn-success btn-block' : 'btn btn-primary btn-block']) ?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
