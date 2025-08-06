<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\CompareTrips $model */

$this->title = 'Update Compare Trips: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Compare Trips', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="compare-trips-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
