<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\SysPensionersVerificationsLogs $model */

$this->title = 'Create Sys Pensioners Verifications Logs';
$this->params['breadcrumbs'][] = ['label' => 'Sys Pensioners Verifications Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sys-pensioners-verifications-logs-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
