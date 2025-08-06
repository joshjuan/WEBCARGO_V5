<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\StockDevicesReport */

$this->title = 'Create Stock Devices Report';
$this->params['breadcrumbs'][] = ['label' => 'Stock Devices Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-devices-report-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
