<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "device_logs".
 *
 * @property int $id
 * @property string $data
 * @property string $datatime
 * @property int|null $user
 * @property string|null $tzdl
 */
class DeviceLogs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'device_logs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['data', 'datatime'], 'required'],
            [['data'], 'string'],
            [['datatime'], 'safe'],
            [['user'], 'integer'],
            [['tzdl'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'data' => 'Data',
            'datatime' => 'Datatime',
            'user' => 'User',
            'tzdl' => 'Tzdl',
        ];
    }
}
