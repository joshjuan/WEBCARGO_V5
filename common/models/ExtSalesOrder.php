<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ext_sales_order".
 *
 * @property int $id
 * @property int $order_id
 * @property string $customer
 * @property string $devices
 * @property string|null $selcome_url
 * @property string $payment_status
 * @property float|null $total_amount
 * @property string $products
 * @property string $created_at
 * @property int $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 */
class ExtSalesOrder extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ext_sales_order';
    }



    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['selcome_url', 'total_amount', 'updated_at', 'updated_by'], 'default', 'value' => null],
            [['order_id', 'customer', 'devices', 'payment_status', 'products', 'created_at', 'created_by'], 'required'],
            [['order_id', 'created_by', 'updated_by'], 'integer'],
            [['customer', 'devices', 'selcome_url', 'products'], 'string'],
            [['total_amount'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['payment_status'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'customer' => 'Customer',
            'devices' => 'Devices',
            'selcome_url' => 'Selcome Url',
            'payment_status' => 'Payment Status',
            'total_amount' => 'Total Amount',
            'products' => 'Products',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * {@inheritdoc}
     * @return ExtSalesOrderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ExtSalesOrderQuery(get_called_class());
    }

}
