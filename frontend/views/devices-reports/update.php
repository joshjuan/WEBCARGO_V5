<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\DevicesReports $model */

$this->title = 'Update Devices Reports: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Devices Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="devices-reports-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
