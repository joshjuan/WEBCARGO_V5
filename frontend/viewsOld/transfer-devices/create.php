<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\ReleasedDevices */

$this->title = 'Create Released Devices';
$this->params['breadcrumbs'][] = ['label' => 'Released Devices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="released-devices-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
