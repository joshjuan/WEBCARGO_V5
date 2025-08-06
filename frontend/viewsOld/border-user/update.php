<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\BorderUser */

$this->title = 'Update Border User: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Border Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="border-user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
