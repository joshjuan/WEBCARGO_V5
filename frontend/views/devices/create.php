<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Devices */

$this->title = 'Create Devices';
$this->params['breadcrumbs'][] = ['label' => 'Devices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="devices-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
