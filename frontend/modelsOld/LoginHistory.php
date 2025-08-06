<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "login_history".
 *
 * @property int $id
 * @property int $user_id
 * @property string $ip_address
 * @property string $datetime
 */
class LoginHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'login_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'ip_address', 'datetime'], 'required'],
            [['user_id'], 'integer'],
            [['datetime'], 'safe'],
            [['ip_address'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'ip_address' => 'Ip Address',
            'datetime' => 'Datetime',
        ];
    }
}
