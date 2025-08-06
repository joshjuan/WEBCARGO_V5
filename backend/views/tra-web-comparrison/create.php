<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\TraWebComparrison $model */

$this->title = 'Create Tra Web Comparrison';
$this->params['breadcrumbs'][] = ['label' => 'Tra Web Comparrisons', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tra-web-comparrison-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
