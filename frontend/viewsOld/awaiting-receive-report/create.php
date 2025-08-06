<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\ReceivedDevicesReport */

$this->title = 'Create Received Devices Report';
$this->params['breadcrumbs'][] = ['label' => 'Received Devices Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="received-devices-report-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
