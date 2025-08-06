<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Device */

$this->title = '' ;
$this->params['breadcrumbs'][] = ['label' => 'Devices', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->	serial]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="device-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
