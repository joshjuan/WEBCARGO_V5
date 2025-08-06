<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\CompareTripsItems $model */

$this->title = 'Update Compare Trips Items: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Compare Trips Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="compare-trips-items-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
