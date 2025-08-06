<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "compare_trips".
 *
 * @property int $id
 * @property string $document_path
 * @property string $document_name
 * @property string $date_from
 * @property string $date_to
 * @property int $total_activation
 * @property string $status
 * @property int $upload_by
 * @property string $upload_date
 */
class CompareTrips extends \yii\db\ActiveRecord
{

    public  $file;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'compare_trips';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['file'], 'file', 'extensions' => 'csv', 'skipOnEmpty' => false,
                'checkExtensionByMimeType' => false],
            [[ 'status', 'upload_by', 'upload_date','date_from','date_to'], 'required'],
            [['date_from', 'date_to', 'upload_date'], 'safe'],
            [['total_activation', 'upload_by'], 'integer'],
            [['document_path', 'document_name', 'status'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'document_path' => 'Document Path',
            'document_name' => 'Document Name',
            'date_from' => 'Date From',
            'date_to' => 'Date To',
            'total_activation' => 'Total Activation',
            'status' => 'Status',
            'upload_by' => 'Upload By',
            'upload_date' => 'Upload Date',
        ];
    }
}
