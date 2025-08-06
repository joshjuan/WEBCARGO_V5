<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Portpoint */

$this->title = 'Create Portpoint';
$this->params['breadcrumbs'][] = ['label' => 'Portpoints', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="portpoint-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
