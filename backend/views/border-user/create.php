<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\BorderUser */

$this->title = 'Create Border User';
$this->params['breadcrumbs'][] = ['label' => 'Border Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="border-user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
