<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\AllocatedDevice */

$this->title = 'Update Allocated Device: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Allocated Devices', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="allocated-device-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
