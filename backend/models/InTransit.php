<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "in_transit".
 *
 * @property int $id
 * @property int $serial_no
 * @property int $tzl
 * @property int $border
 * @property int $sales_person
 * @property string $created_at
 * @property int $created_by
 * @property int $view_status
 * @property int $type
 * @property string|null $vehicle_no
 * @property string|null $container_number
 * @property string|null $branch
 * @property string|null $sale_id
 */
class InTransit extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'in_transit';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['serial_no'], 'required'],
            [['serial_no', 'border', 'sales_person', 'created_by','view_status','branch','sale_id','type'], 'integer'],
            [['created_at'], 'safe'],
            [['vehicle_no', 'container_number','tzl'], 'string', 'max' => 200],
            [['serial_no'], 'unique'],
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


    public function getReleased0()
    {
        return $this->hasOne(User::className(), ['id' => 'sales_person']);
    }

    public function getCreatedBy0()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getType0()
    {
        return $this->hasOne(Devices::className(), ['serial' => 'serial_no']);
    }

    public static function LongTrips()
    {
        $date_today = strtotime("-7 day");
        $date_today = date('Y-m-d', $date_today);

        $device=InTransit::find()->where(['<','date(created_at)',$date_today])->andWhere(['view_status'=>Devices::in_transit])->count();

        if ($device > 0) {
            echo $device;
        } else {
            echo 0;
        }
    }
}
