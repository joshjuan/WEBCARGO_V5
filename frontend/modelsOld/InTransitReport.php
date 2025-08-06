<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "in_transit_report".
 *
 * @property int $id
 * @property int $serial_no
 * @property int $tzl
 * @property int $border
 * @property int $sales_person
 * @property string $created_at
 * @property int $created_by
 * @property string|null $vehicle_no
 * @property string|null $container_number
 * @property string|null $sale_id
 */
class InTransitReport extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'in_transit_report';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['serial_no'], 'required'],
            [['border', 'sales_person', 'created_by','sale_id'], 'integer'],
            [['serial_no'], 'string' ,'max'=>255],
            [['created_at'], 'safe'],
            [['vehicle_no', 'container_number','tzl'], 'string', 'max' => 200],
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
            'tzl' => 'TZL',
            'border' => 'Border',
            'sales_person' => 'Sales Person',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'vehicle_no' => 'Vehicle No',
            'container_number' => 'Container Number',
        ];
    }

    public function getBorderPort()
    {
        return $this->hasOne(BorderPort::className(), ['id' => 'border']);
    }

    public function getSalesPerson()
    {
        return $this->hasOne(User::className(), ['id' => 'sales_person']);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getType0()
    {
        return $this->hasOne(Devices::className(), ['serial_no' => 'serial_no']);
    }

}
