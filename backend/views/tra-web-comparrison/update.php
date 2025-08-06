<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\TraWebComparrison $model */

$this->title = 'Update Tra Web Comparrison: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tra Web Comparrisons', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tra-web-comparrison-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
