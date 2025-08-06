<?php

namespace backend\controllers;

use backend\models\AwaitingReceive;
use backend\models\AwaitingReceiveReport;
use backend\models\BorderPort;
use backend\models\BorderPortUser;
use backend\models\Devices;
use backend\models\InTransit;
use backend\models\InTransitReport;
use backend\models\Location;
use backend\models\Receipt;
use backend\models\ReceivedDevices;
use backend\models\ReceivedDevicesReport;
use backend\models\ReleasedDevices;
use backend\models\ReleasedDevicesReport;
use backend\models\SalesTrips;
use backend\models\StockDevices;
use common\models\LoginForm;
use common\models\User;
use DateTime;
use frontend\models\SignupForm;
use http\Exception;
use search;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\db\Query;
use yii\db\QueryBuilder;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;


class ApiController extends \yii\rest\ActiveController
{

    /**********************LIST OF ENDPOINT USED  APPLICATION ***********************************************/

    public $modelClass = 'backend\models\SalesTransactions';


    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */

    /**********************END POINT FOR USER AND CELEBRITIES LOGIN ***********************************************/
    public function actionLogin()
    {
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_RAW;

        $model = new LoginForm();
        $data = json_decode(file_get_contents("php://input"));

        $model->username = $data->username;
        $model->password = $data->password;


        $user = User::findByUsername($model->username);
        $userAll = User::find()->where(['username' => $model->username])->one();
        $userAllocated = BorderPortUser::find()->where(['name' => $userAll['id']])->one();

        if (!empty($user)) {
            if ($model->login()) {

                $response['error'] = false;
                $response['status'] = 'success';
                $response['message'] = 'You are now logged in';
                $response['userData'] = \common\models\User::findByUsername($model->username);

                $response = [

                    'user_id' => Yii::$app->user->identity->id,
                    'name' => Yii::$app->user->identity->full_name,
                    'username' => Yii::$app->user->identity->username,
                    'email' => Yii::$app->user->identity->email,
                    'status' => Yii::$app->user->identity->status,
                    'message' => $response['message'],
                    'access_token' => Yii::$app->user->identity->getAuthKey(),
                    'user_type' => Yii::$app->user->identity->user_type,
                    'border_port' => $userAllocated['border_port'],
                    'role' => Yii::$app->user->identity->role,

                ];

                $result = ['userData' => $response];
                return Json::encode($result);


            } else {
                $response['error'] = false;
                $response['status'] = 'error';
                $model->validate($model->password);
                $response['errors'] = $model->getErrors();
                $response['message'] = 'wrong password';
                return Json::encode($response);
            }


        } else {
            $response['error'] = false;
            $response['status'] = 'error';
            $model->validate($model->password);
            $response['errors'] = $model->getErrors();
            $response['message'] = 'user is disabled or does not exist!';
            return Json::encode($response);
        }
    }


    public function actionReleased($id)
    {

        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;

        $data = ReleasedDevices::find()->where(['id' => $id])->andWhere(['type' => [1, 2]])->all();
        if (count($data) > 0) {
            return array('data' => $data);


        } else {
            return array('status' => false, 'data' => 'No Data');
        }
    }

    public function actionSaleAdd()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;

        $model = new SalesTrips();
        $data = json_decode(file_get_contents("php://input"));

        $slaves_array = $data->slaves;
        $devices = json_decode(json_encode($slaves_array), True);

        // $slave_serial = $devices->serial;

        $model->sale_date = $data->sale_date;
        $model->tzl = $data->tzl;
        $model->start_date_time = $data->start_date_time;
        $model->vehicle_number = $data->vehicle_number;
        $model->chasis_number = $data->chasis_number;
        $model->driver_name = $data->driver_name;
        $model->border_id = $data->border_id;
        $model->trip_status = $data->trip_status;
        $model->driver_number = $data->driver_number;
        $model->serial_no = $data->serial_no;
        $model->amount = $data->amount;
        $model->currency = $data->currency;
        $model->gate_number = $data->gate_number;
        $model->end_date = $data->end_date;
        $model->sales_person = $data->sales_person;
        //  $model->receipt_number = $data->receipt_number;
        $model->passport = $data->passport;
        $model->container_number = $data->container_number;
        $model->vehicle_type_id = $data->vehicle_type_id;
        $model->customer_type_id = $data->customer_type_id;
        $model->customer_id = $data->customer_id;


        $model->company_name = $data->company_name;
        $model->customer_name = $data->customer_name;

        $model->agent = $data->agent;
        $model->cancelled_by = '';
        $model->edited_by = '';
        $model->edited_at = '';
        $model->date_cancelled = '';
        $model->sale_type = $data->sale_type;
        $model->sale_id = $data->sale_id;


        $branch=User::find()->where(['id'=>$data->sales_person])->one();
        $branch_id=$branch['branch'];
        $model->branch=$branch_id;
        //  print_r($devices);
        //  exit;


        ############################## NORMAL SALES #####################################
        if ($model->sale_type == 1) {

            $check_tzl = SalesTrips::find()->where(['tzl' => $model->tzl])->one();

            if (empty($check_tzl)) {

                ############################## CASH SALES #####################################
                if ($data->customer_type_id == 1) {
                    $model->receipt_number = $model->sales_person . Receipt::findCash();

                    $transit = InTransit::updateAll([
                        'view_status' => Devices::in_transit,
                        'tzl' => $model->tzl,
                        'border' => $model->border_id,
                        'sales_person' => $model->sales_person,
                        'vehicle_no' => $model->vehicle_number,
                        'container_number' => $model->container_number,
                        'created_by' => $model->sales_person,
                        'sale_id' =>$model->id,
                        'created_at' => date('Y-m-d H:i:s'),
                    ], ['serial_no' => $model->serial_no]);


                    $transit_report = Yii::$app->db->createCommand()
                        ->insert('in_transit_report', [
                            'serial_no' => $model->serial_no,
                            'tzl' => $model->tzl,
                            'border' => $model->border_id,
                            'sales_person' => $model->sales_person,
                            'vehicle_no' => $model->vehicle_number,
                            'container_number' => $model->container_number,
                            'created_by' => $model->sales_person,
                            'sale_id'=>$model->id,
                            'created_at' => date('Y-m-d H:i:s'),
                        ])->execute();

                    $this->actionReturn();

                    $branch=Devices::find()->where(['serial'=>$model->serial_no])->one();
                    $model->branch=$branch['branch'];
                    $model->type=$branch['type'];



                    if ($model->save() == true && $transit == true && $transit_report == true) {

                        ReleasedDevices::updateAll(['view_status' => Devices::in_transit, 'released_date' => null,
                            'released_by' => null, 'released_to' => null, 'sales_point' => null, 'transferred_from' => null,
                            'transferred_to' => null, 'transferred_date' => null, 'transferred_by' => null], ['serial_no' => $model->serial_no]);

                        if ($data->slaves != '') {
                            foreach ($devices as $key => $value) {

                                $data = ReleasedDevices::find()->where(['id' => $value])->one();
                                ReleasedDevices::updateAll([
                                    'view_status' => Devices::in_transit,
                                    'released_date' => null,
                                    'released_by' => null,
                                    'released_to' => null,
                                    'sales_point' => null,
                                    'transferred_from' => null,
                                    'transferred_to' => null,
                                    'transferred_date' => null,
                                    'transferred_by' => null],
                                    ['serial_no' => $data['serial_no']]);

                                Yii::$app->db->createCommand()
                                    ->insert('sales_trip_slaves', [
                                        'serial_no' => $data['serial_no'],
                                        'sale_id' => $model->id,
                                        'branch' => $model->branch,
                                        'created_at' => date('Y-m-d H:i:s'),
                                    ])->execute();

                                InTransit::updateAll([
                                    'view_status' => Devices::in_transit,
                                    'tzl' => $model->tzl,
                                    'border' => $model->border_id,
                                    'sales_person' => $model->sales_person,
                                    'vehicle_no' => $model->vehicle_number,
                                    'container_number' => $model->container_number,
                                    'created_by' => $model->sales_person,
                                    'sale_id' => $model->id,
                                    'created_at' => date('Y-m-d H:i:s'),
                                ], ['serial_no' => $data['serial_no']]);


                                Yii::$app->db->createCommand()
                                    ->insert('in_transit_report', [
                                        'serial_no' => $data['serial_no'],
                                        'tzl' => $model->tzl,
                                        'border' => $model->border_id,
                                        'sales_person' => $model->sales_person,
                                        'vehicle_no' => $model->vehicle_number,
                                        'sale_id' => $model->id,
                                        'container_number' => $model->container_number,
                                        'created_by' => $model->sales_person,
                                        'created_at' => date('Y-m-d H:i:s'),
                                    ])->execute();


                                $sale=InTransit::find()->where(['serial_no'=>$data['serial_no']])->one();
                                InTransit::updateAll(['sale_id'=>$sale['sale_id']],['tzl'=>$sale['tzl']]);
                                InTransitReport::updateAll(['sale_id'=>$sale['sale_id']],['tzl'=>$sale['tzl']]);

                            }


                        }

                        $response = [
                            'receipt_number' => $model->receipt_number,
                            'amount' => $model->amount,
                            'sale_id' => $model->id,
                        ];

                        $result = ['receipt' => $response];

                        return Json::encode($result);
                    } else {
                        $result = ['message' => $model->getErrors()];
                        return Json::encode($result);
                    }


                } ############################## BILL SALES ########### ##########################
                elseif ($data->customer_type_id == 2) {
                    $model->receipt_number = $model->sales_person . Receipt::findBill();

                    $transit = InTransit::updateAll([
                        'view_status' => Devices::in_transit,
                        'tzl' => $model->tzl,
                        'border' => $model->border_id,
                        'sales_person' => $model->sales_person,
                        'vehicle_no' => $model->vehicle_number,
                        'container_number' => $model->container_number,
                        'created_by' => $model->sales_person,
                        'sale_id' => $model->id,
                        'created_at' => date('Y-m-d H:i:s'),
                    ], ['serial_no' => $model->serial_no]);


                    $transit_report = Yii::$app->db->createCommand()
                        ->insert('in_transit_report', [
                            'serial_no' => $model->serial_no,
                            'tzl' => $model->tzl,
                            'border' => $model->border_id,
                            'sales_person' => $model->sales_person,
                            'vehicle_no' => $model->vehicle_number,
                            'container_number' => $model->container_number,
                            'created_by' => $model->sales_person,
                            'sale_id' => $model->id,
                            'created_at' => date('Y-m-d H:i:s'),
                        ])->execute();

                    if ($model->save() == true && $transit == true && $transit_report == true) {

                        ReleasedDevices::updateAll(['view_status' => Devices::in_transit, 'released_date' => null,
                            'released_by' => null, 'released_to' => null, 'sales_point' => null, 'transferred_from' => null,
                            'transferred_to' => null, 'transferred_date' => null, 'transferred_by' => null], ['serial_no' => $model->serial_no]);

                        if ($data->slaves != '') {
                            foreach ($devices as $key => $value) {

                                $data = ReleasedDevices::find()->where(['id' => $value])->one();
                                ReleasedDevices::updateAll([
                                    'view_status' => Devices::in_transit,
                                    'released_date' => null,
                                    'released_by' => null,
                                    'released_to' => null,
                                    'sales_point' => null,
                                    'transferred_from' => null,
                                    'transferred_to' => null,
                                    'transferred_date' => null,
                                    'transferred_by' => null],
                                    ['serial_no' => $data['serial_no']]);

                                Yii::$app->db->createCommand()
                                    ->insert('sales_trip_slaves', [
                                        'serial_no' => $data['serial_no'],
                                        'sale_id' => $model->id,
                                        'branch' => $model->branch,
                                        'created_at' => date('Y-m-d H:i:s'),
                                    ])->execute();

                                InTransit::updateAll([
                                    'view_status' => Devices::in_transit,
                                    'tzl' => $model->tzl,
                                    'border' => $model->border_id,
                                    'sales_person' => $model->sales_person,
                                    'vehicle_no' => $model->vehicle_number,
                                    'container_number' => $model->container_number,
                                    'created_by' => $model->sales_person,
                                    'sale_id' => $model->sale_id,
                                    'created_at' => date('Y-m-d H:i:s'),
                                ], ['serial_no' => $data['serial_no']]);


                                Yii::$app->db->createCommand()
                                    ->insert('in_transit_report', [
                                        'serial_no' => $data['serial_no'],
                                        'tzl' => $model->tzl,
                                        'border' => $model->border_id,
                                        'sales_person' => $model->sales_person,
                                        'vehicle_no' => $model->vehicle_number,
                                        'sale_id' => $model->sale_id,
                                        'container_number' => $model->container_number,
                                        'created_by' => $model->sales_person,
                                        'created_at' => date('Y-m-d H:i:s'),
                                    ])->execute();


                                $sale=InTransit::find()->where(['serial_no'=>$data['serial_no']])->one();
                                InTransit::updateAll(['sale_id'=>$sale['sale_id']],['tzl'=>$sale['tzl']]);
                                InTransitReport::updateAll(['sale_id'=>$sale['sale_id']],['tzl'=>$sale['tzl']]);

                            }

                        }

                        $this->actionReturn();
                        $response = [

                            'receipt_number' => $model->receipt_number,
                            'amount' => $model->amount,
                            'sale_id' => $model->id,

                        ];

                        $result = ['receipt' => $response];

                        return Json::encode($result);
                    } else {
                        $result = ['message' => $model->getErrors()];
                        return Json::encode($result);
                    }


                } else {
                    $result = ['message' => $model->getErrors()];
                    return Json::encode($result);
                }

                // end checking TZL
            } else {
                $result = ['message' => 'TZL exist'];
                return Json::encode($result);
            }


        } ############################## EDIT  SALES #####################################
        elseif ($model->sale_type == 3) {
            $receipt = SalesTrips::find()->where(['id' => $model->sale_id])->one();

            if ($receipt['serial_no'] == $model->serial_no) {

                $edited = 'EDITED';
                SalesTrips::updateAll([
                    //   'sale_type' => $model->sale_type,
                    //     'sale_date' => $model->sale_date,
                    'tzl' => $model->tzl . $edited,
                    'start_date_time' => $model->start_date_time,
                    'vehicle_number' => $model->vehicle_number,
                    'chasis_number' => $model->chasis_number,
                    'driver_name' => $model->driver_name,
                    'border_id' => $model->border_id,
                  //  'trip_status' => $model->trip_status,
                    'trip_status' => 2,
                    'driver_number' => $model->driver_number,
                    'serial_no' => $model->serial_no,
                    'amount' => $model->amount,
                    'currency' => $model->currency,
                    'gate_number' => $model->gate_number,
                    'end_date' => $model->end_date,
                    'sales_person' => $model->sales_person,
                    'receipt_number' => $receipt['receipt_number'],
                    'passport' => $model->passport,
                    'container_number' => $model->container_number,
                    'vehicle_type_id' => $model->vehicle_type_id,
                    'customer_type_id' => $model->customer_type_id,
                    'company_name' => $model->company_name,
                    'customer_name' => $model->customer_name,
                    'agent' => $model->agent,
                    'sale_id' => $model->sale_id,
                    'edited_by' => $model->sales_person,
                    'edited_at' => date('Y-m-d H:i:s')],
                    ['id' => $model->sale_id]);

                $model = new SalesTrips();
                $model->sale_date = $data->sale_date;
                $model->tzl = $data->tzl;
                $model->start_date_time = $data->start_date_time;
                $model->vehicle_number = $data->vehicle_number;
                $model->chasis_number = $data->chasis_number;
                $model->driver_name = $data->driver_name;
                $model->border_id = $data->border_id;
                // $model->trip_status = $data->trip_status;
                $model->trip_status = 1;
                $model->driver_number = $data->driver_number;
                $model->serial_no = $data->serial_no;
                $model->amount = $data->amount;
                $model->currency = $data->currency;
                $model->gate_number = $data->gate_number;
                $model->end_date = $data->end_date;
                $model->sales_person = $data->sales_person;
                $model->receipt_number = $receipt['receipt_number'];
                $model->passport = $data->passport;
                $model->container_number = $data->container_number;
                $model->vehicle_type_id = $data->vehicle_type_id;
                $model->customer_type_id = $data->customer_type_id;
                $model->company_name = $data->company_name;
                $model->customer_name = $data->customer_name;
                $model->agent = $data->agent;
                $model->cancelled_by = '';
                $model->edited_by = $model->sales_person;
                $model->edited_at = date('Y-m-d H:i:s');
                $model->date_cancelled = '';
                $model->sale_type = $data->sale_type;
                $model->sale_id = $data->sale_id;
                $model->save();


                InTransit::updateAll([
                    'serial_no' => $model->serial_no,
                    'tzl' => $model->tzl,
                    'border' => $model->border_id,
                    'sales_person' => $model->sales_person,
                    'vehicle_no' => $model->vehicle_number,
                    'container_number' => $model->container_number,
                    'created_by' => $model->sales_person,
                    'created_at' => $model->sale_date],
                    ['serial_no' => $receipt['serial_no']]);

                InTransitReport::updateAll([
                    'serial_no' => $model->serial_no,
                    'tzl' => $model->tzl,
                    'border' => $model->border_id,
                    'sales_person' => $model->sales_person,
                    'vehicle_no' => $model->vehicle_number,
                    'container_number' => $model->container_number,
                    'created_by' => $model->sales_person,
                    'created_at' => $model->sale_date],
                    ['serial_no' => $receipt['serial_no'], 'tzl' => $receipt['tzl']]);


                $response = [
                    'receipt_number' => $receipt['receipt_number'],
                    'amount' => $model->amount,
                    'sale_id' => $model->sale_id,
                ];
                $result = ['receipt' => $response];

                return Json::encode($result);


            } else {
                $edited = 'EDITED';
                $true = SalesTrips::updateAll([
                    //  'serial_no'=>$model->serial_no,
                    'tzl' => $receipt['tzl'] . $edited,
                    'sale_id' => $model->sale_id,
                    'sale_type' => $model->sale_type,
                    'edited_at' => date('Y-m-d H:i:s'),
                    'edited_by' => $model->sales_person,
                    'trip_status' => SalesTrips::EDITED,
                ],
                    ['id' => $model->sale_id]);

                Yii::$app->db->createCommand()
                    ->insert('sales_trips', [
                        'sale_date' => $model->sale_date,
                        'tzl' => $model->tzl,
                        'start_date_time' => $model->start_date_time,
                        'vehicle_number' => $model->vehicle_number,
                        'chasis_number' => $model->chasis_number,
                        'driver_name' => $model->driver_name,
                        'border_id' => $model->border_id,
                        'trip_status' => $model->trip_status,
                        'driver_number' => $model->driver_number,
                        'serial_no' => $model->serial_no,
                        'amount' => $model->amount,
                        'currency' => $model->currency,
                        'gate_number' => $model->gate_number,
                        'end_date' => $model->end_date,
                        'sales_person' => $model->sales_person,
                        'receipt_number' => $receipt['receipt_number'],
                        'passport' => $model->passport,
                        'container_number' => $model->container_number,
                        'vehicle_type_id' => $model->vehicle_type_id,
                        'customer_type_id' => $model->customer_type_id,
                        'company_name' => $model->company_name,
                        'customer_name' => $model->customer_name,
                        'agent' => $model->agent,
                    ])->execute();


                $check = InTransit::find()->where(['tzl' => $model->tzl])->one();

                InTransit::updateAll(['view_status' => Devices::released_devices], ['tzl' => $check['tzl']]);
                InTransit::updateAll(['view_status' => Devices::in_transit], ['serial_no' => $model->serial_no]);

                InTransitReport::updateAll(['serial_no' => $model->serial_no], ['tzl' => $check['tzl']]);

                if ($true == true) {
                    $check_report = ReleasedDevicesReport::find()
                        ->where(['serial_no' => $receipt['serial_no']])
                        ->orderBy('id DESC')
                        ->one();

                    ReleasedDevices::updateAll([
                        'view_status' => Devices::released_devices,
                        'id' => $check_report['id'],
                        'released_to' => $check_report['released_to'],
                        'sales_point' => $check_report['sales_point'],
                        'status' => $check_report['status'],
                        'released_by' => $check_report['released_by'],
                        'released_date' => $check_report['released_date'],
                    ], ['serial_no' => $check_report['serial_no']]);


                    ReleasedDevices::updateAll(['view_status' => Devices::in_transit], ['serial_no' => $model->serial_no]);
                    //   ReleasedDevices::deleteAll(['serial_no' => $model->serial_no]);
                    $response = [
                        'receipt_number' => $receipt['receipt_number'],
                        'amount' => $model->amount,
                        'sale_id' => $model->sale_id,
                    ];

                    $result = ['receipt' => $response];

                    return Json::encode($result);
                } else {
                    $result = ['message' => 'Swapping Failed, No devices changes'];
                    return Json::encode($result);
                }
            }


        } else {

            $result = ['message' => ' failed'];
            return Json::encode($result);
        }

    }


    public function actionBorders()
    {
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;
        $query = new Query;
        $query->select(['id', 'name as title'])
            ->from('border_port')
            ->where(['location' => 1]);

        $command = $query->createCommand();
        $response['borders'] = $command->queryAll();
        return $response;

    }

    public function actionPoints()
    {
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;
        $query = new Query;
        $query->select(['id', 'name as gate_name'])
            ->from('border_port')
            ->where(['location' => 2]);

        $command = $query->createCommand();
        $response['portPoints'] = $command->queryAll();
        return $response;

    }

    public function actionStaffs()
    {
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;
        $query = new Query;
        $query->select(['id', 'full_name as names', 'id as uid'])
            ->from('user')
            ->where(['user_type' => \backend\models\User::PORT_STAFF]);

        $command = $query->createCommand();
        $response['staffs'] = $command->queryAll();
        return $response;

    }

    public function actionDevices($user_id)
    {
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;

        $query = new Query;
        $query->select(['id', 'serial_no as name'])
            ->from('devices');
        // ->where(['released_to' => $user_id]);
        // ->andWhere(['in','type' => [1, 2]])
        //   ->andWhere(['view_status' => Devices::released_devices]);
        $command = $query->createCommand();
        $response['devices'] = $command->queryAll();
        return $response;
    }

    public function actionSlave($user_id)
    {
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;

        $query = new Query;
        $query->select(['id', 'serial_no as name'])
            ->from('released_devices')
            ->where(['released_to' => $user_id])
            ->andWhere(['type' => 3])
            ->andWhere(['view_status' => Devices::released_devices]);
        $command = $query->createCommand();
        $response['devices'] = $command->queryAll();
        return $response;
    }


    public function actionCustomers()
    {
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;
        $query = new Query;
        $query->select(['id', 'company_name'])
            ->from('user')
            ->where(['user_type' => 5]);

        $command = $query->createCommand();
        $response['customers'] = $command->queryAll();
        return $response;

    }

######################################### PULL DATA FOR EDITING SALE RECORD #######################################
    public function actionEditSale()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;

        $data = json_decode(file_get_contents("php://input"));
        $tzl = $data->tzdl;
        $updateSalesTrip = SalesTrips::find()->where(['tzl' => $tzl])->one();
        $gateName = BorderPort::find()->where(['id' => $updateSalesTrip['gate_number']])->one();
        $device = Devices::find()->where(['serial' => $updateSalesTrip['serial_no']])->one();
        $customer = User::find()->where(['id' => $updateSalesTrip['customer_name']])->one();
        $username = User::find()->where(['id' => $updateSalesTrip['sales_person']])->one();

        $date1 = strtotime($updateSalesTrip['sale_date']);
        $date2 = strtotime('Y-m-d H:i:s');

        $diff = abs($date2 - $date1);
        $years = floor($diff / (365 * 60 * 60 * 24));
        $months = floor(($diff - $years * 365 * 60 * 60 * 24)
            / (30 * 60 * 60 * 24));
        $days = floor(($diff - $years * 365 * 60 * 60 * 24 -
                $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
        $hours = floor(($diff - $years * 365 * 60 * 60 * 24
                - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24)
            / (60 * 60));

        if ($tzl == '') {
            if ($updateSalesTrip != null) {

                $result = ['receipt' => [
                    'port' => [
                        'port_point_id' => $updateSalesTrip['gate_number'],
                        'port_point_name' => $gateName['name']
                    ],
                    'driver_name' => $updateSalesTrip['driver_name'],
                    'sale_id' => $updateSalesTrip['id'],
                    'user_id' => $updateSalesTrip['sales_person'],
                    'username' => $username['username'],
                    'amount' => $updateSalesTrip['amount'],
                    'company_name' => $updateSalesTrip['company_name'],
                    'agent' => $updateSalesTrip['agent'],
                    'currency' => $updateSalesTrip['currency'],
                    'vehicle_number' => $updateSalesTrip['vehicle_number'],
                    'tzl' => $updateSalesTrip['tzl'],
                    'border' => [
                        'border_id' => $updateSalesTrip['gate_number'],
                        'border_name' => $gateName['name']
                    ],
                    'device' => [
                        'device_id' => $device['id'],
                        'device_serial' => $updateSalesTrip['serial_no']
                    ],
                    'passport' => $updateSalesTrip['passport'],
                    'container_number' => $updateSalesTrip['container_number'],
                    'customer' => [
                        'customer_id' => $customer['id'],
                        'customer_name' => $updateSalesTrip['customer_name']
                    ],
                    'driver_number' => $updateSalesTrip['driver_number'],
                    'receipt_number' => $updateSalesTrip['receipt_number'],
                    'chasis_number' => $updateSalesTrip['chasis_number'],
                    'vehicle_type_id' => $updateSalesTrip['vehicle_type_id'],
                    'customer_type_id' => $updateSalesTrip['customer_type_id'],
                    'start_date_time' => $updateSalesTrip['start_date_time'],
                    'end_date' => $updateSalesTrip['start_date_time'],
                ]
                ];

                return Json::encode($result);

            } else {

                $result = ['message' => 'This tzl not found'];

                return Json::encode($result);
            }
        } else {
            $result = ['message' => 'You can not edit Sales before cancellation'];

            return Json::encode($result);
        }

    }


    ######################################### REPRINT SALE RECORD #######################################
    public function actionReprint()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;

        $data = json_decode(file_get_contents("php://input"));

        $tzl = $data->tzdl;

        $updateSalesTrip = SalesTrips::find()->where(['tzl' => $tzl])->one();
        $gateName = BorderPort::find()->where(['id' => $updateSalesTrip['gate_number']])->one();
        $device = Devices::find()->where(['serial' => $updateSalesTrip['serial_no']])->one();
        $customer = User::find()->where(['id' => $updateSalesTrip['customer_name']])->one();
        $username = User::find()->where(['id' => $updateSalesTrip['sales_person']])->one();

        if ($updateSalesTrip != null) {
            $result = ['receipt' => [
                'port' => [
                    'port_point_id' => $updateSalesTrip['gate_number'],
                    'port_point_name' => $gateName['name']
                ],
                'driver_name' => $updateSalesTrip['driver_name'],
                'sale_id' => $updateSalesTrip['id'],
                'user_id' => $updateSalesTrip['sales_person'],
                'username' => $username['username'],
                'amount' => $updateSalesTrip['amount'],
                'company_name' => $updateSalesTrip['company_name'],
                'agent' => $updateSalesTrip['agent'],
                'currency' => $updateSalesTrip['currency'],
                'vehicle_number' => $updateSalesTrip['vehicle_number'],
                'tzl' => $updateSalesTrip['tzl'],
                'border' => [
                    'border_id' => $updateSalesTrip['gate_number'],
                    'border_name' => $gateName['name']
                ],
                'device' => [
                    'device_id' => $device['id'],
                    'device_serial' => $updateSalesTrip['serial_no']
                ],
                'passport' => $updateSalesTrip['passport'],
                'container_number' => $updateSalesTrip['container_number'],
                'customer' => [
                    'customer_id' => $customer['id'],
                    'customer_name' => $updateSalesTrip['customer_name']
                ],
                'driver_number' => $updateSalesTrip['driver_number'],
                'receipt_number' => $updateSalesTrip['receipt_number'],
                'chasis_number' => $updateSalesTrip['chasis_number'],
                'vehicle_type_id' => $updateSalesTrip['vehicle_type_id'],
                'customer_type_id' => $updateSalesTrip['customer_type_id'],
                'start_date_time' => $updateSalesTrip['start_date_time'],
                'end_date' => $updateSalesTrip['start_date_time'],
            ]
            ];

            return Json::encode($result);

        } else {

            $result = ['message' => 'This tzl not found'];

            return Json::encode($result);
        }

    }

    public function actionTransfer()
    {
        Yii::$app->db->transaction(function () {

            \Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;

            $data = json_decode(file_get_contents("php://input"));

            $array = $data->devices;
            $devices = json_decode(json_encode($array), True);

            $from_id = $data->from_id;
            $to_id = $data->to_id;


            if ($to_id == 42) {

                if ($devices != '' && $from_id != '' && $to_id != '') {

                    foreach ($devices as $key => $value) {

                        $data = ReleasedDevices::find()->where(['id' => $value])->one();

                        ReceivedDevices::updateAll([
                            'received_from' => 2,
                            'border_port' => $data['sales_point'],
                            'received_from_staff' => $data['released_to'],
                            'received_at' => date('Y-m-d H:i:s'),
                            'received_status' => 1,
                            'received_by' => $from_id,
                            'view_status' => Devices::received_devices,
                        ], ['serial_no' => $data['serial_no']]);


                        $report = new ReceivedDevicesReport();
                        $report->serial_no = $data['serial_no'];
                        $report->received_from = 2;
                        $report->border_port = $data['sales_point'];
                        $report->received_from_staff = $data['released_to'];
                        $report->received_at = date('Y-m-d H:i:s');
                        $report->received_status = 1;
                        $report->received_by = $from_id;
                        $report->save();

                        ReleasedDevices::updateAll(['view_status' => Devices::received_devices], ['serial_no' => $data['serial_no']]);

                    }
                    $result = ['status' => 'true', 'message' => 'successfully'];

                    return Json::encode($result);

                } else {

                    $result = ['status' => ' Failed To Find User'];

                    return Json::encode($result);
                }


            } else {

                if ($devices != '' && $from_id != '' && $to_id != '') {

                    foreach ($devices as $key => $value) {

                        $data = ReleasedDevices::find()->where(['id' => $value])->one();

                        ReleasedDevices::updateAll([
                            'transferred_to' => $to_id,
                            'transferred_from' => $from_id,
                            'transferred_by' => $from_id,
                            'released_to' => $to_id,
                            'transferred_date' => date('Y-m-d H:i:s'),
                            'status' => 2
                        ],
                            ['id' => $data['id']]);

                        ReleasedDevicesReport::updateAll([
                            'transferred_to' => $to_id,
                            'transferred_from' => $from_id,
                            'transferred_by' => $from_id,
                            'released_to' => $to_id,
                            'transferred_date' => date('Y-m-d H:i:s'),
                            'status' => 2
                        ],
                            ['id' => $data['id']]);

                        $result = ['status' => 'true', 'message' => 'successfully'];

                        return Json::encode($result);

                    }

                    //   $result = ['status' => 'true', 'message' => 'successfully'];

                    //  return Json::encode($result);


                } else {

                    $result = ['status' => 'Failed'];

                    return Json::encode($result);
                }

            }


        });

    }

    public function actionSalesReport()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;

        $data = json_decode(file_get_contents("php://input"));

        $date_from = $data->from_date;
        $user_id = $data->user_id;
        $to_date = $data->to_date;
        $customer = 1;
        $customer2 = 2;
        if ($date_from != '' && $user_id != '' && $to_date != '') {

            $query = new Query;
            $query->select(['count(u.id) as total_number_bill_sales, sum(u.amount) total_amount_bill_sales,
                         count(k.id) as total_number_cash_sales, sum(k.amount) as total_amount_cash_sales '])
                ->from('sales_trips a')
                ->andWhere(['between', 'DATE_FORMAT(sale_date, "%Y-%m-%d")', $date_from, $to_date])
                ->join('left join',
                    'sales_trips as u',
                    'u.id =a.id  AND u.customer_type_id=' . $customer2 . '')
                ->join('left join',
                    'sales_trips as k',
                    'k.id =a.id  AND k.customer_type_id=' . $customer . '')
                ->where(['a.sales_person' => $user_id]);

            $query1 = new Query;
            $query1->select(['count(id) remaining_devices'])
                ->from('released_devices')
                ->where(['released_to' => $user_id]);

            $command1 = $query1->createCommand();
            $post1 = $command1->queryAll();

            $command = $query->createCommand();
            $post = $command->queryAll();

            if (!empty($post)) {
                $result = ['status' => $post, $post1];

                return Json::encode($result);
            } else {
                $result = ['status' => 'No found'];

                return Json::encode($result);
            }
        } else {

            $result = ['status' => ' not found'];

            return Json::encode($result);
        }


    }


    public function actionTransitDevices($user_id)
    {

        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;

        $userAllocated = BorderPortUser::find()->where(['name' => $user_id])->one();
        $query = new Query;
        $query->select(['id', 'serial_no as name'])
            ->from('stock_devices')
            ->where(['status' => StockDevices::not_deactivated])
            ->andWhere(['location_from' => $userAllocated['border_port']]);

        $command = $query->createCommand();
        $response['devices'] = $command->queryAll();


        if ($response != '') {

            return $response;
        } else {
            return array('statusCode ' => [
                // $model->getErrors(),
                'message' => 'Not Found',
                'status' => '403',
            ]);
        }
    }


    public function actionConfirmReceive()
    {
        Yii::$app->db->transaction(function () {

            \Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;

            $data = json_decode(file_get_contents("php://input"));

            $array = $data->devices;
            $devices = json_decode(json_encode($array), True);
            $user_id = $data->user_id;


            if ($devices != '' && $user_id != '') {

                $e = InTransit::find()->select(['serial_no', 'border'])->where(['in', 'id', $devices])->all();

                if (!empty($e)) {

                    foreach ($e as $singleProductlineID) {

                        AwaitingReceive::updateAll([
                            'received_from' => 1,
                            'received_status' => 1,
                            'received_by' => $user_id,
                            'received_at' => date('Y-m-d H:i:s'),
                            'view_status' => Devices::awaiting_receive,
                            'border_port' => $singleProductlineID->border,
                        ], ['serial_no' => $singleProductlineID->serial_no]);

                        InTransit::updateAll(['view_status' => Devices::awaiting_receive], ['serial_no' => $singleProductlineID->serial_no]);

                        $report = new AwaitingReceiveReport();
                        $report->serial_no = $singleProductlineID->serial_no;
                        $report->received_from = 1;
                        $report->border_port = $singleProductlineID->border;
                        $report->received_by = $user_id;
                        $report->received_at = date('Y-m-d H:i:s');
                        // $report->isNewRecord = true;
                        $report->received_status = 1;
                        $report->save();

                    }

                    $result = ['message' => 'Successfully Confirmed'];
                    return Json::encode($result);

                } else {
                    $result = ['status' => 'false', 'message' => 'No device found'];
                    return Json::encode($result);
                }


            } else {

                $result = ['status' => ' not device found'];

                return Json::encode($result);
            }
        });

    }

    public function actionDeviceToConfirm($user_id)
    {

        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;

        $userAllocated = BorderPortUser::find()->where(['name' => $user_id])->one();
        $query = new Query;
        $query->select(['id', 'serial_no as name'])
            ->from('in_transit')
            ->where(['border' => $userAllocated['border_port']])->andWhere(['view_status' => Devices::in_transit]);

        $command = $query->createCommand();
        $response['devices'] = $command->queryAll();


        if ($response != '') {

            return $response;
        } else {
            return array('statusCode ' => [
                // $model->getErrors(),
                'message' => 'Not Found',
                'status' => '403',
            ]);
        }
    }



    protected function verbs()
    {
        return [
            'login' => ['POST'],
            'released' => ['GET'],
            'sale-add' => ['POST'],
            'borders' => ['GET'],
            'devices' => ['GET'],
            'points' => ['GET'],
            'staffs' => ['GET'],
            'customers' => ['GET'],
            'transfer' => ['POST'],
            'sales-report' => ['POST'],
            'confirm-receive' => ['POST'],
            'transit-devices' => ['GET'],
            'device-to-confirm' => ['GET'],

        ];
    }
}