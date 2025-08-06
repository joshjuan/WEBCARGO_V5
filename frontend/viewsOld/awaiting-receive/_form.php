<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

use kartik\depdrop\DepDrop;


$catList = \frontend\models\Location::getAllLocation();
/* @var $this yii\web\View */
/* @var $model frontend\models\ReceivedDevices */
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
        <div class="col-sm-3">
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
            <?= $form->field($model, 'received_from_staff')->widget(DepDrop::classname(), [
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
