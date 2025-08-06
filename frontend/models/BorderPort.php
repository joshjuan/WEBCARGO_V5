<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "border_port".
 *
 * @property int $id
 * @property string $name
 * @property int $location
 *
 * @property Location $location0
 */
class BorderPort extends \yii\db\ActiveRecord
{

    const Border=1;
    const Port=2;

    public static function getStatus()
    {
        return [
            self::Port => Yii::t('app', 'PORT'),
            self::Border => Yii::t('app', 'BORDER'),


        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'border_port';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', ], 'required'],
            [['location'], 'integer'],
            [['name'], 'string', 'max' => 200],
            [['location'], 'exist', 'skipOnError' => true, 'targetClass' => Location::className(), 'targetAttribute' => ['location' => 'id']],
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
            'location' => 'Location',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocation0()
    {
        return $this->hasOne(Location::className(), ['id' => 'location']);
    }

    public static function getBordersPorts()
    {
        return ArrayHelper::map(BorderPort::find()->where(['location'=>1])->all(),'id','name');
    }
}
