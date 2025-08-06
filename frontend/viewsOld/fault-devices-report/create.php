<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\FaultDevicesReport */

$this->title = 'Create Fault Devices Report';
$this->params['breadcrumbs'][] = ['label' => 'Fault Devices Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fault-devices-report-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
