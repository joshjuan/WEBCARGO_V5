<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "rfidcard".
 *
 * @property int $id
 * @property int $card_no
 * @property int $assigned_to
 * @property int $assigned_by
 */
class Rfidcard extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rfidcard';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['card_no', 'assigned_to', 'assigned_by'], 'required'],
            [['card_no', 'assigned_to', 'assigned_by'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'card_no' => 'Card No',
            'assigned_to' => 'Assigned To',
            'assigned_by' => 'Assigned By',
        ];
    }
}
