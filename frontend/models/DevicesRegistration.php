<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "devices_registration".
 *
 * @property int $id
 * @property string $serial_no
 * @property string|null $sim_card
 * @property int|null $received_from
 * @property int|null $border_port
 * @property int|null $received_from_staff
 * @property string|null $received_at
 * @property int|null $received_status
 * @property int|null $received_by
 * @property string|null $remark
 * @property int|null $created_by
 * @property int|null $device_from
 * @property int|null $stock_status
 * @property string|null $created_at
 * @property int|null $status
 * @property int|null $branch
 * @property int|null $type
 * @property int|null $device_category
 * @property int|null $released_by
 * @property int|null $released_to
 * @property int|null $transferred_from
 * @property int|null $transferred_to
 * @property string|null $transferred_date
 * @property int|null $transferred_by
 * @property string|null $released_date
 * @property int|null $sales_person
 * @property string|null $tzl
 * @property string|null $vehicle_no
 * @property string|null $container_number
 * @property int|null $sale_id
 * @property int $view_status
 * @property int|null $partiner
 * @property string|null $registration_date
 * @property int|null $registration_by
 */
class DevicesRegistration extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'devices_registration';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['serial_no'], 'required'],
            [['received_from', 'border_port', 'received_from_staff', 'received_status', 'received_by', 'created_by', 'device_from', 'stock_status', 'status', 'branch', 'type', 'device_category', 'released_by', 'released_to', 'transferred_from', 'transferred_to', 'transferred_by', 'sales_person', 'sale_id', 'view_status', 'partiner', 'registration_by'], 'integer'],
            [['received_at', 'created_at', 'transferred_date', 'released_date', 'registration_date'], 'safe'],
            [['remark'], 'string'],
            [['serial_no'], 'safe'],
            [['sim_card', 'tzl', 'vehicle_no', 'container_number'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'serial_no' => 'Serial No',
            'sim_card' => 'Sim Card',
            'received_from' => 'Received From',
            'border_port' => 'Border Port',
            'received_from_staff' => 'Received From Staff',
            'received_at' => 'Received At',
            'received_status' => 'Received Status',
            'received_by' => 'Received By',
            'remark' => 'Remark',
            'created_by' => 'Created By',
            'device_from' => 'Device From',
            'stock_status' => 'Stock Status',
            'created_at' => 'Created At',
            'status' => 'Status',
            'branch' => 'Branch',
            'type' => 'Type',
            'device_category' => 'Device Category',
            'released_by' => 'Released By',
            'released_to' => 'Released To',
            'transferred_from' => 'Transferred From',
            'transferred_to' => 'Transferred To',
            'transferred_date' => 'Transferred Date',
            'transferred_by' => 'Transferred By',
            'released_date' => 'Released Date',
            'sales_person' => 'Sales Person',
            'tzl' => 'Tzl',
            'vehicle_no' => 'Vehicle No',
            'container_number' => 'Container Number',
            'sale_id' => 'Sale ID',
            'view_status' => 'View Status',
            'partiner' => 'Partiner',
            'registration_date' => 'Registration Date',
            'registration_by' => 'Registration By',
        ];
    }
}
