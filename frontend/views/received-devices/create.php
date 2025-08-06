<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ReceivedDevices */

$this->title = 'Create Received Devices';
$this->params['breadcrumbs'][] = ['label' => 'Received Devices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="received-devices-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
