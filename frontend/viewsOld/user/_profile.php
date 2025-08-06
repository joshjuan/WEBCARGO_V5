<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\models\User;

$this->title = '';
$this->params['breadcrumbs'][] = 'Users';
?>
<p style="padding-top: 30px"/>
<div class="employee-view">

    <div class="row">

        <div class="col-md-12">
            <?php $form = ActiveForm::begin(); ?>
            <div class="user-form">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?= Yii::t('app', 'Login Details'); ?>
                    </div>
                    <div class="panel-body">

                        <div class="col-sm-12 no-padding">
                            <div class="col-sm-6">
                                <?= $form->field($model, 'full_name')->textInput(['maxlength' => 255,]) ?>
                            </div>
                            <div class="col-sm-6">
                                <?= $form->field($model, 'username')->textInput(['maxlength' => 255,]) ?>
                            </div>
                        </div>
                        <div class="col-sm-12 no-padding">
                            <div class="col-sm-6">
                                <?= $form->field($model, 'password')->passwordInput(['maxlength' => 255]) ?>
                            </div>
                            <div class="col-sm-6">
                                <?= $form->field($model, 'repassword')->passwordInput(['maxlength' => 255]) ?>
                            </div>
                        </div>

                        <div class="col-sm-12 no-padding">
                            <div class="col-sm-12">
                                <?php $form->field($model, 'role')->dropDownList(User::getRules(), ['disabled' => 'disabled']) ?>
                            </div>
                            <div class="col-sm-12">
                                <?php $form->field($model, 'status')->dropDownList(User::getArrayStatus(), ['disabled' => 'disabled']) ?>
                            </div>
                        </div>

                        <div class="form-group col-xs-10 col-sm-6 col-lg-4 no-padding edusecArLangCss">
                            <div class="col-xs-6">
                                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Change Password'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                            </div>

                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>