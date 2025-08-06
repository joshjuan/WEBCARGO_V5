<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\User */

$this->title = 'Create Bill Customer User';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['user/index-bill-customer']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <?= $this->render('_formBillCustomer', [
        'model' => $model,
    ]) ?>

</div>
