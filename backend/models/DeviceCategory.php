<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "device_category".
 *
 * @property int $id
 * @property string $name
 * @property string $bland
 * @property string $created_at
 * @property int $created_by
 */
class DeviceCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'device_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'bland', 'created_at', 'created_by'], 'required'],
            [['created_at'], 'safe'],
            [['created_by'], 'integer'],
            [['name'], 'string', 'max' => 200],
            [['bland'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'bland' => 'Bland',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    public static function getAllCategory()
    {
        return ArrayHelper::map(DeviceCategory::find()
            ->all(),'id','name');
    }
}
