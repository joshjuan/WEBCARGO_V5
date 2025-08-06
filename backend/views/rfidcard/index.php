<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RfidcardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rfidcards';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rfidcard-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Rfidcard', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'card_no',
            'assigned_to',
            'assigned_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
