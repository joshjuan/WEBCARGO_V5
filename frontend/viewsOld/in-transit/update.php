<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\InTransit */

$this->title = 'Update In Transit: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'In Transits', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="in-transit-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
