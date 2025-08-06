<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\SysPensionersVerificationsLogs $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Sys Pensioners Verifications Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="sys-pensioners-verifications-logs-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'date_time',
            'uid',
            'pension_number',
            'full_names',
            'verification_code',
            'code_expiry_date',
            'sent_date_time',
            'batch_code',
            'kin_mobile_number',
            'verified_by_uid',
            'verified_date_time',
            'verification_status',
            'checker_remarks:ntext',
            'date_renewed',
            'start_date',
            'expiry_date',
            'verified_location',
            'verified_by',
        ],
    ]) ?>

</div>
