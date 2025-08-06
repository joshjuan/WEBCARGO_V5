<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Rfidcard */

$this->title = 'Create Rfidcard';
$this->params['breadcrumbs'][] = ['label' => 'Rfidcards', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rfidcard-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
