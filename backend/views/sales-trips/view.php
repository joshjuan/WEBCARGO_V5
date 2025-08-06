<?php

use kartik\form\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\SalesTrips */

$this->title = $model->receipt_number;
$this->params['breadcrumbs'][] = ['label' => 'Sales Trips', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="sales-trips-view">

    <hr/>

    <p>

        <?= Html::a(Yii::t('app', 'Back Home'), ['index'], ['class' => 'btn btn-warning']) ?>

        <?php
        Modal::begin([
            'header' => '<h3 class="text text-primary">Cancel Trip</h3>',
            'toggleButton' => ['label' => ' <i class="fa fa-times-circle"></i> Cancel Trip', 'class' => 'btn btn-danger',],
            'size' => Modal::SIZE_DEFAULT,
            'options' => ['class' => 'slide', 'id' => 'modal-2'],
        ]);
        ?>
    <div class="product-form" style="margin-left: 10px">

        <?php $form = ActiveForm::begin(['action' => ['cancel', 'id' => $model->id]]); ?>

        <?= $form->field($model, 'remarks')->textarea(['maxlength' => true, 'rows' => 4]) ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Submit'), ['class' => $model->isNewRecord ? 'btn b btn-success' : 'btn  btn-success']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <?php
    Modal::end();
    ?>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            'sale_date',

            [
                'attribute' => 'tzl',
                'contentOptions' => ['class' => 'truncate'],
            ],
            [
                'attribute' => 'branch',
                'value' => function ($model) {
                    if ($model->branch) {
                        return $model->branch0->name;
                    }
                }
            ],
//        [
//            'attribute' => 'type',
//            'value' => 'types0.name',
//        ],
            'serial_no',
            'slaves_serial',
            [
                'label' => 'No of Slave',
                'value' => function ($model) {
                    if ($model->serial_no != '') {
                        return \backend\models\SalesTripSlaves::find()->where(['sale_id' => $model->id])->count();
                    } else {
                        return '0';
                    }
                }
            ],
            'start_date_time',
            'end_date',
            'vehicle_number',
            'driver_name',

            [
                'attribute' => 'border_id',

                'value' => function ($model) {
                    if ($model->border_id) {
                        return $model->borderPort->name;
                    }
                }
            ],
            'driver_number',
            [
                'attribute' => 'amount',
                'value' => 'amount',
                'visible' => Yii::$app->user->can('checkAmount'),
            ],
            [
                'attribute' => 'gate_number',
                'value' => function ($model) {
                    if ($model->gate_number) {
                        return $model->port->name;
                    }
                }
            ],
            'company_name',
            [
                'attribute' => 'sales_person',
                'value' => function ($model) {
                    if ($model->sales_person) {
                        return $model->salesPerson->username;
                    }
                }
            ],
            'receipt_number',
            'passport',
            'container_number',
            [
                'attribute' => 'customer_type_id',
                'label' => 'Payment Method',
                'value' => function ($model) {
                    if ($model->customer_type_id == 2) {

                        return 'BILL PAYMENT';
                    } elseif ($model->customer_type_id == 1) {
                        return 'CASH PAYMENT';
                    }
                }
            ],
            [
                'attribute' => 'customer_id',
                'label' => 'Customer Name',
                'value' => function ($model) {
                    if ($model->customer_id) {
                        return $model->customer->company_name;
                    }
                    else{
                        return $model->company_name;
                    }
                }
            ],
            //  'customer_name',
            'agent',
            [
                'attribute' => 'edited_by',
                'value' => function ($model) {
                    if ($model->editedBy) {
                        return $model->editedBy->username;
                    }
                }
            ],
            'edited_at',
            [
                'attribute' => 'cancelled_by',
                'value' => function ($model) {
                    if ($model->cancelled_by) {
                        return $model->canceledBy->username;
                    }
                }
            ],
            'date_cancelled',
            'remarks',

            // 'id',
//            'datetime',
//            'trip_number',
//            'start_date_time',
//            'vehicle_number',
//            'driver_name',
//            'border',
//            'trip_status',
//            'driver_number',
//            'serial_no',
//            'amount',
//            'gate_number',
//            'stop_date_time',
//            'sales_person',
//            'receipt_number',
//            'passport',
//            'container_number',
//            'vehicle_type',
//            'customer_name',
//            'agent',
//            'cancelled_by',
//            'edited_by',
//            'edited_at',
//            'date_cancelled',
        ],
    ]) ?>

</div>
