<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\BorderPort */

$this->title = '' ;
$this->params['breadcrumbs'][] = ['label' => 'Border Ports', 'url' => ['border']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="border-port-update" style="padding-top: 3%">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
