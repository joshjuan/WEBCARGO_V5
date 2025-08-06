<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\DevicesReports $model */

$this->title = 'Create Devices Reports';
$this->params['breadcrumbs'][] = ['label' => 'Devices Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="devices-reports-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
