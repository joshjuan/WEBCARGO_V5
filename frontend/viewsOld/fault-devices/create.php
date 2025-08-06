<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\FaultDevices */

$this->title = 'Create Fault Devices';
$this->params['breadcrumbs'][] = ['label' => 'Fault Devices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fault-devices-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
