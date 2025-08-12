<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ext_sales_trips".
 *
 * @property int $id
 * @property string|null $customer
 * @property string $master
 * @property string|null $slaves
 * @property int $gate_id
 * @property int $border_id
 * @property string $trip_no
 * @property string|null $vehicle_no
 * @property string|null $trailer_no
 * @property string|null $merchant_no
 * @property string $receipt_no
 * @property string|null $agent
 * @property string|null $driver
 * @property int $cargo_type_id
 * @property string|null $cargo_no
 * @property string|null $chassis_no
 * @property string|null $vehicle_type
 * @property string|null $container_no
 * @property string|null $device_price
 * @property int|null $trip_status
 * @property string $start_date
 * @property string $end_date
 * @property string $created_at
 * @property int|null $created_by
 * @property string|null $branch
 * @property string|null $cancelled_at
 * @property int|null $cancelled_by
 * @property string|null $editted_at
 * @property int|null $editted_by
 */
class ExtSalesTrips extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ext_sales_trips';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer', 'slaves', 'vehicle_no', 'trailer_no', 'merchant_no', 'agent', 'driver', 'cargo_no', 'chassis_no', 'vehicle_type', 'container_no', 'device_price', 'created_by', 'branch', 'cancelled_at', 'cancelled_by', 'editted_at', 'editted_by'], 'default', 'value' => null],
            [['trip_status'], 'default', 'value' => 1],
            [['customer', 'slaves', 'agent', 'driver', 'device_price'], 'string'],
            [['master', 'gate_id', 'border_id', 'trip_no', 'receipt_no', 'cargo_type_id', 'start_date', 'end_date', 'created_at'], 'required'],
            [['gate_id', 'border_id', 'cargo_type_id', 'trip_status', 'created_by', 'cancelled_by', 'editted_by'], 'integer'],
            [['start_date', 'end_date', 'created_at', 'cancelled_at', 'editted_at'], 'safe'],
            [['master', 'trip_no', 'vehicle_no', 'trailer_no', 'merchant_no', 'receipt_no', 'cargo_no', 'chassis_no', 'vehicle_type', 'container_no', 'branch'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer' => 'Customer',
            'master' => 'Master',
            'slaves' => 'Slaves',
            'gate_id' => 'Gate ID',
            'border_id' => 'Border ID',
            'trip_no' => 'Trip No',
            'vehicle_no' => 'Vehicle No',
            'trailer_no' => 'Trailer No',
            'merchant_no' => 'Merchant No',
            'receipt_no' => 'Receipt No',
            'agent' => 'Agent',
            'driver' => 'Driver',
            'cargo_type_id' => 'Cargo Type ID',
            'cargo_no' => 'Cargo No',
            'chassis_no' => 'Chassis No',
            'vehicle_type' => 'Vehicle Type',
            'container_no' => 'Container No',
            'device_price' => 'Device Price',
            'trip_status' => 'Trip Status',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'branch' => 'Branch',
            'cancelled_at' => 'Cancelled At',
            'cancelled_by' => 'Cancelled By',
            'editted_at' => 'Editted At',
            'editted_by' => 'Editted By',
        ];
    }

    /**
     * {@inheritdoc}
     * @return ExtSalesTripsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ExtSalesTripsQuery(get_called_class());
    }

}
