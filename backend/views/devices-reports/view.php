<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\DevicesReports $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Devices Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="devices-reports-view">

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
            'serial_no',
            'sim_card',
            'received_from',
            'received_to',
            'border_port',
            'received_from_staff',
            'received_at',
            'received_status',
            'received_by',
            'remark:ntext',
            'created_by',
            'created_at',
            'branch',
            'type',
            'device_category',
            'released_by',
            'released_to',
            'transferred_from',
            'transferred_to',
            'transferred_date',
            'transferred_by',
            'released_date',
            'sales_person',
            'tzl',
            'vehicle_no',
            'container_number',
            'sale_id',
            'view_status',
            'registration_date',
            'registration_by',
            'movement_unique_id',
        ],
    ]) ?>

</div>
