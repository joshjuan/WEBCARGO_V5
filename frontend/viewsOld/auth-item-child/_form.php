<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\AuthItemChild */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="row">
    <div class="box-body table-responsive">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">New Multiple Role Form</h4>
                </div>
                <div class="panel-body">
                    <?php $form = ActiveForm::begin(); ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">

                                        <?= $form->field($model, 'parent')->dropDownList(\frontend\models\AuthItemChild::getParent(),['prompt' => '-- Select User Name --']) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="form-group">

                                            <?= $form->field($model, 'child')->dropDownList(\frontend\models\AuthItemChild::getChild(),['prompt' => '-- Select Role name --']) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-6">
                            <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>

                            <?= Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-warning']) ?>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

