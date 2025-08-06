<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "sys_pensioners_verifications_logs".
 *
 * @property int $id
 * @property string $date_time
 * @property int $uid
 * @property string $pension_number
 * @property string $full_names
 * @property string $verification_code
 * @property string $code_expiry_date
 * @property string $sent_date_time
 * @property string $batch_code
 * @property string $kin_mobile_number
 * @property int $verified_by_uid
 * @property string $verified_date_time
 * @property int $verification_status
 * @property string $checker_remarks
 * @property string $date_renewed
 * @property string $start_date
 * @property string $expiry_date
 * @property string $verified_location
 * @property string $verified_by
 */
class SysPensionersVerificationsLogs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sys_pensioners_verifications_logs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date_time', 'code_expiry_date', 'sent_date_time', 'verified_date_time', 'date_renewed', 'start_date', 'expiry_date'], 'safe'],
            [['uid', 'pension_number', 'full_names', 'verification_code', 'code_expiry_date', 'sent_date_time', 'batch_code', 'kin_mobile_number', 'verified_by_uid', 'verified_date_time', 'checker_remarks', 'date_renewed', 'start_date', 'expiry_date', 'verified_location', 'verified_by'], 'required'],
            [['uid', 'verified_by_uid', 'verification_status'], 'integer'],
            [['checker_remarks'], 'string'],
            [['pension_number', 'verification_code', 'batch_code'], 'string', 'max' => 100],
            [['full_names', 'verified_location', 'verified_by'], 'string', 'max' => 255],
            [['kin_mobile_number'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date_time' => 'Date Time',
            'uid' => 'Uid',
            'pension_number' => 'Pension Number',
            'full_names' => 'Full Names',
            'verification_code' => 'Verification Code',
            'code_expiry_date' => 'Code Expiry Date',
            'sent_date_time' => 'Sent Date Time',
            'batch_code' => 'Batch Code',
            'kin_mobile_number' => 'Kin Mobile Number',
            'verified_by_uid' => 'Verified By Uid',
            'verified_date_time' => 'Verified Date Time',
            'verification_status' => 'Verification Status',
            'checker_remarks' => 'Checker Remarks',
            'date_renewed' => 'Date Renewed',
            'start_date' => 'Start Date',
            'expiry_date' => 'Expiry Date',
            'verified_location' => 'Verified Location',
            'verified_by' => 'Verified By',
        ];
    }
}
