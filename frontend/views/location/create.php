<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Location */

$this->title = '';
$this->params['breadcrumbs'][] = ['label' => 'Locations', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Create Location';
?>
<div class="location-create" style="padding-top: 3%">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
