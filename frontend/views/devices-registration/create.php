<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\DevicesRegistration $model */

$this->title = 'Create Devices Registration';
$this->params['breadcrumbs'][] = ['label' => 'Devices Registrations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="devices-registration-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
