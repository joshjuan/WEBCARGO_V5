<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\CompareTrips $model */

$this->title = ' Compare Trips';
$this->params['breadcrumbs'][] = ['label' => 'Compare Trips', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="compare-trips-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
