<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "fault_devices_report".
 *
 * @property int $id
 * @property int $serial_no
 * @property int $created_by
 * @property string $created_at
 * @property string|null $remarks
 * @property int $status
 */
class FaultDevicesReport extends \yii\db\ActiveRecord
{

    const FAULT_DEVICE =1;

    public static function getArrayStatus()
    {
        return [
            self::FAULT_DEVICE => Yii::t('app', 'FAULT DEVICE'),

        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fault_devices_report';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['serial_no', 'created_by', 'created_at', 'status'], 'required'],
            [[ 'created_by', 'status'], 'integer'],
            [['created_at','serial_no','serial_no'], 'safe'],
            [['remarks'], 'string'],
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
            'remarks' => 'Remarks',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
}
