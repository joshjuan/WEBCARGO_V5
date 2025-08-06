<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "branches".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property string|null $created_at
 * @property string|null $created_by
 * @property string|null $branch_type
 */
class Branches extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'branches';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name','status'], 'required'],
            [['status','branch_type'], 'integer'],
            [['created_at'], 'safe'],
            [['name', 'created_by'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'status' => 'Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }


    public static function getAll()
    {
        return ArrayHelper::map(\backend\models\Branches::find()
            ->all(),'id','name');
    }

    public static function getAllBranches()
    {
        return ArrayHelper::map(\backend\models\Branches::find()
            //->where(['id'=>Yii::$app->user->identity->branch])
            ->all(),'id','name');
    }
}
