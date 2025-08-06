<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\ReleasedDevicesReport */

$this->title = 'Create Released Devices Report';
$this->params['breadcrumbs'][] = ['label' => 'Released Devices Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="released-devices-report-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
