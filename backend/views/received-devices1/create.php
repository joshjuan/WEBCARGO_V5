<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ReceivedDevices */

$this->title = '';
$this->params['breadcrumbs'][] = ['label' => 'Received Devices', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Received Devices';
?>
<div class="received-devices-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
