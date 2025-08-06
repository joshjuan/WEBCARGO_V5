<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "location".
 *
 * @property int $id
 * @property string $location_name
 */
class Location extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'location';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['location_name'], 'required'],
            [['location_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'location_name' => 'Location Name',
        ];
    }


    public static function getAllLocation()
    {
        return ArrayHelper::map(Location::find()->where(['in','id',[3]])->all(),'id','location_name');

       // return ArrayHelper::map(Location::find()->all(),'id','location_name');
    }

    public static function getLocation()
    {
        return ArrayHelper::map(Location::find()->all(),'id','location_name');
    }
}
