<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\AuthItemChild */

$this->title = $model->parent;
$this->params['breadcrumbs'][] = ['label' => 'Auth Item Children', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?> <p style="padding-top: 20px"></p>

<div class="col-sm-20">
    <div class="col-md-12">
        <h2 class="page-header">
            <i class="fa fa-list"></i> <?php echo Yii::t('app', 'Role Assignment details'); ?>
        </h2>
    </div>
    <div class="panel panel-default">
        <div class="table table-responsive">

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'parent',
                    'child',
                ],
            ]) ?>

        </div>

    </div>
    <p>
        <?= Html::a('Update', ['update', 'parent' => $model->parent, 'child' => $model->child], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Back', ['index', 'parent' => $model->parent, 'child' => $model->child], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'parent' => $model->parent, 'child' => $model->child], ['class' => 'btn btn-primary',
            'data' => ['confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',],]) ?>
    </p>
</div>