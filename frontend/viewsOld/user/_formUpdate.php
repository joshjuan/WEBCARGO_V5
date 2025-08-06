<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\User */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin(); ?>

<div class="user-form" style="padding-top: 2%">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <?= Yii::t('app', 'System User (Office Staff User) Form'); ?>
        </div>
        <div class="panel-body">
            <div class="box-body">

                <div class="col-xs-12 col-lg-12 col-sm-12">
                    <div class="col-sm-12 no-padding">
                        <div class="col-sm-6">
                            <?= $form->field($model, 'full_name')->textInput(['maxlength' => true,  'placeholder'=>'Full name']) ?>
                        </div>
                        <div class="col-sm-6">
                            <?= $form->field($model, 'username')->textInput(['maxlength' => true,  'placeholder'=>'Username']) ?>

                        </div>
                    </div>
                    <div class="col-sm-12 no-padding">
                        <div class="col-sm-6">

                            <?= $form->field($model, 'password')->passwordInput(['maxlength' => true, 'placeholder'=>'Password']) ?>
                        </div>
                        <div class="col-sm-6">
                            <?= $form->field($model, 'repassword')->passwordInput(['maxlength' => true, 'placeholder'=>'Re-password']) ?>
                        </div>
                    </div>
                    <div class="col-sm-12 no-padding">
                        <div class="col-sm-4">

                            <?= $form->field($model, 'mobile')->textInput(['maxlength' => true,'placeholder'=>'Mobile number']) ?>
                        </div>
                        <div class="col-sm-4">
                            <?= $form->field($model, 'email')->textInput(['maxlength' => true,  'placeholder'=>'Email']) ?>
                        </div>
                        <div class="col-sm-4">
                            <?= $form->field($model, 'branch')->dropDownList(\backend\models\Branches::getAllBranches(), ['prompt' => '-- select All Branches name --']) ?>
                        </div>
                    </div>

                    <div class="col-sm-12 no-padding">

                        <div class="col-sm-4">
                            <?= $form->field($model, 'role')->dropDownList(\frontend\models\User::getRules(), ['prompt' => '-- select role name --']) ?>
                        </div>
                        <div class="col-sm-4">
                            <?= $form->field($model, 'status')->dropDownList(\frontend\models\User::getArrayStatus()) ?>
                        </div>
                        <div class="col-sm-4">
                            <?= $form->field($model, 'user_type')->dropDownList(\frontend\models\User::getUserType(),['prompt' => '-- select user type --']) ?>
                        </div>
                    </div>


                    <div class="form-group col-xs-12 col-sm-6 col-lg-4">
                        <div class="col-xs-6 col-xs-12">
                            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-block btn-success' : 'btn btn-block btn-info']) ?>
                        </div>
                        <div class="col-xs-6 col-xs-12">
                            <?= Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-warning btn-block']) ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div><!---./end box-body--->
        </div><!---./end box--->
    </div>
</div>