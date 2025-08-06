<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\CompareTripsItems $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Compare Trips Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="compare-trips-items-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            'id',
            'tzdl',
            'serial_no',
            'route',
            'vehicle_no',
            'departure',
            'destination',
            'vendor',
            'cargo_type',
            'activation_date',
            'activated_by',
            'deactivated_by',
            'deactivate_date',
            'status',
        ],
    ]) ?>

</div>
