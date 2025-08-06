<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "device_movements".
 *
 * @property int $id
 * @property int $serial
 * @property int $action
 * @property int|null $receive_from
 * @property int|null $received_from_staff
 * @property string|null $received_date
 */
class DeviceMovements extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'device_movements';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['serial', 'action'], 'required'],
            [['action', 'receive_from', 'received_from_staff'], 'integer'],
            [['received_date','serial'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'serial' => 'Serial',
            'action' => 'Action',
            'receive_from' => 'Receive From',
            'received_from_staff' => 'Received From Staff',
            'received_date' => 'Received Date',
        ];
    }
}
