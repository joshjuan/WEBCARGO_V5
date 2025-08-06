<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\InTransitSlave */

$this->title = 'Update In Transit Slave: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'In Transit Slaves', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="in-transit-slave-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
