<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Device */

$this->title = '';
$this->params['breadcrumbs'][] = ['label' => 'Devices', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Create Device';
?>
<div class="device-create">

    <h3><?= Html::encode('DEVICE SUMMARY DATA') ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
