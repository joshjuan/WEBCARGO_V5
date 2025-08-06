<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "in_transit_slave".
 *
 * @property int $id
 * @property int $serial_no
 * @property int|null $intansit_id
 * @property int $branch
 * @property string|null $created_at
 */
class InTransitSlave extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'in_transit_slave';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
          //  [['serial_no', 'branch'], 'required'],
            [['intansit_id', 'branch'], 'integer'],
            [['serial_no'], 'string' ,'max'=>255],
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
            'serial_no' => 'Serial No',
            'intansit_id' => 'Intansit ID',
            'branch' => 'Branch',
            'created_at' => 'Created At',
        ];
    }
}
