<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\DeviceCategory */

$this->title = 'Update Device Category: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Device Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="device-category-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
