<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\InTransit */

$this->title = 'Create In Transit';
$this->params['breadcrumbs'][] = ['label' => 'In Transits', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="in-transit-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
