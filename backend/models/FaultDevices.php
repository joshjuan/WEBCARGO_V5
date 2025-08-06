<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "fault_devices".
 *
 * @property int $id
 * @property int $serial_no
 * @property int $created_by
 * @property int $status
 * @property string $created_at
 * @property string $remarks
 *
 * @property User $createdBy
 * @property User $branch
 */
class FaultDevices extends \yii\db\ActiveRecord
{
    const fault_device = 2;


    public static function getStatus()
    {
        return [
            self::fault_device => Yii::t('app', 'FAULT DEVICE'),


        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fault_devices';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['serial_no', 'created_by', 'created_at', ], 'required'],
            [['created_by','status','branch'], 'integer'],
            [['created_at','serial_no'], 'safe'],
            [['remarks'], 'string'],
            [['serial_no'], 'unique'],
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
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'remarks' => 'Remarks',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public static function getAvailable()
    {
        $total = FaultDevices::find()->where(['view_status'=>Devices::fault_devices])->count();
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
            $total = FaultDevices::find()
                ->where(['in','serial_no',$devices])
                ->andWhere(['view_status'=>Devices::fault_devices])
                ->andWhere(['branch'=>Yii::$app->user->identity->branch])
                ->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }
        else{
            $total = FaultDevices::find()
                ->where(['view_status'=>Devices::fault_devices])
                ->andWhere(['branch'=>Yii::$app->user->identity->branch])
                ->count();
            if ($total > 0) {
                echo $total;
            } else {
                echo 0;
            }
        }

    }


}
