<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\CompareTrips $model */

$this->title = $model->document_name;
$this->params['breadcrumbs'][] = ['label' => 'Compare Trips', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="compare-trips-view">

    <p>
        <?= Html::a('Back home', ['index'], ['class' => 'btn btn-primary']) ?>
        <?php

        if ($model->status =='1'){
          echo  Html::a('Compare items', ['compare', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to compare with sales records?',
                    'method' => 'post',
                ],
            ]);
        }
        else{

          echo  Html::a('View Already compared report', ['tra-web-comparrison/report', 'id' => $model->id], [
                'class' => 'btn btn-warning',
                'data' => [
                    'confirm' => 'Are you sure you want to view  report?',
                    'method' => 'post',
                ],
            ]);
        }


        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
           // 'id',
          //  'document_path',
            'document_name',
            'date_from',
            'date_to',
            'total_activation',
            'status',
            'upload_by',
            'upload_date',
        ],
    ]) ?>

</div>
