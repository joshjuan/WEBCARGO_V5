<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "compare_trips_items".
 *
 * @property int $id
 * @property string $tzdl
 * @property string $serial_no
 * @property string $route
 * @property string $vehicle_no
 * @property string $departure
 * @property string $destination
 * @property string $vendor
 * @property string $cargo_type
 * @property string $activation_date
 * @property string $activated_by
 * @property string $deactivated_by
 * @property string $deactivate_date
 * @property string $status
 */
class CompareTripsItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'compare_trips_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
          //  [['tzdl', 'serial_no', 'route', 'vehicle_no', 'departure', 'destination', 'vendor', 'cargo_type', 'activation_date', 'activated_by', 'deactivated_by', 'deactivate_date', 'status'], 'required'],
            [['activation_date', 'deactivate_date'], 'safe'],
            [['tzdl', 'serial_no', 'route', 'vehicle_no'], 'string', 'max' => 100],
            [['departure', 'destination', 'vendor', 'cargo_type', 'activated_by', 'deactivated_by', 'status'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tzdl' => 'Tzdl',
            'serial_no' => 'Serial No',
            'route' => 'Route',
            'vehicle_no' => 'Vehicle No',
            'departure' => 'Departure',
            'destination' => 'Destination',
            'vendor' => 'Vendor',
            'cargo_type' => 'Cargo Type',
            'activation_date' => 'Activation Date',
            'activated_by' => 'Activated By',
            'deactivated_by' => 'Deactivated By',
            'deactivate_date' => 'Deactivate Date',
            'status' => 'Status',
        ];
    }
}
