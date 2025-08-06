<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\BorderPortUser */

$this->title = '';
$this->params['breadcrumbs'][] = ['label' => 'Border Port Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->name;
\yii\web\YiiAsset::register($this);
?>
<div class="border-port-user-view">
    <div class="row" style="padding-top: 3%">
        <div class="col-md-6">
            <strong class="lead" style="color: #01214d;font-family: Tahoma"> <i class="fa fa-map-marker"></i> ECTS
                Portal - USER ALLOCATED</strong>
        </div>

        <div class="col-md-6">

            <?php if (Yii::$app->user->can('admin') || Yii::$app->user->can('addUserAllocated')) { ?>
                <?= Html::a(Yii::t('app', '<i class="fa fa-user"></i>Add New'), ['create'], ['class' => 'btn btn-primary waves-effect waves-light']) ?>
            <?php } ?>
            <?= Html::a(Yii::t('app', '<i class="fa fa-th-list"></i> User Allocated List'), ['index'], ['class' => 'btn btn-primary waves-effect waves-light']) ?>

        </div>
    </div>
    <p style="padding-top: 3%"/>
    <div class="row">
        <div class="col-md-8">

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    //'id',
                    [
                        'attribute' => 'border_port',
                        'value' => $model->borderPort->name,
                    ],
                    [
                        'attribute' => 'name',
                        'value' => $model->userAssigned->username,
                    ],
                    'assigned_date',
                    [
                        'attribute' => 'assigned_by',
                        'value' => $model->userAssignedBy->username,
                    ],
                ],
            ]) ?>
        </div>

        <div class="col-md-4">
            <p>
                <?php if (Yii::$app->user->can('admin') || Yii::$app->user->can('addUserAllocated')) { ?>
                    <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?php } ?>
                <?= Html::a(Yii::t('app', 'Cancel'), ['index',], ['class' => 'btn btn-warning']) ?>
                <?php if (Yii::$app->user->can('admin')||Yii::$app->user->can('deleteUserAllocated')) { ?>
                <?php Html::a('Delete', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]) ?>
                <?php } ?>
            </p>


        </div>
    </div>
</div>