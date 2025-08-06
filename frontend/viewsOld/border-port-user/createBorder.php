<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\BorderPortUser */

$this->title = '';
$this->params['breadcrumbs'][] = ['label' => 'Border Port Users', 'url' => ['border']];
$this->params['breadcrumbs'][] = 'Border Port User';
?>
<div class="border-port-user-create">

    <?= $this->render('_formBorder', [
        'model' => $model,
    ]) ?>

</div>
