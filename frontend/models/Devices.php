<?php

namespace frontend\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "devices".
 *
 * @property int $id
 * @property string $serial_no
 * @property string|null $sim_card
 * @property int $received_from
 * @property int|null $border_port
 * @property int|null $received_from_staff
 * @property string|null $remark
 * @property int $created_by
 * @property int $device_from
 * @property int $stock_status,
 * @property string $created_at
 * @property int|null $status
 * @property int|null $branch
 * @property int|null $type
 * @property int $view_status
 * @property int $device_category
 * @property int $partiner
 *
 * @property User $createdBy
 */
class Devices extends \yii\db\ActiveRecord
{


    const registration = 1;
    const accounts = 2;
    const new_items = 3;
    const awaiting_store = 4;
    const store = 5;
    const awaiting_allocation = 6;
    // const //allocation = 7;
    // const //awaiting_released = 8;
    const released = 7;
    const on_road = 8;
    const in_transit = 9;
    const border_received = 10;
    const fault_devices = 11;
    const damaged = 12;
    const return_to_office = 13;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'devices';
    }

//    public static function getBranchActive()
//    {
//        if (Yii::$app->user->identity->user_type == User::PARTNER) {
//            $devices = Devices::find()
//                ->select(['serial_no'])
//                ->where(['partiner' => 1])
//                ->andWhere(['view_status' => Devices::awaiting_receive])
//                ->count();
//            if ($devices > 0) {
//                echo $devices;
//            } else {
//                echo 0;
//            }
//        } else {
//
//            $total = Devices::find()
//                ->where(['view_status' => Devices::awaiting_receive])
//                ->andWhere(['branch' => Yii::$app->user->identity->branch])
//                ->count();
//            if ($total > 0) {
//                echo $total;
//            } else {
//                echo 0;
//            }
//        }
//    }
//
//    public static function getAvailableNotDeactivated()
//    {
//        if (Yii::$app->user->identity->user_type == User::PARTNER) {
//            $total = Devices::find()
//                ->where(['partiner' => 1])
//                ->andWhere(['status' => StockDevices::not_deactivated])
//                // ->andWhere(['branch' => Yii::$app->user->identity->branch])
//                ->andWhere(['view_status' => Devices::stock_devices])
//                ->count();
//            if ($total > 0) {
//                echo $total;
//            } else {
//                echo 0;
//            }
//        } else {
//            $total = Devices::find()
//                ->where(['status' => StockDevices::not_deactivated])
//                ->andWhere(['branch' => Yii::$app->user->identity->branch])
//                ->andWhere(['view_status' => Devices::stock_devices])
//                ->count();
//            if ($total > 0) {
//                echo $total;
//            } else {
//                echo 0;
//            }
//        }
//    }
//
//    public static function getStock()
//    {
//
//        $total = Devices::find()
//            //    ->where(['branch'=>Yii::$app->user->identity->branch])
//            ->count();
//        if ($total > 0) {
//            echo $total;
//        } else {
//            echo 0;
//        }
//
//    }


    public static function getBranchRegisteredDevices()
    {

        if (Yii::$app->user->identity->branch ==1){
            $queryBranches = Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0]);

// Query to retrieve the main branch with id = 1
            $queryMainBranch = Branches::find()
                ->select(['id'])
                ->where(['id' => 1]);

// Combine the two queries using the union() method
            $branches = (new Query())
                ->select(['id'])
                ->from(['u' => $queryBranches->union($queryMainBranch)]);

            $total = Devices::find()
                ->select(['serial_no'])
                ->where(['in','branch',$branches])
                ->count();

            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }
        else{

            $total = Devices::find()
                ->select(['serial_no'])
                ->where(['branch'=>Yii::$app->user->identity->branch])
                ->count();

            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }


        }



    }

    public static function getBranchAvailableKasumulu()
    {
        if (Yii::$app->user->identity->user_type == User::PARTNER) {
            $total = Devices::find()
                ->select(['serial_no'])
                ->where(['partiner' => 1])
                ->andWhere(['border_port' => 2])
                ->andWhere(['view_status' => Devices::in_transit])
                ->count();

            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        } else {
            $total = Devices::find()
                ->where(['border_port' => 2])
                ->andWhere(['branch' => Yii::$app->user->identity->branch])
                ->andWhere(['view_status' => Devices::in_transit])->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }

    }

    public static function getBranchDevicesInOperation()
    {
        if (Yii::$app->user->identity->branch ==1){
            $queryBranches = Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0]);

// Query to retrieve the main branch with id = 1
            $queryMainBranch = Branches::find()
                ->select(['id'])
                ->where(['id' => 1]);

// Combine the two queries using the union() method
            $branches = (new Query())
                ->select(['id'])
                ->from(['u' => $queryBranches->union($queryMainBranch)]);


            $total = Devices::find()
                ->where(['in','branch',$branches])
                ->andWhere(['not in', 'view_status', [Devices::fault_devices, Devices::damaged,Devices::registration]])
                ->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }
        else{

            $total = Devices::find()
                ->where(['branch' => Yii::$app->user->identity->branch])
                ->andWhere(['not in', 'view_status', [Devices::fault_devices, Devices::damaged,Devices::registration]])
                ->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }

        }

    }


    public static function getBranchFaultDevices()
    {

        if (Yii::$app->user->identity->branch ==1){
            $queryBranches = Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0]);

// Query to retrieve the main branch with id = 1
            $queryMainBranch = Branches::find()
                ->select(['id'])
                ->where(['id' => 1]);

// Combine the two queries using the union() method
            $branches = (new Query())
                ->select(['id'])
                ->from(['u' => $queryBranches->union($queryMainBranch)]);

            $total = Devices::find()
                ->where(['in','branch',$branches])
                ->andWhere(['in', 'view_status', [Devices::fault_devices]])
                ->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }
        else{

            $total = Devices::find()
                ->where(['branch' => Yii::$app->user->identity->branch])
                ->andWhere(['in', 'view_status', [Devices::fault_devices]])
                ->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }



    }
    public static function getBranchDamagedDevices()
    {

        if (Yii::$app->user->identity->branch ==1){
            $queryBranches = Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0]);

// Query to retrieve the main branch with id = 1
            $queryMainBranch = Branches::find()
                ->select(['id'])
                ->where(['id' => 1]);

// Combine the two queries using the union() method
            $branches = (new Query())
                ->select(['id'])
                ->from(['u' => $queryBranches->union($queryMainBranch)]);

            $total = Devices::find()
                ->where(['in','branch',$branches])
                ->andWhere(['in', 'view_status', [Devices::damaged]])
                ->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }
        else{

            $total = Devices::find()
                ->where(['branch' => Yii::$app->user->identity->branch])
                ->andWhere(['in', 'view_status', [Devices::damaged]])
                ->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }



    }
    public static function getBranchIntransitDevices()
    {

        if (Yii::$app->user->identity->branch ==1){
            $queryBranches = Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0]);

// Query to retrieve the main branch with id = 1
            $queryMainBranch = Branches::find()
                ->select(['id'])
                ->where(['id' => 1]);

// Combine the two queries using the union() method
            $branches = (new Query())
                ->select(['id'])
                ->from(['u' => $queryBranches->union($queryMainBranch)]);

            $total = Devices::find()
                ->where(['in','branch',$branches])
                ->andWhere(['in', 'view_status', [Devices::in_transit]])
                ->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }
        else{

            $total = Devices::find()
                ->where(['branch' => Yii::$app->user->identity->branch])
                ->andWhere(['in', 'view_status', [Devices::in_transit]])
                ->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }



    }

    public static function getDevicesMoreThan7Days()
    {

        if (Yii::$app->user->identity->branch ==1) {
            $queryBranches = Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0]);

// Query to retrieve the main branch with id = 1
            $queryMainBranch = Branches::find()
                ->select(['id'])
                ->where(['id' => 1]);

// Combine the two queries using the union() method
            $branches = (new Query())
                ->select(['id'])
                ->from(['u' => $queryBranches->union($queryMainBranch)]);


            $date=date('Y-m-d');
            $endDate = date("Y-m-d", strtotime("$date -7 day"));

            $total = Devices::find()
                ->where(['view_status' => self::on_road])
                ->andWhere(['in','branch',$branches])
                ->andWhere(['<', 'date(created_at)', $endDate])
                ->count();

            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }

        }
        else{

            $date=date('Y-m-d');
            $endDate = date("Y-m-d", strtotime("$date -7 day"));

            $total = Devices::find()
                ->where(['view_status' => self::on_road])
                ->andWhere(['branch' =>Yii::$app->user->identity->branch])
                ->andWhere(['<', 'date(created_at)', $endDate])
                ->count();

            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }

        }


    }
    public static function getDevicesMoreThan3DaysIntransit()
    {

        if (Yii::$app->user->identity->branch ==1) {
            $queryBranches = Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0]);

// Query to retrieve the main branch with id = 1
            $queryMainBranch = Branches::find()
                ->select(['id'])
                ->where(['id' => 1]);

// Combine the two queries using the union() method
            $branches = (new Query())
                ->select(['id'])
                ->from(['u' => $queryBranches->union($queryMainBranch)]);


            $date=date('Y-m-d');
            $endDate = date("Y-m-d", strtotime("$date -3 day"));

            $total = Devices::find()
                ->where(['view_status' => self::in_transit])
                ->andWhere(['in','branch',$branches])
                ->andWhere(['<', 'date(created_at)', $endDate])
                ->count();

            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }

        }
        else{

            $date=date('Y-m-d');
            $endDate = date("Y-m-d", strtotime("$date -3 day"));

            $total = Devices::find()
                ->where(['view_status' => self::in_transit])
                ->andWhere(['branch' =>Yii::$app->user->identity->branch])
                ->andWhere(['<', 'date(created_at)', $endDate])
                ->count();

            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }

        }


    }
    public static function getDevices1To7Days()
    {

        if (Yii::$app->user->identity->branch ==1){
            $queryBranches = Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0]);

// Query to retrieve the main branch with id = 1
            $queryMainBranch = Branches::find()
                ->select(['id'])
                ->where(['id' => 1]);

// Combine the two queries using the union() method
            $branches = (new Query())
                ->select(['id'])
                ->from(['u' => $queryBranches->union($queryMainBranch)]);

            $date=date('Y-m-d');
            $endDate = date("Y-m-d", strtotime("$date -7 day"));


            $total = Devices::find()
                ->select(['serial_no'])
                ->where(['view_status' => self::on_road])
                ->andWhere(['in','branch', $branches])
                ->andWhere(['>', 'date(created_at)', $endDate])
                ->count();

            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }

        }
        else{

            $date=date('Y-m-d');
            $endDate = date("Y-m-d", strtotime("$date -7 day"));

            $total = Devices::find()
                ->select(['serial_no'])
                ->where(['view_status' => self::on_road])
                ->andWhere(['branch' =>Yii::$app->user->identity->branch])
                ->andWhere(['>', 'date(created_at)', $endDate])
                ->count();

            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }


    }
    public static function getDevices8To14Days()
    {

        if (Yii::$app->user->identity->branch ==1) {
            $queryBranches = Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0]);

// Query to retrieve the main branch with id = 1
            $queryMainBranch = Branches::find()
                ->select(['id'])
                ->where(['id' => 1]);

// Combine the two queries using the union() method
            $branches = (new Query())
                ->select(['id'])
                ->from(['u' => $queryBranches->union($queryMainBranch)]);


            $date = date('Y-m-d');
            $endDate = date("Y-m-d", strtotime("$date -8 day"));
            $startDate = date("Y-m-d", strtotime("$date -14 day"));


            $total = Devices::find()
                ->where(['view_status' => self::on_road])
                ->andWhere(['in','branch',$branches])
                ->andWhere(['between', 'date(created_at)', $startDate, $endDate])
                ->count();

            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }

        }
        else {


            $date = date('Y-m-d');
            $endDate = date("Y-m-d", strtotime("$date -8 day"));
            $startDate = date("Y-m-d", strtotime("$date -14 day"));


            $total = Devices::find()
                ->where(['view_status' => self::on_road])
                ->andWhere(['branch' => Yii::$app->user->identity->branch])
                ->andWhere(['between', 'date(created_at)', $startDate, $endDate])
                ->count();

            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }
    }
    public static function getDevices14DaysAndAbove()
    {
        if (Yii::$app->user->identity->branch ==1) {
            $queryBranches = Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0]);

// Query to retrieve the main branch with id = 1
            $queryMainBranch = Branches::find()
                ->select(['id'])
                ->where(['id' => 1]);

// Combine the two queries using the union() method
            $branches = (new Query())
                ->select(['id'])
                ->from(['u' => $queryBranches->union($queryMainBranch)]);


            $date=date('Y-m-d');
            $endDate = date("Y-m-d", strtotime("$date -14 day"));

            $total = Devices::find()
                ->where(['view_status' => self::on_road])
                ->andWhere(['in','branch',$branches])
                ->andWhere(['<', 'date(created_at)', $endDate])
                ->count();

            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }

        }
        else{

            $date=date('Y-m-d');
            $endDate = date("Y-m-d", strtotime("$date -14 day"));

            $total = Devices::find()
                ->where(['view_status' => self::on_road])
                ->andWhere(['branch' =>Yii::$app->user->identity->branch])
                ->andWhere(['<', 'date(created_at)', $endDate])
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
        } else {
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
            $total = InTransit::find()
                ->where(['partiner' => 1])
                ->andWhere(['border_port' => 5])
                ->andWhere(['branch' => Yii::$app->user->identity->branch])
                ->andWhere(['view_status' => Devices::in_transit])->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        } else {
            $total = Devices::find()
                ->where(['border_port' => 5])
                ->andWhere(['branch' => Yii::$app->user->identity->branch])
                ->andWhere(['view_status' => Devices::in_transit])->count();
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
                ->andWhere(['border_port' => 4])
                ->andWhere(['branch' => Yii::$app->user->identity->branch])
                ->andWhere(['view_status' => Devices::in_transit])->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        } else {
            $total = Devices::find()
                ->where(['border_port' => 4])
                ->andWhere(['branch' => Yii::$app->user->identity->branch])
                ->andWhere(['view_status' => Devices::in_transit])->count();
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
            ->where(['border_port' => 31])
            ->andWhere(['branch' => Yii::$app->user->identity->branch])
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
            ->where(['border_port' => 32])
            ->andWhere(['branch' => Yii::$app->user->identity->branch])
            ->count();
        if ($total > 0) {
            echo $total;
        } else {
            echo 0;
        }
    }

//    public static function InTransit()
//    {
//        $date_today = strtotime("-3 day");
//        $date_today = date('Y-m-d', $date_today);
//        $device = Devices::find()
//            ->where(['<', 'date(created_at)', $date_today])
//            ->andWhere(['view_status' => Devices::stock_devices])
//            ->andWhere(['status' => 2])
//            ->count();
//        if ($device > 0) {
//            echo $device;
//        } else {
//            echo 0;
//        }
//    }
//
//    public static function notAttended()
//    {
//
//        $date_today = strtotime("-7 day");
//        $date_today = date('Y-m-d', $date_today);
//
//        $device = Devices::find()
//            ->where(['<', 'date(created_at)', $date_today])
//            ->andWhere(['view_status' => Devices::stock_devices])
//            ->andWhere(['status' => 2])
//            ->count();
//
//        if ($device > 0) {
//            echo $device;
//        } else {
//            echo 0;
//        }
//    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['serial_no'], 'required'],
            [['received_from', 'border_port', 'received_from_staff', 'received_status', 'created_by',
                'status', 'device_from', 'stock_status', 'branch', 'type', 'released_by', 'released_to', 'transferred_from', 'transferred_to',
                'transferred_by', 'sales_person', 'sale_id', 'device_category', 'partiner', 'gate_number'], 'integer'],
            [['created_at', 'transferred_date', 'released_date','view_status'], 'safe'],
            [['remark'], 'string'],
            [['serial_no'], 'safe'],
            [['sim_card', 'tzl', 'vehicle_no', 'container_number'], 'string', 'max' => 255],
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
            'remark' => 'Remark',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'status' => 'Status',
            'branch' => 'Branch',
            'type' => 'Type',
            'view_status' => 'Current Status',
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
        return $this->hasMany(StockDevices::className(), ['serial_no' => 'serial']);
    }

    public function getBranch0()
    {
        return $this->hasOne(Branches::className(), ['id' => 'branch']);
    }

    public function getType0()
    {
        return $this->hasOne(DeviceTypes::className(), ['id' => 'type']);
    }

    public function getCat0()
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

    public function getGate0()
    {
        return $this->hasOne(BorderPort::className(), ['id' => 'gate_number']);
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
    public function getCreate0()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getReg0()
    {
        return $this->hasOne(User::className(), ['id' => 'registration_by']);
    }

//    public static function getAvailable()
//    {
//        $total = Devices::find()
//            ->where(['view_status' => Devices::released_devices])
//            ->count();
//        if ($total > 0) {
//            echo $total;
//        } else {
//            echo 0;
//        }
//
//    }
//
//
//    public static function getActive()
//    {
//        $devices = Devices::find()
//            ->select(['serial_no'])
//            ->where(['view_status' => Devices::awaiting_receive])
//            ->count();
//        if ($devices > 0) {
//            echo $devices;
//        } else {
//            echo 0;
//        }
//    }

    public function getReleased0()
    {
        return $this->hasOne(User::className(), ['id' => 'released_by']);
    }

    public function getTagger0()
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


//    public static function getBranchAvailable()
//    {
//        $total = Devices::find()
//            ->where(['view_status' => Devices::released_devices])
//            ->andWhere(['branch' => Yii::$app->user->identity->branch])
//            ->count();
//        if ($total > 0) {
//            echo $total;
//        } else {
//            echo 0;
//        }
//    }

    public static function getAvailableGateTwo()
    {
        $total = Devices::find()->where(['border_port' => 12])->count();
        if ($total > 0) {
            echo $total;
        } else {
            echo 0;
        }
    }

    public static function getBranchAvailableGateTwo()
    {
        $total = Devices::find()
            ->where(['border_port' => 12])
            ->andWhere(['branch' => Yii::$app->user->identity->branch])
            ->count();
        if ($total > 0) {
            echo $total;
        } else {
            echo 0;
        }
    }

//    public static function getAvailableGateThree()
//    {
//        $total = Devices::find()->where(['border_port' => 13])->andWhere(['view_status' => Devices::released_devices])->count();
//        if ($total > 0) {
//            echo $total;
//        } else {
//            echo 0;
//        }
//    }

//    public static function getBranchAvailableGateThree()
//    {
//        $total = Devices::find()
//            ->where(['border_port' => 13])
//            ->andWhere(['branch' => Yii::$app->user->identity->branch])
//            ->andWhere(['view_status' => Devices::released_devices])->count();
//        if ($total > 0) {
//            echo $total;
//        } else {
//            echo 0;
//        }
//    }

//    public static function getAvailableGateFive()
//    {
//        $total = Devices::find()->where(['border_port' => 14])->andWhere(['view_status' => Devices::released_devices])->count();
//        if ($total > 0) {
//            echo $total;
//        } else {
//            echo 0;
//        }
//    }

//    public static function getBranchAvailableGateFive()
//    {
//        $total = Devices::find()
//            ->where(['border_port' => 14])
//            ->andWhere(['branch' => Yii::$app->user->identity->branch])
//            ->andWhere(['view_status' => Devices::released_devices])
//            ->count();
//        if ($total > 0) {
//            echo $total;
//        } else {
//            echo 0;
//        }
//    }

//    public static function getAvailableGateMalawi()
//    {
//        $total = Devices::find()->where(['border_port' => 15])->andWhere(['view_status' => Devices::released_devices])->count();
//        if ($total > 0) {
//            echo $total;
//        } else {
//            echo 0;
//        }
//    }
//
//    public static function getBranchAvailableGateMalawi()
//    {
//        $total = Devices::find()
//            ->where(['border_port' => 15])
//            ->andWhere(['branch' => Yii::$app->user->identity->branch])
//            ->andWhere(['view_status' => Devices::released_devices])->count();
//        if ($total > 0) {
//            echo $total;
//        } else {
//            echo 0;
//        }
//    }
//
//    public static function getAvailableGateCargo()
//    {
//        $total = Devices::find()->andWhere(['view_status' => Devices::released_devices])->count();
//        if ($total > 0) {
//            echo $total;
//        } else {
//            echo 0;
//        }
//    }
//
//    public static function getAvailableGateKicd()
//    {
//        $total = Devices::find()->where(['border_port' => 16])->andWhere(['view_status' => Devices::released_devices])->count();
//        if ($total > 0) {
//            echo $total;
//        } else {
//            echo 0;
//        }
//    }
//
//    public static function getBranchAvailableGateKicd()
//    {
//        $total = Devices::find()
//            ->where(['border_port' => 16])
//            ->andWhere(['branch' => Yii::$app->user->identity->branch])
//            ->andWhere(['view_status' => Devices::released_devices])->count();
//        if ($total > 0) {
//            echo $total;
//        } else {
//            echo 0;
//        }
//    }


    public function getCreatedBy0()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }


    public static function LongTrips()
    {
//        $date_today = strtotime("-7 day");
//        $date_today = date('Y-m-d', $date_today);
//        $device = Devices::find()->where(['<', 'date(created_at)', $date_today])->andWhere(['view_status' => Devices::in_transit])->count();
//
//        if ($device > 0) {
//            echo $device;
//        } else {
//            echo 0;
//        }
    }


    public static function getOnRoad()
    {
        if (Yii::$app->user->identity->user_type == User::PARTNER) {

            $total = Devices::find()
                //->where(['in','serial_no',$devices])
                ->where(['branch' => Yii::$app->user->identity->branch])
                ->andWhere(['view_status' => Devices::in_transit])
                ->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        } else {
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


}
