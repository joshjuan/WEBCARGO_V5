<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "sales_trip_slaves".
 *
 * @property int $id
 * @property int|null $sale_id
 * @property int|null $serial_no
 * @property string|null $created_at
 */
class SalesTripSlaves extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sales_trip_slaves';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sale_id'], 'integer'],
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
            'sale_id' => 'Sale ID',
            'serial_no' => 'Serial No',
            'created_at' => 'Created At',
        ];
    }
}
