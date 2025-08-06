<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\InTransitSlaveReport */

$this->title = 'Create In Transit Slave Report';
$this->params['breadcrumbs'][] = ['label' => 'In Transit Slave Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="in-transit-slave-report-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
