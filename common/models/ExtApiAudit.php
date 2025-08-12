<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ext_api_audit".
 *
 * @property int $id
 * @property string|null $request_to_backend
 * @property string $request_data
 * @property string $endpoint
 * @property int $endpoint_type
 * @property int $status_code
 * @property string $response_data
 * @property string $created_at
 * @property int $created_by
 */
class ExtApiAudit extends \yii\db\ActiveRecord
{

    const CREATE_SALES_ORDER = 1;
    const CREATE_PAYMENT_ORDER = 2;
    const GET_CUSTOMER_ORDER = 3;
    const GET_DEVICE_PRICE = 4;
    const GET_PAYMENT_VALIDATION = 5;
    const CREATE_SALES_TRIP = 6;
    const CREATE_DEVICE_SWAPPING = 7;
    const CREATE_SALES_TRIP_TOP_UP = 8;
    const CANCEL_SALES_TRIP = 9;
    const GET_UNPAID_SALES_ORDER = 10;
    const GET_PAID_SALES_ORDER = 11;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ext_api_audit';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['request_to_backend'], 'default', 'value' => null],
            [['request_to_backend', 'request_data', 'response_data'], 'string'],
            [['request_data', 'endpoint', 'endpoint_type', 'status_code', 'response_data', 'created_at', 'created_by'], 'required'],
            [['endpoint_type', 'status_code', 'created_by'], 'integer'],
            [['created_at'], 'safe'],
            [['endpoint'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'request_to_backend' => 'Request To Backend',
            'request_data' => 'Request Data',
            'endpoint' => 'Endpoint',
            'endpoint_type' => 'Endpoint Type',
            'status_code' => 'Status Code',
            'response_data' => 'Response Data',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * {@inheritdoc}
     * @return ExtApiAuditQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ExtApiAuditQuery(get_called_class());
    }

}
