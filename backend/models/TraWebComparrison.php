<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "tra_web_comparrison".
 *
 * @property int $id
 * @property string|null $serial_no
 * @property string|null $tzdl
 * @property string|null $sale_dates
 * @property int|null $tra_count
 * @property int|null $web_count
 * @property int|null $count_status
 * @property int|null $compared_by
 * @property string|null $datetime
 * @property int|null $status
 * @property int|null $route_id
 * @property int|null $branch
 */
class TraWebComparrison extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tra_web_comparrison';
    }


    const BOTH=1;
    const TRA=2;
    const WEB=3;
    const NO=4;

    public static function getArray()
    {
        return [
            self::BOTH => Yii::t('app', 'BOTH'),
            self::TRA => Yii::t('app', 'TRA'),
            self::WEB => Yii::t('app', 'WEB'),
            self::NO => Yii::t('app', 'NO'),
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tzdl', 'sale_dates'], 'string'],
            [['tra_count', 'web_count', 'count_status', 'compared_by', 'status', 'route_id', 'branch'], 'integer'],
            [['datetime'], 'safe'],
            [['serial_no'], 'string', 'max' => 200],
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
            'tzdl' => 'Tzdl',
            'sale_dates' => 'Sale Dates',
            'tra_count' => 'Tra Count',
            'web_count' => 'Web Count',
            'count_status' => 'Count Status',
            'compared_by' => 'Compared By',
            'datetime' => 'Datetime',
            'status' => 'Status',
            'route_id' => 'Route ID',
            'branch' => 'Branch',
        ];
    }
}
