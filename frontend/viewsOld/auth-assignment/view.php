<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\AuthAssignment */

$this->title = $model->item_name;
$this->params['breadcrumbs'][] = ['label' => 'Auth Assignments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-xs-12">
    <div class="col-lg-8 col-sm-8 col-xs-12 no-padding edusecArLangCss"><h3 class="box-title"><i class="fa fa-search"></i>
            <?php echo Yii::t('app', 'Auth Assignment') ?></h3></div>

</div>

<div class="col-xs-12">
    <div class="box box-primary view-item">
        <div class="auth-item-view">

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'item_name',
                    'user_id',
                    'created_at',
                ],
            ]) ?>
        </div>
    </div>
    <div class="col-lg-4 col-sm-4 col-xs-12 no-padding" style="padding-top: 20px !important;">
        <div class="col-xs-4 left-padding">
            <?= Html::a(Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-block btn-warning']) ?>
        </div>
        <div class="col-xs-4 left-padding">
            <?= Html::a('Update', ['update', 'item_name' => $model->item_name, 'user_id' => $model->user_id], ['class' => 'btn btn-block btn-info']) ?>
        </div>
        <div class="col-xs-4 left-padding">
            <?= Html::a('Delete', ['delete', 'item_name' => $model->item_name, 'user_id' => $model->user_id], [
                'class' => 'btn btn-block btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </div>
</div>