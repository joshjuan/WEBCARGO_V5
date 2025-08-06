<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "in_transit".
 *
 * @property int $id
 * @property int $serial_no
 * @property string|null $tzl
 * @property int|null $border
 * @property int|null $sales_person
 * @property string|null $created_at
 * @property int|null $created_by
 * @property string|null $vehicle_no
 * @property string|null $container_number
 * @property int|null $view_status
 * @property int|null $branch
 * @property int|null $sale_id
 * @property int|null $type
 */
class InTransit extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'in_transit';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
          //  [['serial_no'], 'required'],
            [['border', 'sales_person', 'created_by', 'view_status', 'branch', 'sale_id', 'type'], 'integer'],
            [['created_at'], 'safe'],
            [['serial_no'], 'string' ,'max'=>255],
            [['tzl', 'vehicle_no', 'container_number'], 'string', 'max' => 200],
            [['serial_no'], 'unique'],
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
            'tzl' => 'Tzl',
            'border' => 'Border',
            'sales_person' => 'Sales Person',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'vehicle_no' => 'Vehicle No',
            'container_number' => 'Container Number',
            'view_status' => 'View Status',
            'branch' => 'Branch',
            'sale_id' => 'Sale ID',
            'type' => 'Type',
        ];
    }


    public function getBorderPort()
    {
        return $this->hasOne(BorderPort::className(), ['id' => 'border']);
    }


    public function getReleased0()
    {
        return $this->hasOne(User::className(), ['id' => 'sales_person']);
    }

    public function getCreatedBy0()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getType0()
    {
        return $this->hasOne(Devices::className(), ['serial' => 'serial_no']);
    }

    public static function LongTrips()
    {

        $date_today = strtotime("-7 day");
        $date_today = date('Y-m-d', $date_today);

        $device=InTransit::find()->where(['<','date(created_at)',$date_today])->andWhere(['view_status'=>Devices::in_transit])->count();

        if ($device > 0) {
            echo $device;
        } else {
            echo 0;
        }
    }


    public static function getOnRoad()
    {
        if (Yii::$app->user->identity->user_type == User::PARTNER) {
            $devices = Devices::find()
                ->select(['serial'])
                ->where(['partiner' => 1])
                ->asArray();

            $total = InTransit::find()
                ->where(['in','serial_no',$devices])
                ->andWhere(['branch' => Yii::$app->user->identity->branch])
                ->andWhere(['view_status' => Devices::in_transit])
                ->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }
        else {
            $total = InTransit::find()
                ->where(['branch' => Yii::$app->user->identity->branch])
                ->andWhere(['view_status' => Devices::in_transit])
                ->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }
    }
}
