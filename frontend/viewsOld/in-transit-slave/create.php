<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\InTransitSlave */

$this->title = 'Create In Transit Slave';
$this->params['breadcrumbs'][] = ['label' => 'In Transit Slaves', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="in-transit-slave-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
