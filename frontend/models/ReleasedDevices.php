<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "released_devices".
 *
 * @property int $id
 * @property int $serial_no
 * @property string $released_date
 * @property int|null $released_by
 * @property int|null $released_to
 * @property int|null $transferred_from
 * @property int|null $transferred_to
 * @property string|null $transferred_date
 * @property int $status
 * @property int $sales_point
 * @property int $transferred_by
 * @property int $view_status
 * @property int $branch
 * @property int $type
 */
class ReleasedDevices extends \yii\db\ActiveRecord
{

    const RELEASED=1;
    const TRANSFERRED=2;

    public static function getStatus()
    {
        return [
            self::RELEASED => Yii::t('app', 'RELEASED DEVICE'),
            self::TRANSFERRED => Yii::t('app', 'TRANSFERRED'),


        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'released_devices';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['serial_no', 'released_date'], 'required'],
            [['view_status','branch','transferred_by', 'released_by', 'released_to', 'transferred_from', 'transferred_to', 'status','sales_point','view_status','type'], 'integer'],
            [['released_date', 'transferred_date','serial_no'], 'safe'],
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
            'released_date' => 'Released Date',
            'released_by' => 'Released By',
            'released_to' => 'Released To',
            'sales_point' => 'Sales Point',
            'transferred_from' => 'Transferred From',
            'transferred_to' => 'Transferred To',
            'transferred_date' => 'Transferred Date',
            'status' => 'Status',
            'view_status' => 'view Status',
        ];
    }

    public function getReleased0()
    {
        return $this->hasOne(User::className(), ['id' => 'released_by']);
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

    public function getBorderPort()
    {
        return $this->hasOne(BorderPort::className(), ['id' => 'sales_point']);
    }


    public function getType0()
    {
        return $this->hasOne(DeviceTypes::className(), ['id' => 'type']);
    }

    public static function getAvailable()
    {
        $total = ReleasedDevices::find()->where(['view_status'=>Devices::released_devices])->count();
        if ($total > 0) {
            echo $total;
        } else {
            echo 0;
        }
    }

    public static function getBranchAvailable()
    {
        if (Yii::$app->user->identity->user_type == User::PARTNER) {
            $devices = Devices::find()
                ->select(['serial'])
                ->where(['partiner' => 1])
                ->asArray();
            $total = ReleasedDevices::find()
                ->where(['in','serial_no',$devices])
                ->andWhere(['view_status'=>Devices::released_devices])
                ->andWhere(['branch'=>Yii::$app->user->identity->branch])
                ->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }
        else{
            $total = ReleasedDevices::find()
                ->where(['view_status'=>Devices::released_devices])
                ->andWhere(['branch'=>Yii::$app->user->identity->branch])
                ->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }

    }

    public static function getAvailableGateTwo()
    {
        $total = ReleasedDevices::find()->where(['sales_point'=>12])->count();
        if ($total > 0) {
            echo $total;
        } else {
            echo 0;
        }
    }

    public static function getBranchAvailableGateTwo()
    {
        $total = ReleasedDevices::find()
            ->where(['sales_point'=>12])
            ->andWhere(['branch'=>Yii::$app->user->identity->branch])
            ->count();
        if ($total > 0) {
            echo $total;
        } else {
            echo 0;
        }
    }

    public static function getBranchAvailableKigamboni()
    {
        $total = ReleasedDevices::find()
            ->where(['sales_point'=>31])
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
        $total = ReleasedDevices::find()
            ->where(['sales_point'=>32])
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
        $total = ReleasedDevices::find()->where(['sales_point'=>13])->andWhere(['view_status'=>Devices::released_devices])->count();
        if ($total > 0) {
            echo $total;
        } else {
            echo 0;
        }
    }

    public static function getBranchAvailableGateThree()
    {
        $total = ReleasedDevices::find()
            ->where(['sales_point'=>13])
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
        $total = ReleasedDevices::find()->where(['sales_point'=>14])->andWhere(['view_status'=>Devices::released_devices])->count();
        if ($total > 0) {
            echo $total;
        } else {
            echo 0;
        }
    }

    public static function getBranchAvailableGateFive()
    {
        $total = ReleasedDevices::find()
            ->where(['sales_point'=>14])
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
        $total = ReleasedDevices::find()->where(['sales_point'=>15])->andWhere(['view_status'=>Devices::released_devices])->count();
        if ($total > 0) {
            echo $total;
        } else {
            echo 0;
        }
    }
    public static function getBranchAvailableGateMalawi()
    {
        $total = ReleasedDevices::find()
            ->where(['sales_point'=>15])
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
        $total = ReleasedDevices::find()->andWhere(['view_status'=>Devices::released_devices])->count();
        if ($total > 0) {
            echo $total;
        } else {
            echo 0;
        }
    }

    public static function getAvailableGateKicd()
    {
        $total = ReleasedDevices::find()->where(['sales_point'=>16])->andWhere(['view_status'=>Devices::released_devices])->count();
        if ($total > 0) {
            echo $total;
        } else {
            echo 0;
        }
    }

    public static function getBranchAvailableGateKicd()
    {
        $total = ReleasedDevices::find()
            ->where(['sales_point'=>16])
            ->andWhere(['branch'=>Yii::$app->user->identity->branch])
            ->andWhere(['view_status'=>Devices::released_devices])->count();
        if ($total > 0) {
            echo $total;
        } else {
            echo 0;
        }
    }
}
