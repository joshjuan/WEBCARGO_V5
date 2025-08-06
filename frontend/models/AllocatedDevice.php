<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "allocated_device".
 *
 * @property int $id
 * @property int $serial_no
 * @property string $allocated_date
 * @property int $allocated_to
 * @property int $allocated_by
 */
class AllocatedDevice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'allocated_device';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['serial_no', 'allocated_date', 'allocated_to', 'allocated_by'], 'required'],
            [['allocated_to', 'allocated_by'], 'integer'],
            [['allocated_date','serial_no'], 'safe'],
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
            'allocated_date' => 'Allocated Date',
            'allocated_to' => 'Allocated To',
            'allocated_by' => 'Allocated By',
        ];
    }
}
