<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\InTransitReport */

$this->title = 'Create In Transit Report';
$this->params['breadcrumbs'][] = ['label' => 'In Transit Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="in-transit-report-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
