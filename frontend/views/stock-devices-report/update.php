<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\StockDevicesReport */

$this->title = 'Update Stock Devices Report: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Stock Devices Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="stock-devices-report-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
