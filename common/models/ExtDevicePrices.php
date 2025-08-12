<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ext_device_prices".
 *
 * @property int $id
 * @property string $value
 * @property string $created_at
 */
class ExtDevicePrices extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ext_device_prices';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['value', 'created_at'], 'required'],
            [['value'], 'string'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'value' => 'Value',
            'created_at' => 'Created At',
        ];
    }

    /**
     * {@inheritdoc}
     * @return ExtDevicePricesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ExtDevicePricesQuery(get_called_class());
    }

}
