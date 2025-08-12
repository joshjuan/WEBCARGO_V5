<?php

namespace common\models;

use frontend\models\Devices;

/**
 * This is the ActiveQuery class for [[ExtSalesOrder]].
 *
 * @see ExtSalesOrder
 */
class ExtSalesOrderQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/


    public static function createSalesOrderTemp(mixed $customer, mixed $devices, mixed $userId)
    {
        $model = new ExtSalesOrder();
        $model->order_id =  0;
        $model->customer = json_encode($customer);
        $model->devices = json_encode($devices);
        $model->total_amount = 0;
        $model->created_at = date('Y-m-d H:i:s');
        $model->created_by = $userId;
        $model->payment_status = 'pending_external';
        $model->products = json_encode([]);

        if (!$model->save()) {
            throw new \Exception('Failed to save order locally');
        }

        return $model;
    }

    public static function updateSalesOrder(ExtSalesOrder $model, mixed $response)
    {
        $model->order_id = $response['id'];
        $model->total_amount = $response['amount_total'];
        $model->payment_status = $response['payment_status'];
        $model->products = json_encode($response['products']);

        if (!$model->save()) {
            throw new \Exception('Failed to update order with external data');
        }
    }



    /**
     * {@inheritdoc}
     * @return ExtSalesOrder[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ExtSalesOrder|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
