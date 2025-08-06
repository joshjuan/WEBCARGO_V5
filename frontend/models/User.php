<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string $email
 * @property string $mobile
 * @property int $status
 * @property int $branch
 * @property int $company_name
 * @property int $user_type
 * @property int $created_at
 * @property int $updated_at
 * @property string|null $verification_token
 *
 * @property Devices[] $devices
 * @property FaultDevices[] $faultDevices
 */
class User extends \common\models\User
{


    public $password;
    public $repassword;
    private $_statusLabel;
    private $_roleLabel;

    const ADMIN = 1;
    const OFFICE_STAFF = 2;
    const PORT_STAFF= 3;
    const BORDER_STAFF= 4;
    const BILL_STAFF= 5;
    const PARTNER= 6;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }


    public static function getUserType()
    {
        return [
            self::ADMIN => Yii::t('app', 'Admin'),
            self::OFFICE_STAFF => Yii::t('app', 'Office Staff'),
            self::PORT_STAFF => Yii::t('app', 'Port Staff'),
            self::BORDER_STAFF => Yii::t('app', 'Border Staff'),
            self::BILL_STAFF => Yii::t('app', 'Bill Staff'),
            self::PARTNER => Yii::t('app', 'Partner'),

        ];
    }

     public static function getUserTypeBillCustomer()
    {
        return [

            self::BILL_STAFF => Yii::t('app', 'Bill Customer'),
        ];
    }


    public static function getArrayRole()
    {
        return ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description');
    }

    public function getRoleLabel()
    {

        if ($this->_roleLabel === null) {
            $roles = self::getArrayRole();
            $this->_roleLabel = $roles[$this->role];
        }
        return $this->_roleLabel;
    }

    public static function getLoginStatus()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('app', 'STATUS_ACTIVE'),
            self::STATUS_INACTIVE => Yii::t('app', 'STATUS_INACTIVE'),
            self::STATUS_DELETED => Yii::t('app', 'STATUS_DELETED'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
           // [['username','mobile', 'created_at'], 'required',],
            [['status', 'created_at', 'updated_at','user_type','branch'], 'integer'],
            ['repassword', 'compare', 'compareAttribute' => 'password'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'verification_token','company_name'], 'string', 'max' => 255],
            [['auth_key','mobile'], 'string', 'max' => 32],
            [['username','email'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'verification_token' => 'Verification Token',
        ];
    }


  
    public function scenarios()
    {
        return [
            'default' => ['full_name','username','user_type','company_name','email','role','password','repassword', 'status','branch'],
            'createUser' => ['username','name','mobile', 'email', 'password', 'repassword', 'status', 'role'],
            // 'admin-update' => ['username','name','mobile', 'email', 'status', 'role']
            'admin-update' => ['password', 'repassword']

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevices()
    {
        return $this->hasMany(Devices::className(), ['created_by' => 'id']);
    }

    public function getBranch0()
    {
        return $this->hasOne(Branches::className(), ['id' => 'branch']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFaultDevices()
    {
        return $this->hasMany(FaultDevices::className(), ['created_by' => 'id']);
    }

    public static function getAllUser()
    {
        return ArrayHelper::map(User::find()->all(),'id','username');
    }


    public static function getBorderPortUser()
    {
        return ArrayHelper::map(User::find()->where(['in','user_type',[3,4]])->all(),'id','username');
    }
    
        public static function getOfficeUser()
    {
        return ArrayHelper::map(User::find()->where(['in','user_type',[1,2]])->all(),'id','username');
    }
    
    public static function getBillCustomer()
    {
        return ArrayHelper::map(User::find()->where(['in','user_type',5])->all(),'id','company_name');
    }

    public static function getBorderUser()
    {
        return ArrayHelper::map(User::find()->where(['in','user_type',[4]])->all(),'id','username');
    }

    public static function getBorderUserBranch()
    {
        return ArrayHelper::map(User::find()->where(['in','user_type',[4]])->andWhere(['branch'=>Yii::$app->user->identity->branch])->all(),'id','username');
    }

    public static function getPortUser()
    {
        return ArrayHelper::map(User::find()->where(['in','user_type',[3]])->all(),'id','username');
    }
    public static function getPortUserBranch()
    {
        return ArrayHelper::map(User::find()
            ->where(['in','user_type',[3]])
            ->andWhere(['branch' =>Yii::$app->user->identity->branch])
            ->all(),'id','username');
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord || (!$this->isNewRecord && $this->password)) {
                $this->setPassword($this->password);
                $this->generateAuthKey();
                $this->generatePasswordResetToken();
            }
            return true;
        }
        return false;
    }

    public static function getRules()
    {
        return ArrayHelper::map(AuthItem::find()->where(['type'=>"1"])->all(),'name','name');
    }

   public static function getRulesCustomerRules()
    {
        return ArrayHelper::map(AuthItem::find()->where(['type'=>"1"])->all(),'name','name');
    }

    public static function getRulesBillCustomerRules()
    {
        return ArrayHelper::map(AuthItem::find()->where(['type'=>"1"])->andWhere(['name'=>"Bill_Customer"])->all(),'name','name');
    }

    public static function getArrayStatus()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('app', 'STATUS_ACTIVE'),
            self::STATUS_INACTIVE => Yii::t('app', 'STATUS_INACTIVE'),
            self::STATUS_DELETED => Yii::t('app', 'STATUS_DELETED'),
        ];
    }
}
