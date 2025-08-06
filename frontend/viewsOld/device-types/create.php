<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\DeviceTypes */

$this->title = 'Create Device Types';
$this->params['breadcrumbs'][] = ['label' => 'Device Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="device-types-create">



    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
