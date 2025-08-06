<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "border_port_user".
 *
 * @property int $id
 * @property int $border_port
 * @property string $name
 * @property string $assigned_date
 * @property int $assigned_by
 * @property int $branch
 */
class BorderPortUser extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'border_port_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['border_port', 'name', 'assigned_date', 'assigned_by'], 'required'],
            [['border_port', 'assigned_by','branch'], 'integer'],
            [['assigned_date'], 'safe'],
            [['name'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'border_port' => 'Border Port',
            'name' => 'Name',
            'assigned_date' => 'Assigned Date',
            'assigned_by' => 'Assigned By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBorderPort()
    {
        return $this->hasOne(BorderPort::className(), ['id' => 'border_port']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAssignedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'assigned_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAssigned()
    {
        return $this->hasOne(User::className(), ['id' => 'name']);
    }
}
