<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "in_transit_slave_report".
 *
 * @property int $id
 * @property int|null $serial_no
 * @property int|null $intansit_id
 * @property string $created_at
 * @property int|null $branch
 */
class InTransitSlaveReport extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'in_transit_slave_report';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
         //   [['serial_no', 'intansit_id', 'branch'], 'integer'],
            [['created_at'], 'required'],
            [['created_at'], 'safe'],
            [['serial_no'], 'string' ,'max'=>255],
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
            'created_at' => 'Created At',
            'branch' => 'Branch',
        ];
    }
}
