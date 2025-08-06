<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\BorderPortUser */

$this->title = '' ;
$this->params['breadcrumbs'][] = ['label' => 'Border Port Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="border-port-user-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
