<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\SysPensionersVerificationsLogs $model */

$this->title = 'Update Sys Pensioners Verifications Logs: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Sys Pensioners Verifications Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sys-pensioners-verifications-logs-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
