<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\SalesTrips */

$this->title = 'New Sub Sales Trips';
$this->params['breadcrumbs'][] = ['label' => 'Sales Trips', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sales-trips-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
