<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "tbl_audit".
 *
 * @property integer $id
 * @property string $activity
 * @property string $module
 * @property string $action
 * @property string $maker
 * @property string $maker_time
 * @property string $date_issued
 */
class Audit extends \yii\db\ActiveRecord
{
    public $date_issued;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_audit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activity', 'module', 'action', 'maker', 'maker_time',], 'string', 'max' => 200],
            [['old','new','$date_issued;'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'activity' => Yii::t('app', 'Activity'),
            'module' => Yii::t('app', 'Module'),
            'action' => Yii::t('app', 'Action'),
            'maker' => Yii::t('app', 'User'),
            'maker_time' => Yii::t('app', 'Date | Time'),
            'date_issued' => Yii::t('app', 'Date | Time'),
        ];
    }

    /**
     * inserts new user activity
     */
    public static function setActivity($activity,$module,$action,$contentBefore,$contentAfter)
    {
        $audit=new Audit();


     //   print_r($contentAfter);
       //print_r($contentBefore);
       // exit;
        $audit->old = '';
        $audit->new = '';
        if($contentBefore != null && $contentAfter != null) {
            foreach ($contentBefore as $name => $value) {
                $tempOne = $name . ': ' . $value . ',  ';
                $before[] = $tempOne;
            }

            foreach ($contentAfter as $name => $value) {
                $tempTwo = $name . ': ' . $value . ',  ';
                $after[] = $tempTwo;
            }


            $length = count($after);
            for ($x = 0; $x < $length; $x++) {
                if ($before[$x] != $after[$x]) {
                    $audit->old = $audit->old . ' ' . $before[$x];
                    $audit->new = $audit->new . ' ' . $after[$x];
                }
            }
        }





        $audit->activity=$activity;
        $audit->module=$module;
        $audit->action=$action;
        if (!Yii::$app->user->isGuest) {
        $audit->maker=Yii::$app->user->identity->username;
        } else if (Yii::$app->user->isGuest) {

            return Yii::$app->getResponse()->redirect(Yii::$app->getHomeUrl());


        }
        $audit->maker_time=date('Y-m-d:H:i:s');
        $audit->date_issued=date('Y-m-d:H:i:s');
        if($audit->maker == null){
            $audit->maker = 'System';
        }
        $audit->save();

    }


}
