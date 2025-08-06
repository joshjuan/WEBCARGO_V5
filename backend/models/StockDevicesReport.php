<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "stock_devices_report".
 *
 * @property int $id
 * @property int $serial_no
 * @property int $created_by
 * @property string $created_at
 * @property int $status
 * @property int $location_from
 */
class StockDevicesReport extends \yii\db\ActiveRecord
{

    const available=1;
    const not_deactivated=2;

    public static function getStatus()
    {
        return [
            self::available => Yii::t('app', 'AVAILABLE'),
            self::not_deactivated => Yii::t('app', 'NOT DEACTIVATED'),


        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stock_devices_report';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['serial_no', 'created_by', 'created_at', 'status'], 'required'],
            [[ 'created_by', 'status','location_from'], 'integer'],
            [['created_at','serial_no'], 'safe'],
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
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'location_from' => 'From',
            'status' => 'Status',
        ];
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
}
