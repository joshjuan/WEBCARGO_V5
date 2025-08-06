<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "devices".
 *
 * @property int $id
 * @property string $serial_no
 * @property string|null $sim_card
 * @property int $received_from
 * @property int|null $border_port
 * @property int|null $received_from_staff
 * @property string|null $received_at
 * @property string|null $remark
 * @property int $created_by
 * @property string $created_at
 * @property int|null $status
 * @property int|null $branch
 * @property int|null $type
 * @property int $view_status
 * @property int $received_by
 * @property int $device_category
 * @property int $partiner
 *
 * @property User $createdBy
 */
class Devices extends \yii\db\ActiveRecord
{


    const awaiting_receive = 1;
    const received_devices = 2;
    const stock_devices = 3;
    const released_devices = 4;
    const in_transit = 5;
    const fault_devices = 6;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'devices';
    }

    public static function getBranchActive()
    {
        if (Yii::$app->user->identity->user_type == User::PARTNER) {
            $devices = Devices::find()
                ->select(['serial_no'])
                ->where(['partiner' => 1])
                ->andWhere(['view_status' => Devices::awaiting_receive])
                ->count();
            if ($devices > 0) {
                echo $devices;
            } else {
                echo 0;
            }
        }
        else {

            $total = Devices::find()
                ->where(['view_status' => Devices::awaiting_receive])
                ->andWhere(['branch' => Yii::$app->user->identity->branch])
                ->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }
    }

    public static function getBranchAvailableNotDeactivated()
    {
        if (Yii::$app->user->identity->user_type == User::PARTNER) {
            $total = Devices::find()
                ->where(['partiner' => 1])
                ->andWhere(['status' => StockDevices::not_deactivated])
                ->andWhere(['branch' => Yii::$app->user->identity->branch])
                ->andWhere(['view_status' => Devices::stock_devices])
                ->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }
        else {
            $total = Devices::find()
                ->where(['status' => StockDevices::not_deactivated])
                ->andWhere(['branch' => Yii::$app->user->identity->branch])
                ->andWhere(['view_status' => Devices::stock_devices])
                ->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }
    }

    public static function getBranchStock()
    {
        if (Yii::$app->user->identity->user_type == User::PARTNER) {
            $total = Devices::find()
                ->select(['serial_no'])
                ->where(['partiner' => 1])
                ->count();

            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }
        else{

            $total = Devices::find()
                ->where(['branch'=>Yii::$app->user->identity->branch])
                ->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }

    }

    public static function getBranchRegisteredDevices()
    {
        $total = Devices::find()
            ->select(['serial_no'])
            ->where(['branch' => Yii::$app->user->identity->getId()])
            ->count();

        if ($total > 0) {
            echo $total;
        } else {
            echo 0;
        }

    }
    public static function getBranchAvailableKasumulu()
    {
        if (Yii::$app->user->identity->user_type == User::PARTNER) {
            $total = Devices::find()
                ->select(['serial_no'])
                ->where(['partiner' => 1])
                ->andWhere(['border_port'=>2])
                ->andWhere(['view_status'=>Devices::in_transit])
                ->count();

            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }
        elseif (Yii::$app->user->identity->user_type == User::BILL_STAFF) {

            $query_date = date('Y-m-d');
            $firstDay= date('Y-m-01', strtotime($query_date));
            $lastDay= date('Y-m-t', strtotime($query_date));
            $total = SalesTrips::find()
                ->select(['serial_no'])
                ->where(['border_id'=>2])
                ->andWhere(['trip_status'=>SalesTrips::NORMAL])
                  ->andWhere(['customer_id'=>\Yii::$app->user->identity->getId()])
                ->andWhere(['between','date(sale_date)',$firstDay,$lastDay])
                ->count();

            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }
        else{
            $total = Devices::find()
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

    public static function getBranchAvailableTunduma()
    {
        if (Yii::$app->user->identity->user_type == User::PARTNER) {

            $total = Devices::find()
                ->where(['partiner'=>1])
                ->andWhere(['border_port'=>1])
              //  ->andWhere(['branch'=>Yii::$app->user->identity->branch])
                ->andWhere(['view_status'=>Devices::in_transit])
                ->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }
        elseif (Yii::$app->user->identity->user_type == User::BILL_STAFF) {

            $query_date = date('Y-m-d');
            $firstDay= date('Y-m-01', strtotime($query_date));
            $lastDay= date('Y-m-t', strtotime($query_date));
            $total = SalesTrips::find()
                ->select(['serial_no'])
                ->where(['border_id'=>1])
                ->andWhere(['trip_status'=>SalesTrips::NORMAL])
                ->andWhere(['customer_id'=>\Yii::$app->user->identity->getId()])
                ->andWhere(['between','date(sale_date)',$firstDay,$lastDay])
                ->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }

        else{
            $total = Devices::find()
                ->where(['border_port'=>1])
                ->andWhere(['branch'=>Yii::$app->user->identity->branch])
                ->andWhere(['view_status'=>Devices::in_transit])
                ->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }

    }

    public static function getBranchAvailableKabanga()
    {
        if (Yii::$app->user->identity->user_type == User::PARTNER) {

            $total = Devices::find()
                ->where(['partiner' => 1])
                ->andWhere(['border_port' => 3])
                ->andWhere(['branch' => Yii::$app->user->identity->branch])
                ->andWhere(['view_status' => Devices::in_transit])->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }
        elseif (Yii::$app->user->identity->user_type == User::BILL_STAFF) {

            $query_date = date('Y-m-d');
            $firstDay= date('Y-m-01', strtotime($query_date));
            $lastDay= date('Y-m-t', strtotime($query_date));
            $total = SalesTrips::find()
                ->select(['serial_no'])
                ->where(['border_id'=>3])
                ->andWhere(['trip_status'=>SalesTrips::NORMAL])
                ->andWhere(['customer_id'=>\Yii::$app->user->identity->getId()])
                ->andWhere(['between','date(sale_date)',$firstDay,$lastDay])
                ->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }
        else {
            $total = Devices::find()
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

    public static function getBranchAvailableRusumo()
    {
        if (Yii::$app->user->identity->user_type == User::PARTNER) {
            $total = Devices::find()
                ->where(['partiner' => 1])
                ->andWhere(['border_port'=>5])
                ->andWhere(['branch'=>Yii::$app->user->identity->branch])
                ->andWhere(['view_status'=>Devices::in_transit])->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }
        elseif (Yii::$app->user->identity->user_type == User::BILL_STAFF) {
            $query_date = date('Y-m-d');
            $firstDay= date('Y-m-01', strtotime($query_date));
            $lastDay= date('Y-m-t', strtotime($query_date));
            $total = SalesTrips::find()
                ->select(['serial_no'])
                ->where(['border_id'=>5])
                ->andWhere(['trip_status'=>SalesTrips::NORMAL])
                ->andWhere(['customer_id'=>\Yii::$app->user->identity->getId()])
                ->andWhere(['between','date(sale_date)',$firstDay,$lastDay])
                ->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }
        else{
            $total = Devices::find()
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

    public static function getBranchAvailableMtukula()
    {
        if (Yii::$app->user->identity->user_type == User::PARTNER) {

            $total = Devices::find()
                ->where(['partiner' => 1])
                ->andWhere(['border_port'=>4])
                ->andWhere(['branch'=>Yii::$app->user->identity->branch])
                ->andWhere(['view_status'=>Devices::in_transit])->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }

        elseif (Yii::$app->user->identity->user_type == User::BILL_STAFF) {

            $query_date = date('Y-m-d');
            $firstDay= date('Y-m-01', strtotime($query_date));
            $lastDay= date('Y-m-t', strtotime($query_date));
            $total = SalesTrips::find()
                ->select(['serial_no'])
                ->where(['border_id'=>2])
                ->andWhere(['trip_status'=>SalesTrips::NORMAL])
                ->andWhere(['customer_id'=>\Yii::$app->user->identity->getId()])
                ->andWhere(['between','date(sale_date)',$firstDay,$lastDay])
                ->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }

        else{
            $total = Devices::find()
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
    public static function getBranchAvailableKigoma()
    {
        if (Yii::$app->user->identity->user_type == User::PARTNER) {

            $total = Devices::find()
                ->where(['partiner' => 1])
                ->andWhere(['border_port'=>26])
                ->andWhere(['branch'=>Yii::$app->user->identity->branch])
                ->andWhere(['view_status'=>Devices::in_transit])->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }

        elseif (Yii::$app->user->identity->user_type == User::BILL_STAFF) {

            $query_date = date('Y-m-d');
            $firstDay= date('Y-m-01', strtotime($query_date));
            $lastDay= date('Y-m-t', strtotime($query_date));
            $total = SalesTrips::find()
                ->select(['serial_no'])
                ->where(['border_id'=>26])
                ->andWhere(['trip_status'=>SalesTrips::NORMAL])
                ->andWhere(['customer_id'=>\Yii::$app->user->identity->getId()])
                ->andWhere(['between','date(sale_date)',$firstDay,$lastDay])
                ->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }

        else{
            $total = Devices::find()
                ->where(['border_port'=>26])
                ->andWhere(['branch'=>Yii::$app->user->identity->branch])
                ->andWhere(['view_status'=>Devices::in_transit])->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }

    }

    public static function getBranchAvailableKigamboni()
    {
        $total = Devices::find()
            ->where(['border_port'=>31])
            ->andWhere(['branch'=>Yii::$app->user->identity->branch])
            ->count();
        if ($total > 0) {
            echo $total;
        } else {
            echo 0;
        }
    }

    public static function getBranchAvailableKurasini()
    {
        $total = Devices::find()
            ->where(['border_port'=>32])
            ->andWhere(['branch'=>Yii::$app->user->identity->branch])
            ->count();
        if ($total > 0) {
            echo $total;
        } else {
            echo 0;
        }
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['serial_no'], 'required'],
            [['received_from', 'border_port', 'received_from_staff', 'received_status', 'received_by', 'created_by',
                'status', 'branch', 'type', 'released_by', 'released_to', 'transferred_from','device_category',
                'transferred_to', 'transferred_by', 'sales_person', 'sale_id', 'view_status','partiner'], 'integer'],
            [['received_at', 'created_at', 'transferred_date', 'released_date'], 'safe'],
            [['remark'], 'string'],
            [['serial_no'], 'safe'],
            [[ 'sim_card', 'tzl', 'vehicle_no', 'container_number'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
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
            'sim_card' => 'Sim Card',
            'received_from' => 'Received From',
            'border_port' => 'Border Port',
            'received_from_staff' => 'Received From Staff',
            'received_at' => 'Received At',
            'remark' => 'Remark',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'status' => 'Status',
            'branch' => 'Branch',
            'type' => 'Type',
            'view_status' => 'View Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFaultDevices()
    {
        return $this->hasMany(FaultDevices::className(), ['device_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockDevices()
    {
        return $this->hasMany(StockDevices::className(), ['serial_no' => 'serial_no']);
    }

    public function getBranch0()
    {
        return $this->hasOne(Branches::className(), ['id' => 'branch']);
    }

    public function getType0()
    {
        return $this->hasOne(DeviceTypes::className(), ['id' => 'type']);
    }

    public function getCategory0()
    {
        return $this->hasOne(DeviceCategory::className(), ['id' => 'device_category']);
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
    public function getLocation()
    {
        return $this->hasOne(Location::className(), ['id' => 'received_from']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceivedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'received_by']);
    }

    public static function getAvailable()
    {
        $total = Devices::find()->count();
        if ($total > 0) {
            echo $total;
        } else {
            echo 0;
        }
    }


    public static function getActive()
    {
        $awaiting = Devices::find()->where(['view_status'=>Devices::awaiting_receive])->count();
        $received = Devices::find()->where(['view_status'=>Devices::received_devices])->count();
        $stock = Devices::find()->where(['view_status'=>Devices::stock_devices])->count();
        $released = Devices::find()->where(['view_status'=>Devices::released_devices])->count();
        $intransit = Devices::find()->where(['view_status'=>Devices::in_transit])->count();

        $total=$awaiting +$received +$stock +$released +$intransit ;
        if ($total > 0) {
            echo $total;
        } else {
            echo 0;
        }
    }

    public function getReleased0()
    {
        return $this->hasOne(User::className(), ['id' => 'released_by']);
    }

    public function getSales0()
    {
        return $this->hasOne(User::className(), ['id' => 'sales_person']);
    }

    public function getReleasedTo0()
    {
        return $this->hasOne(User::className(), ['id' => 'released_to']);
    }

    public function getTransferred0()
    {
        return $this->hasOne(User::className(), ['id' => 'transferred_from']);
    }

    public function getTransferredTo0()
    {
        return $this->hasOne(User::className(), ['id' => 'transferred_to']);
    }

    public function getTransferredBy()
    {
        return $this->hasOne(User::className(), ['id' => 'transferred_by']);
    }



    public static function getBranchAvailable()
    {
        $total = Devices::find()
            ->where(['view_status'=>self::stock_devices])
            ->andWhere(['branch'=>Yii::$app->user->identity->branch])
            ->andWhere(['status'=>StockDevices::available])
            ->count();
        if ($total > 0) {
            echo $total;
        } else {
            echo 0;
        }
    }

    public static function getBranchReleased()
    {
        if (Yii::$app->user->identity->user_type == User::PARTNER) {
            $devices = Devices::find()
                ->where(['partiner' => 1])
                ->andWhere(['view_status' => Devices::released_devices])
                ->count();
            if ($devices > 0) {
                echo $devices;
            } else {
                echo 0;
            }
        }
        else {

            $total = Devices::find()
                ->where(['view_status' => self::released_devices])
                ->andWhere(['branch' => Yii::$app->user->identity->branch])
                ->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }
    }

    public static function getBranchFault()
    {
        $total = Devices::find()
            ->where(['view_status'=>self::fault_devices])
            ->andWhere(['branch'=>Yii::$app->user->identity->branch])
            ->count();
        if ($total > 0) {
            echo $total;
        } else {
            echo 0;
        }
    }

    public static function getAvailableGateTwo()
    {
        $total = Devices::find()->where(['border_port'=>12])->count();
        if ($total > 0) {
            echo $total;
        } else {
            echo 0;
        }
    }

    public static function getBranchAvailableGateTwo()
    {
        $total = Devices::find()
            ->where(['border_port'=>12])
            ->andWhere(['view_status'=>self::released_devices])
            ->andWhere(['branch'=>Yii::$app->user->identity->branch])
            ->count();
        if ($total > 0) {
            echo $total;
        } else {
            echo 0;
        }
    }

    public static function getAvailableGateThree()
    {
        $total = Devices::find()->where(['border_port'=>13])->andWhere(['view_status'=>Devices::released_devices])->count();
        if ($total > 0) {
            echo $total;
        } else {
            echo 0;
        }
    }

    public static function getBranchAvailableGateThree()
    {
        $total = Devices::find()
            ->where(['border_port'=>13])
            ->andWhere(['branch'=>Yii::$app->user->identity->branch])
            ->andWhere(['view_status'=>Devices::released_devices])->count();
        if ($total > 0) {
            echo $total;
        } else {
            echo 0;
        }
    }

    public static function getAvailableGateFive()
    {
        $total = Devices::find()->where(['border_port'=>14])->andWhere(['view_status'=>Devices::released_devices])->count();
        if ($total > 0) {
            echo $total;
        } else {
            echo 0;
        }
    }

    public static function getBranchAvailableGateFive()
    {
        $total = Devices::find()
            ->where(['border_port'=>14])
            ->andWhere(['branch'=>Yii::$app->user->identity->branch])
            ->andWhere(['view_status'=>Devices::released_devices])
            ->count();
        if ($total > 0) {
            echo $total;
        } else {
            echo 0;
        }
    }
    public static function getAvailableGateMalawi()
    {
        $total = Devices::find()->where(['border_port'=>15])->andWhere(['view_status'=>Devices::released_devices])->count();
        if ($total > 0) {
            echo $total;
        } else {
            echo 0;
        }
    }
    public static function getBranchAvailableGateMalawi()
    {
        $total = Devices::find()
            ->where(['border_port'=>17])
            ->andWhere(['branch'=>Yii::$app->user->identity->branch])
            ->andWhere(['view_status'=>Devices::released_devices])->count();
        if ($total > 0) {
            echo $total;
        } else {
            echo 0;
        }
    }

    public static function getAvailableGateCargo()
    {
        $total = Devices::find()->andWhere(['view_status'=>Devices::released_devices])->count();
        if ($total > 0) {
            echo $total;
        } else {
            echo 0;
        }
    }

    public static function getAvailableGateKicd()
    {
        $total = Devices::find()->where(['border_port'=>16])->andWhere(['view_status'=>Devices::released_devices])->count();
        if ($total > 0) {
            echo $total;
        } else {
            echo 0;
        }
    }

    public static function getBranchAvailableGateKicd()
    {
        $total = Devices::find()
            ->where(['border_port'=>16])
            ->andWhere(['branch'=>Yii::$app->user->identity->branch])
            ->andWhere(['view_status'=>Devices::released_devices])->count();
        if ($total > 0) {
            echo $total;
        } else {
            echo 0;
        }
    }



    public function getCreatedBy0()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }


    public static function LongTrips()
    {

        $date_today = strtotime("-7 day");
        $date_today = date('Y-m-d', $date_today);

        $device=Devices::find()->where(['<','date(created_at)',$date_today])->andWhere(['view_status'=>Devices::in_transit])->count();

        if ($device > 0) {
            echo $device;
        } else {
            echo 0;
        }
    }


    public static function getOnRoad()
    {
        if (Yii::$app->user->identity->user_type == User::PARTNER) {

            $total = Devices::find()
                //->where(['in','serial_no',$devices])
                ->where(['partiner' => 1])
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
            $total = Devices::find()
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


    public static function getReceived()
    {
        if (Yii::$app->user->identity->user_type == User::PARTNER) {

            $total = Devices::find()
                //->where(['in','serial_no',$devices])
                ->where(['branch' => Yii::$app->user->identity->branch])
                ->andWhere(['view_status' => Devices::received_devices])
                ->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }
        else {
            $total = Devices::find()
                ->where(['branch' => Yii::$app->user->identity->branch])
                ->andWhere(['view_status' => Devices::received_devices])
                ->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }
    }



}
