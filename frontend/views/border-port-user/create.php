<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\BorderPortUser */

$this->title = '';
$this->params['breadcrumbs'][] = ['label' => 'Border Port Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Border Port User';
?>
<div class="border-port-user-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
