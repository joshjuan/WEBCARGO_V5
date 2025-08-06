<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\BorderPortUser */

$this->title = '';
$this->params['breadcrumbs'][] = ['label' => 'Border Port Users', 'url' => ['port']];
$this->params['breadcrumbs'][] = 'Border Port User';
?>
<div class="border-port-user-create">

    <?= $this->render('_formPort', [
        'model' => $model,
    ]) ?>

</div>
