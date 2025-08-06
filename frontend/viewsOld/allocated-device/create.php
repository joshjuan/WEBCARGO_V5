<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\AllocatedDevice */

$this->title = 'Create Allocated Device';
$this->params['breadcrumbs'][] = ['label' => 'Allocated Devices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="allocated-device-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
