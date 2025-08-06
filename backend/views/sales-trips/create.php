<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\SalesTrips */

$this->title = 'Create Sales Trips';
$this->params['breadcrumbs'][] = ['label' => 'Sales Trips', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sales-trips-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
