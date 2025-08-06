<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\FaultDevices */

$this->title = 'Update Fault Devices: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Fault Devices', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="fault-devices-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
