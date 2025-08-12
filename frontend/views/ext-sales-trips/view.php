<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\ExtSalesTrips $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ext Sales Trips'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="ext-sales-trips-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'customer:ntext',
            'master',
            'slaves:ntext',
            'gate_id',
            'border_id',
            'trip_no',
            'vehicle_no',
            'trailer_no',
            'merchant_no',
            'receipt_no',
            'agent:ntext',
            'driver:ntext',
            'cargo_type_id',
            'cargo_no',
            'chassis_no',
            'vehicle_type',
            'container_no',
            'device_price:ntext',
            'trip_status',
            'start_date',
            'end_date',
            'created_at',
            'created_by',
            'branch',
            'cancelled_at',
            'cancelled_by',
            'editted_at',
            'editted_by',
        ],
    ]) ?>

</div>
