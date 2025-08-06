<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "sales_trips".
 *
 * @property int $id
 * @property int $offline_receipt_number
 * @property int $prev_offline_receipt_number
 * @property string $sale_date
 * @property string $created_at
 * @property string $tzl
 * @property string|null $start_date_time
 * @property string $vehicle_number
 * @property string|null $chasis_number
 * @property string $driver_name
 * @property int $border_id
 * @property int|null $trip_status
 * @property string $driver_number
 * @property string $serial_no
 * @property string $slaves_serial
 * @property float $amount
 * @property string|null $currency
 * @property int $gate_number
 * @property string|null $end_date
 * @property int $sales_person
 * @property string $receipt_number
 * @property string|null $passport
 * @property string|null $container_number
 * @property string|null $vehicle_type_id
 * @property string|null $customer_type_id
 * @property int|null $customer_id
 * @property string|null $company_name
 * @property string|null $customer_name
 * @property string|null $agent
 * @property int|null $cancelled_by
 * @property int|null $edited_by
 * @property string|null $edited_at
 * @property string|null $date_cancelled
 * @property int|null $sale_type
 * @property int|null $sale_id
 * @property int|null $branch
 * @property int|null $type
 * @property int|null $created_by
 * @property string|null $remarks
 * @property string|null $trailerno
 */
class SalesTrips extends \yii\db\ActiveRecord
{
    const CASH = 1;
    const BILL = 2;
    const NORMAL = 1;
    const EDITED = 2;
    const CANCELED = 3;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sales_trips';
    }

    public static function getArrayStatus()
    {
        return [
            self::CASH => Yii::t('app', 'CASH'),
            self::BILL => Yii::t('app', 'BILL'),

        ];
    }

    public static function getTripStatus()
    {
        return [
            self::NORMAL => Yii::t('app', 'NORMAL'),
            self::EDITED => Yii::t('app', 'EDITED'),
            self::CANCELED => Yii::t('app', 'CANCELED'),

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
           // [['sale_date', 'tzl', 'vehicle_number', 'driver_name', 'border_id', 'driver_number', 'serial_no', 'amount', 'gate_number', 'sales_person', 'receipt_number'], 'required'],
            [['sale_date', 'tzl', 'driver_name', 'border_id', 'driver_number', 'serial_no', 'gate_number', 'sales_person', 'receipt_number'], 'required'],
            [['sale_date', 'start_date_time', 'end_date', 'edited_at', 'date_cancelled','created_at'], 'safe'],
            [['border_id', 'trip_status', 'gate_number', 'sales_person', 'customer_id', 'cancelled_by', 'edited_by', 'sale_type', 'sale_id', 'branch', 'type', 'created_by'], 'integer'],
            [['amount'], 'number'],
            [['remarks'], 'string'],
            [['tzl', 'chasis_number', 'currency', 'customer_type_id', 'customer_name','trailerno'], 'string', 'max' => 200],
            [['vehicle_number', 'driver_name', 'driver_number', 'serial_no', 'receipt_number', 'passport', 'container_number', 'company_name', 'agent','offline_receipt_number','prev_offline_receipt_number','slaves_serial'], 'string', 'max' => 255],
            [['vehicle_type_id'], 'string', 'max' => 100],
          //  [['tzl'], 'unique'],
            ['tzl', 'unique', 'targetAttribute' => ['tzl','trip_status'],'message' => 'Sale Trip Exist'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sale_date' => 'Sale Date',
            'tzl' => 'Tzl',
            'start_date_time' => 'Start Date Time',
            'vehicle_number' => 'Vehicle Number',
            'chasis_number' => 'Chasis Number',
            'driver_name' => 'Driver Name',
            'border_id' => 'Border ID',
            'trip_status' => 'Trip Status',
            'driver_number' => 'Driver Number',
            'serial_no' => 'Serial No',
            'amount' => 'Amount',
            'currency' => 'Currency',
            'gate_number' => 'Gate Number',
            'end_date' => 'End Date',
            'sales_person' => 'Sales Person',
            'receipt_number' => 'Receipt Number',
            'passport' => 'Passport',
            'container_number' => 'Container Number',
            'vehicle_type_id' => 'Vehicle Type ID',
            'customer_type_id' => 'Customer Type ID',
            'customer_id' => 'Customer ID',
            'company_name' => 'Company Name',
            'customer_name' => 'Customer Name',
            'agent' => 'Agent',
            'cancelled_by' => 'Cancelled By',
            'edited_by' => 'Edited By',
            'edited_at' => 'Edited At',
            'date_cancelled' => 'Date Cancelled',
            'sale_type' => 'Sale Type',
            'sale_id' => 'Sale ID',
            'branch' => 'Branch',
            'type' => 'Type',
            'created_by' => 'Created By',
            'remarks' => 'Remarks',
        ];
    }

    public function getBorderPort()
    {
        return $this->hasOne(BorderPort::className(), ['id' => 'border_id']);
    }

    public function getBranch0()
    {
        return $this->hasOne(Branches::className(), ['id' => 'branch']);
    }


    public function getPort()
    {
        return $this->hasOne(BorderPort::className(), ['id' => 'gate_number']);
    }
    public function getCustomer()
    {
        return $this->hasOne(User::className(), ['id' => 'customer_id']);
    }

    public function getSalesPerson()
    {
        return $this->hasOne(User::className(), ['id' => 'sales_person']);
    }
    public function getEditedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'edited_by']);
    }
    public function getCanceledBy()
    {
        return $this->hasOne(User::className(), ['id' => 'cancelled_by']);
    }


    public function getType0()
    {
        return $this->hasOne(Devices::className(), ['serial_no' => 'serial_no']);
    }

    public function getCat0()
    {
        return $this->hasOne(DeviceCategory::className(), ['id' => 'device_category']);
    }

    public static function getAvailableTunduma()
    {
        $total = Devices::find()->where(['border_port'=>1])->andWhere(['view_status'=>Devices::in_transit])->count();
        if ($total > 0) {
            echo $total;
        } else {
            echo 0;
        }
    }

    public static function getBranchAvailableTunduma()
    {
        if (Yii::$app->user->identity->user_type == User::PARTNER) {
            $devices = Devices::find()
                ->select(['serial'])
                ->where(['partiner' => 1])
                ->asArray();
            $total = InTransit::find()
                ->where(['in','serial_no',$devices])
                ->andWhere(['border_port'=>1])
                ->andWhere(['branch'=>Yii::$app->user->identity->branch])
                ->andWhere(['view_status'=>Devices::in_transit])->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }
        else{
            $total = InTransit::find()
                ->where(['border_port'=>1])
                ->andWhere(['branch'=>Yii::$app->user->identity->branch])
                ->andWhere(['view_status'=>Devices::in_transit])->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }

    }

    public static function getAvailableKasumulu()
    {
        $total = Devices::find()->where(['border_port'=>2])->andWhere(['view_status'=>Devices::in_transit])->count();
        if ($total > 0) {
            echo $total;
        } else {
            echo 0;
        }
    }
    public static function getBranchAvailableKasumulu()
    {
        if (Yii::$app->user->identity->user_type == User::PARTNER) {
            $devices = Devices::find()
                ->select(['serial'])
                ->where(['partiner' => 1])
                ->asArray();
            $total = InTransit::find()
                ->where(['in','serial_no',$devices])
                ->andWhere(['border_port'=>2])
                ->andWhere(['branch'=>Yii::$app->user->identity->branch])
                ->andWhere(['view_status'=>Devices::in_transit])->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }
        else{
            $total = InTransit::find()
                ->where(['border_port'=>2])
                ->andWhere(['branch'=>Yii::$app->user->identity->branch])
                ->andWhere(['view_status'=>Devices::in_transit])->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }

    }

    public static function getAvailableKabanga()
    {
        $total = Devices::find()->where(['border_port'=>3])->andWhere(['view_status'=>Devices::in_transit])->count();
        if ($total > 0) {
            echo $total;
        } else {
            echo 0;
        }
    }
    public static function getBranchAvailableKabanga()
    {
        if (Yii::$app->user->identity->user_type == User::PARTNER) {
            $devices = Devices::find()
                ->select(['serial'])
                ->where(['partiner' => 1])
                ->asArray();

            $total = InTransit::find()
                ->where(['in','serial_no',$devices])
                ->andWhere(['border_port' => 3])
                ->andWhere(['branch' => Yii::$app->user->identity->branch])
                ->andWhere(['view_status' => Devices::in_transit])->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }
        else {
            $total = InTransit::find()
                ->where(['border_port' => 3])
                ->andWhere(['branch' => Yii::$app->user->identity->branch])
                ->andWhere(['view_status' => Devices::in_transit])->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }
    }

    public static function getAvailableRusumo()
    {
        $total = Devices::find()->where(['border_port'=>5])->andWhere(['view_status'=>Devices::in_transit])->count();
        if ($total > 0) {
            echo $total;
        } else {
            echo 0;
        }
    }
    public static function getBranchAvailableRusumo()
    {
        if (Yii::$app->user->identity->user_type == User::PARTNER) {
            $devices = Devices::find()
                ->select(['serial'])
                ->where(['partiner' => 1])
                ->asArray();
            $total = InTransit::find()
                ->where(['in','serial_no',$devices])
                ->andWhere(['border_port'=>5])
                ->andWhere(['branch'=>Yii::$app->user->identity->branch])
                ->andWhere(['view_status'=>Devices::in_transit])->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }else{
            $total = InTransit::find()
                ->where(['border_port'=>5])
                ->andWhere(['branch'=>Yii::$app->user->identity->branch])
                ->andWhere(['view_status'=>Devices::in_transit])->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }

    }

    public static function getAvailableMtukula()
    {
        $total = Devices::find()->where(['border_port'=>4])->andWhere(['view_status'=>Devices::in_transit])->count();
        if ($total > 0) {
            echo $total;
        } else {
            echo 0;
        }
    }

    public static function getBranchAvailableMtukula()
    {
        if (Yii::$app->user->identity->user_type == User::PARTNER) {
            $devices = Devices::find()
                ->select(['serial'])
                ->where(['partiner' => 1])
                ->asArray();
            $total = InTransit::find()
                ->where(['in','serial_no',$devices])
                ->andWhere(['border_port'=>4])
                ->andWhere(['branch'=>Yii::$app->user->identity->branch])
                ->andWhere(['view_status'=>Devices::in_transit])->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }
        else{
            $total = InTransit::find()
                ->where(['border_port'=>4])
                ->andWhere(['branch'=>Yii::$app->user->identity->branch])
                ->andWhere(['view_status'=>Devices::in_transit])->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }

    }
}
