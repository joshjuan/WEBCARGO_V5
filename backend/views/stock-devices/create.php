<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\StockDevices */

$this->title = 'Create Stock Devices';
$this->params['breadcrumbs'][] = ['label' => 'Stock Devices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-devices-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
