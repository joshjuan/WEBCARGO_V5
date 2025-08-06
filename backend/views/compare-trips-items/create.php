<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\CompareTripsItems $model */

$this->title = 'Create Compare Trips Items';
$this->params['breadcrumbs'][] = ['label' => 'Compare Trips Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="compare-trips-items-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
