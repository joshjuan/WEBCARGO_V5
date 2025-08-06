<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ReleasedDevicesReport */

$this->title = 'Update Released Devices Report: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Released Devices Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="released-devices-report-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
