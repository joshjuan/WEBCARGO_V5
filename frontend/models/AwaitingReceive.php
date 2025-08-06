<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "awaiting_receive".
 *
 * @property int $id
 * @property int $serial_no
 * @property int $received_from
 * @property int|null $border_port
 * @property int|null $received_from_staff
 * @property string $received_at
 * @property int|null $received_status
 * @property string|null $remark
 * @property int $received_by
 * @property int $branch
 */
class AwaitingReceive extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'awaiting_receive';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['serial_no', 'received_from', 'received_at', 'received_by'], 'required'],
            [[ 'received_from', 'border_port','branch', 'received_from_staff', 'received_status', 'received_by'], 'integer'],
            [['serial_no','received_at'], 'safe'],
            [['remark'], 'string'],
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
            'received_from' => 'Received From',
            'border_port' => 'Border Port',
            'received_from_staff' => 'Received From Staff',
            'received_at' => 'Received At',
            'received_status' => 'Received Status',
            'remark' => 'Remark',
            'received_by' => 'Received By',
        ];
    }


    public function getBorderPort()
    {
        return $this->hasOne(BorderPort::className(), ['id' => 'border_port']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocation()
    {
        return $this->hasOne(Location::className(), ['id' => 'received_from']);
    }

    public function getType0()
    {
        return $this->hasOne(Devices::className(), ['serial' => 'serial_no']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceivedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'received_by']);
    }
}
