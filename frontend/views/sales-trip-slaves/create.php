<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\SalesTripSlaves */

$this->title = 'Create Sales Trip Slaves';
$this->params['breadcrumbs'][] = ['label' => 'Sales Trip Slaves', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sales-trip-slaves-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
