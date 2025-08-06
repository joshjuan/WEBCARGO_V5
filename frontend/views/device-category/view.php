<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\DeviceCategory */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Device Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="device-category-view">

    <p>
        <?= Html::a('Back Home', ['index'], ['class' => 'btn btn-primary']) ?>
        <?php Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
           // 'id',
            'name',
            'bland',
            'created_at',
            [
                'attribute' => 'created_by',
                'value' => function ($model) {
                    $user=\frontend\models\User::find()
                        ->where(['id'=>$model->created_by])
                        ->one();
                    if ($user) {
                       return $user->username;
                    }
                }
            ],
           // 'created_by',
        ],
    ]) ?>

</div>
