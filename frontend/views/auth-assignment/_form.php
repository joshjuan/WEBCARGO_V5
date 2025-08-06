<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AuthAssignment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="col-xs-12 col-lg-12" style="padding-top: 25px">
    <div class="<?php echo $model->isNewRecord ? 'box-success' : 'box-info'; ?> box view-item col-xs-12 col-lg-12">
        <div class="auth-assignment-form">

            <?php $form = ActiveForm::begin([
                'id' => 'branch-form',
                'options' => ['enctype' => 'multipart/form-data'],

            ]); ?>
            <h2 class="page-header">
                <i class="fa fa-paper-plane"></i> <?php echo Yii::t('app', 'Auth assignment'); ?>
            </h2>
            <div class="col-sm-12 no-padding">
                <div class="col-sm-6">
                    <?= $form->field($model, 'item_name')->dropDownList(\backend\models\AuthItem::getAll(),['prompt'=>'-- Select Item --']) ?>
                </div>
            </div>
            <div class="col-sm-12 no-padding">
                <div class="col-sm-6">
                    <?= $form->field($model, 'user_id')->dropDownList(\backend\models\User::getAll(),['prompt'=>'-- Select user --']) ?>
                </div>
            </div>

            <div class="form-group col-xs-12 col-sm-6 col-lg-4 no-padding edusecArLangCss">
                <div class="col-xs-6">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-block btn-success']) ?>
                </div>
                <div class="col-xs-6">
                    <?= Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-default btn-block']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
