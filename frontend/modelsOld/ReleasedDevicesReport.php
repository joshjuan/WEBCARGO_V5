<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "released_devices_report".
 *
 * @property int $id
 * @property int $serial_no
 * @property string $released_date
 * @property int|null $released_by
 * @property int|null $released_to
 * @property int|null $transferred_from
 * @property int|null $transferred_to
 * @property string|null $transferred_date
 * @property int $status
 * @property int $sales_point
 *  * @property int $branch
 */
class ReleasedDevicesReport extends \yii\db\ActiveRecord
{
    const RELEASED = 1;
    public static function getArrayStatus()
    {
        return [
            self::RELEASED => Yii::t('app', 'AVILABLE'),

        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'released_devices_report';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['serial_no', 'released_date'], 'required'],
            [[ 'released_by', 'branch','released_to', 'transferred_from', 'transferred_to', 'status','sales_point'], 'integer'],
            [['released_date', 'serial_no','transferred_date'], 'safe'],
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
            'transferred_from' => 'Transferred From',
            'transferred_to' => 'Transferred To',
            'transferred_date' => 'Transferred Date',
            'status' => 'Status',
            'sales_point' => 'Sales Point',
        ];
    }

    public function getReleasedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'released_by']);
    }

    public function getReleasedTo()
    {
        return $this->hasOne(User::className(), ['id' => 'released_to']);
    }

    public function getTransferredFrom()
    {
        return $this->hasOne(User::className(), ['id' => 'transferred_from']);
    }

    public function getTransferredTo()
    {
        return $this->hasOne(User::className(), ['id' => 'transferred_to']);
    }
}
