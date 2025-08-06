<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "transfer_devices_report".
 *
 * @property int $id
 * @property string $serial_no
 * @property string $released_date
 * @property int|null $released_by
 * @property int|null $released_to
 * @property int|null $sales_point
 * @property int|null $transferred_from
 * @property int|null $transferred_to
 * @property string|null $transferred_date
 * @property int|null $transferred_by
 * @property int $status
 * @property int|null $branch
 */
class TransferDevicesReport extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transfer_devices_report';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['serial_no'], 'required'],
            [['released_date', 'transferred_date'], 'safe'],
            [['released_by', 'released_to', 'sales_point', 'transferred_from', 'transferred_to', 'transferred_by', 'status', 'branch'], 'integer'],
            [['serial_no'], 'string', 'max' => 255],
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
            'released_date' => 'Released Date',
            'released_by' => 'Released By',
            'released_to' => 'Released To',
            'sales_point' => 'Sales Point',
            'transferred_from' => 'Transferred From',
            'transferred_to' => 'Transferred To',
            'transferred_date' => 'Transferred Date',
            'transferred_by' => 'Transferred By',
            'status' => 'Status',
            'branch' => 'Branch',
        ];
    }



    public function getReleased0()
    {
        return $this->hasOne(User::className(), ['id' => 'released_by']);
    }

    public function getSales0()
    {
        return $this->hasOne(User::className(), ['id' => 'sales_person']);
    }
    public function getBorderPort()
    {
        return $this->hasOne(BorderPort::className(), ['id' => 'border_port']);
    }
    public function getReleasedTo0()
    {
        return $this->hasOne(User::className(), ['id' => 'released_to']);
    }

    public function getTransferred0()
    {
        return $this->hasOne(User::className(), ['id' => 'transferred_from']);
    }

    public function getTransferredTo0()
    {
        return $this->hasOne(User::className(), ['id' => 'transferred_to']);
    }

    public function getTransferredBy()
    {
        return $this->hasOne(User::className(), ['id' => 'transferred_by']);
    }
}
