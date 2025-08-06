<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\DeviceCategory */

$this->title = 'Create Device Category';
$this->params['breadcrumbs'][] = ['label' => 'Device Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="device-category-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
