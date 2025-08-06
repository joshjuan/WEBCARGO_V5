<?php

namespace frontend\controllers;

ini_set('memory_limit', '10024M');

use frontend\models\AwaitingReceive;
use frontend\models\AwaitingReceiveReport;
use frontend\models\BorderPort;
use frontend\models\BorderPortUser;
use frontend\models\DeviceLogs;
use frontend\models\Devices;
use frontend\models\InTransit;
use frontend\models\InTransitReport;
use frontend\models\Location;
use backend\models\Receipt;
use frontend\models\ReceivedDevices;
use frontend\models\ReceivedDevicesReport;
use frontend\models\ReleasedDevices;
use frontend\models\ReleasedDevicesReport;
use frontend\models\SalesTrips;
use frontend\models\SalesTripSlaves;
use frontend\models\StockDevices;
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


    public $modelClass = 'frontend\models\SalesTransactions';


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

        if (!empty($user)) {

            $userAll = User::find()->where(['username' => $model->username])->one();
            $userAllocated = BorderPortUser::find()->where(['name' => $userAll['id']])->one();
            if ($userAllocated) {
                $location_name = BorderPort::find()->where(['id' => $userAllocated['border_port']])->one();
                $name = $location_name['name'];
                $id = $userAllocated['border_port'];
            } else {
                $name = NULL;
                $id = NULL;
            }

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
                    'border_port' => $id,
                    'border_port_name' => $name,
                    'role' => Yii::$app->user->identity->role,
                    'branch' => Yii::$app->user->identity->branch,

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


//    public function actionReleased($id)
//    {
//
//        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;
//
//        $data = Devices::find()->where(['id' => $id])->andWhere(['type' => [1, 2]])->all();
//        if (count($data) > 0) {
//            return array('data' => $data);
//
//
//        } else {
//            return array('status' => false, 'data' => 'No Data');
//        }
//    }

    public function actionSaleAddOld()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;

        $model = new SalesTrips();
        $data = json_decode(file_get_contents("php://input"));

        if (isset($data->slaves)) {
            $slaves_array = $data->slaves;
            $devices = json_decode(json_encode($slaves_array), True);
        } else {
            $slaves_array = array();
            $devices = array();
        }


        //print_r($data);
        //die;

        // $slave_serial = $devices->serial_no;

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

        if (isset($data->customer_name)) {
            $model->customer_name = $data->customer_name;
        }

        $model->agent = $data->agent;
        $model->cancelled_by = '';
        $model->edited_by = '';
        $model->edited_at = '';
        $model->date_cancelled = '';
        $model->sale_type = $data->sale_type;
        $model->sale_id = $data->sale_id;


        $branch = Devices::find()->where(['serial_no' => $model->serial_no])->one();
        $model->branch = $branch['branch'];
        $model->type = $branch['type'];

        $branch_user = User::find()->where(['id' => $data->sales_person])->one();
        $branch_id = $branch_user['branch'];
        $model->branch = $branch_id;
        //  print_r($devices);
        //  exit;

        ############################## NORMAL SALES #####################################
        if ($model->sale_type == 1) {

            $check_tzl = SalesTrips::find()->where(['tzl' => $model->tzl])->one();

            if (empty($check_tzl)) {


                ############################## CASH SALES #####################################
                if ($data->customer_type_id == 1) {
                    $model->receipt_number = $model->sales_person . Receipt::findCash();
                    if ($model->save()) {

                        Devices::updateAll([
                            'view_status' => Devices::in_transit,
                            'tzl' => $model->tzl,
                            'border_port' => $model->border_id,
                            'sales_person' => $model->sales_person,
                            'vehicle_no' => $model->vehicle_number,
                            'container_number' => $model->container_number,
                            'created_by' => $model->sales_person,
                            'sale_id' => $model->id,
                            'received_at' => date('Y-m-d H:i:s'),
                        ], ['serial_no' => $model->serial_no]);

                        Yii::$app->db->createCommand()
                            ->insert('in_transit_report', [
                                'serial_no' => $model->serial_no,
                                'tzl' => $model->tzl,
                                'border' => $model->border_id,
                                'sales_person' => $model->sales_person,
                                'vehicle_no' => $model->vehicle_number,
                                'container_number' => $model->container_number,
                                'created_by' => $model->sales_person,
                                'sale_id' => $model->id,
                                'type' => $model->type,
                                'branch' => $model->branch,
                                'created_at' => date('Y-m-d H:i:s'),
                            ])->execute();

                        $this->actionReturn();


                        if ($slaves_array != '') {
                            foreach ($devices as $key => $value) {

                                $data = Devices::find()->where(['id' => $value])->one();

                                //  $branch = Devices::find()->where(['serial_no' => $data['serial_no']])->one();
                                $type = $data['type'];

                                Yii::$app->db->createCommand()
                                    ->insert('sales_trip_slaves', [
                                        'serial_no' => $data['serial_no'],
                                        'sale_id' => $model->id,
                                        'branch' => $model->branch,
                                        'created_at' => date('Y-m-d H:i:s'),
                                    ])->execute();

                                Devices::updateAll([
                                    'view_status' => Devices::in_transit,
                                    'tzl' => $model->tzl,
                                    'border_port' => $model->border_id,
                                    'sales_person' => $model->sales_person,
                                    'vehicle_no' => $model->vehicle_number,
                                    'container_number' => $model->container_number,
                                    'created_by' => $model->sales_person,
                                    'sale_id' => $model->id,
                                    'received_at' => date('Y-m-d H:i:s'),
                                ], ['serial_no' => $data['serial_no']]);

                                Yii::$app->db->createCommand()
                                    ->insert('in_transit_report', [
                                        'serial_no' => $data['serial_no'],
                                        'tzl' => $model->tzl,
                                        'border' => $model->border_id,
                                        'sales_person' => $model->sales_person,
                                        'vehicle_no' => $model->vehicle_number,
                                        'sale_id' => $model->id,
                                        'type' => $type,
                                        'branch' => $model->branch,
                                        'container_number' => $model->container_number,
                                        'created_by' => $model->sales_person,
                                        'created_at' => date('Y-m-d H:i:s'),
                                    ])->execute();

                            }

                            $response = [
                                'receipt_number' => $model->receipt_number,
                                'amount' => $model->amount,
                                'sale_id' => $model->id,
                            ];

                            $result = ['receipt' => $response];

                            return Json::encode($result);
                        } else {
                            $response = [
                                'receipt_number' => $model->receipt_number,
                                'amount' => $model->amount,
                                'sale_id' => $model->id,
                            ];

                            $result = ['receipt' => $response];

                            return Json::encode($result);
                        }


                    } else {
                        $result = [
                            'message' => $model->getErrors(),
                            'error' => 'error',
                        ];
                        return Json::encode($result);
                    }


                } ############################## BILL SALES #####################################
                elseif ($data->customer_type_id == 2) {
                    $model->receipt_number = $model->sales_person . Receipt::findBill();


                    if ($model->save()) {

                        Devices::updateAll([
                            'view_status' => Devices::in_transit,
                            'tzl' => $model->tzl,
                            'border_port' => $model->border_id,
                            'sales_person' => $model->sales_person,
                            'vehicle_no' => $model->vehicle_number,
                            'container_number' => $model->container_number,
                            'created_by' => $model->sales_person,
                            'sale_id' => $model->id,
                            'received_at' => date('Y-m-d H:i:s'),
                        ], ['serial_no' => $model->serial_no]);


                        Yii::$app->db->createCommand()
                            ->insert('in_transit_report', [
                                'serial_no' => $model->serial_no,
                                'tzl' => $model->tzl,
                                'border' => $model->border_id,
                                'sales_person' => $model->sales_person,
                                'vehicle_no' => $model->vehicle_number,
                                'container_number' => $model->container_number,
                                'created_by' => $model->sales_person,
                                'sale_id' => $model->id,
                                'branch' => $model->branch,
                                'type' => $model->type,
                                'created_at' => date('Y-m-d H:i:s'),
                            ])->execute();

                        $this->actionReturn();

                        if ($slaves_array != '') {
                            foreach ($devices as $key => $value) {

                                $data = Devices::find()->where(['id' => $value])->one();
                                // $branch = Devices::find()->where(['serial_no' => $data['serial_no']])->one();
                                $type = $data['type'];

                                Yii::$app->db->createCommand()
                                    ->insert('sales_trip_slaves', [
                                        'serial_no' => $data['serial_no'],
                                        'sale_id' => $model->id,
                                        'branch' => $model->branch,
                                        'created_at' => date('Y-m-d H:i:s'),
                                    ])->execute();

                                Devices::updateAll([
                                    'view_status' => Devices::in_transit,
                                    'tzl' => $model->tzl,
                                    'border_port' => $model->border_id,
                                    'sales_person' => $model->sales_person,
                                    'vehicle_no' => $model->vehicle_number,
                                    'container_number' => $model->container_number,
                                    'created_by' => $model->sales_person,
                                    'sale_id' => $model->sale_id,
                                    'received_at' => date('Y-m-d H:i:s'),
                                ], ['serial_no' => $data['serial_no']]);


                                Yii::$app->db->createCommand()
                                    ->insert('in_transit_report', [
                                        'serial_no' => $data['serial_no'],
                                        'tzl' => $model->tzl,
                                        'border' => $model->border_id,
                                        'sales_person' => $model->sales_person,
                                        'vehicle_no' => $model->vehicle_number,
                                        'sale_id' => $model->sale_id,
                                        'branch' => $model->branch,
                                        'type' => $type,
                                        'container_number' => $model->container_number,
                                        'created_by' => $model->sales_person,
                                        'created_at' => date('Y-m-d H:i:s'),
                                    ])->execute();

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


                } else {
                    $result = ['message' => $model->getErrors()];
                    return Json::encode($result);
                }

                // end checking TZL
            } else {
                //  $result = ['message' => 'TZL exist'];
                //   return Json::encode($result);
                $response = [
                    'receipt_number' => $check_tzl['receipt_number'],
                    'amount' => $model->amount,
                    'sale_id' => $model->sale_id,
                ];

                $result = ['receipt' => $response];

                return Json::encode($result);
            }


        } ############################## EDIT  SALES #####################################
        elseif ($model->sale_type == 3) {

            $receipt = SalesTrips::find()->where(['id' => $model->sale_id])->one();

            Devices::updateAll(['view_status' => Devices::released_devices], ['serial_no' => $receipt['serial_no']]);
            $slaves_data = SalesTripSlaves::find()
                ->select('serial_no')
                ->where(['sale_id' => $model->sale_id])
                ->all();
            //UPDATE SLAVES TO AVAILABLE
            $check_last = ReleasedDevicesReport::find()
                ->where(['serial_no' => $model->serial_no])
                ->orderBy('id DESC')
                ->one();

            Devices::updateAll(['view_status' => Devices::released_devices, 'released_date' => $check_last['released_date'],
                'released_by' => $check_last['released_by'], 'released_to' => $check_last['released_to'], 'border_port' => $check_last['sales_point']],
                ['in', 'serial_no', $slaves_data]);

            SalesTripSlaves::updateAll(['status' => 0], ['sale_id' => $model->sale_id]);

            $edited = 'EDITED';

            $edit = SalesTrips::updateAll([
                'tzl' => $receipt['tzl'] . $edited,
                'sale_id' => $model->sale_id,
                'sale_type' => $model->sale_type,
                'edited_at' => date('Y-m-d H:i:s'),
                'edited_by' => $model->sales_person,
                'trip_status' => SalesTrips::EDITED,
            ],
                ['id' => $model->sale_id]);

            if ($edit) {
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

                $model->branch = $branch['branch'];
                $model->type = $branch['type'];
                if ($model->save()) {

                    Devices::updateAll([
                        'view_status' => Devices::in_transit,
                        'tzl' => $model->tzl,
                        'border_port' => $model->border_id,
                        'sales_person' => $model->sales_person,
                        'vehicle_no' => $model->vehicle_number,
                        'container_number' => $model->container_number,
                        'created_by' => $model->sales_person,
                        'sale_id' => $model->id,
                        'created_at' => date('Y-m-d H:i:s'),
                    ], ['serial_no' => $model->serial_no]);

                    Yii::$app->db->createCommand()
                        ->insert('in_transit_report', [
                            'serial_no' => $model->serial_no,
                            'tzl' => $model->tzl,
                            'border' => $model->border_id,
                            'sales_person' => $model->sales_person,
                            'vehicle_no' => $model->vehicle_number,
                            'container_number' => $model->container_number,
                            'created_by' => $model->sales_person,
                            'sale_id' => $model->id,
                            'type' => $model->type,
                            'branch' => $model->branch,
                            'created_at' => date('Y-m-d H:i:s'),
                        ])->execute();

                    $this->actionReturn();


                    if ($slaves_array != '') {
                        foreach ($devices as $key => $value) {

                            $data = Devices::find()->where(['id' => $value])->one();

                            $branch = Devices::find()->where(['serial_no' => $data['serial_no']])->one();
                            $type = $branch['type'];

                            Yii::$app->db->createCommand()
                                ->insert('sales_trip_slaves', [
                                    'serial_no' => $data['serial_no'],
                                    'sale_id' => $model->id,
                                    'branch' => $model->branch,
                                    'created_at' => date('Y-m-d H:i:s'),
                                ])->execute();

                            Devices::updateAll([
                                'view_status' => Devices::in_transit,
                                'tzl' => $model->tzl,
                                'border_port' => $model->border_id,
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
                                    'type' => $type,
                                    'branch' => $model->branch,
                                    'container_number' => $model->container_number,
                                    'created_by' => $model->sales_person,
                                    'created_at' => date('Y-m-d H:i:s'),
                                ])->execute();

                        }

                        $response = [
                            'receipt_number' => $model->receipt_number,
                            'amount' => $model->amount,
                            'sale_id' => $model->id,
                        ];

                        $result = ['receipt' => $response];

                        return Json::encode($result);
                    } else {
                        $response = [
                            'receipt_number' => $model->receipt_number,
                            'amount' => $model->amount,
                            'sale_id' => $model->id,
                        ];

                        $result = ['receipt' => $response];

                        return Json::encode($result);
                    }


                } else {
//                    $result = ['message' => $model->getErrors()];
//                    return Json::encode($result);

                    $response = [
                        'receipt_number' => $model->receipt_number,
                        'amount' => $model->amount,
                        'sale_id' => $model->id,
                    ];

                    $result = ['receipt' => $response];

                    return Json::encode($result);
                }
            } else {
                $result = ['message' => $model->getErrors()];
                return Json::encode($result);
            }

        } else {

            $result = ['message' => ' failed'];
            return Json::encode($result);
        }

    }

    public function actionSaleAdd()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $data = json_decode(file_get_contents("php://input"));

        if (isset($data->slaves)) {
            $slaves_array = $data->slaves;
            $devices = json_decode(json_encode($slaves_array), True);
        } else {
            $slaves_array = array();
            $devices = array();
        }

        try {
            $deviceTransLog = new DeviceLogs();
            $deviceTransLog->data = json_decode(json_encode(file_get_contents("php://input")), true);
            $deviceTransLog->user = $data->sales_person;
            $deviceTransLog->tzdl = $data->tzl;
            $deviceTransLog->datatime = date('Y-m-d H:i:s');
            $deviceTransLog->save(false);
        } catch (Exception $e) {
        }

        $compare = SalesTrips::find()
            ->select(['sale_date', 'tzl', 'start_date_time', 'vehicle_number', 'chasis_number',
                'driver_name', 'border_id', 'trip_status', 'driver_number', 'serial_no', 'amount',
                'currency', 'gate_number', 'end_date', 'sales_person', 'passport', 'container_number',
                'vehicle_type_id', 'customer_type_id', 'customer_id', 'company_name', 'agent', 'sale_type',
                'sale_id', 'type', 'branch', 'receipt_number'])
            ->where(['tzl' => $data->tzl])
            // ->asArray()
            ->one();

        //  if ($data->sale_type == 1) {



        SalesTripSlaves::updateAll([
            'status' => 0
        ],
            ['in', 'sale_id', $data->sale_id]);

        if ($compare) {
            $prev=ReleasedDevicesReport::find()
                ->where(['serial_no' => $compare['serial_no']])
                ->orderBy(['id' => SORT_DESC])
                ->one();


            Devices::updateAll([
                'view_status' => Devices::released_devices,
                'border_port' => $prev['sales_point']

            ],
                ['tzl' => $data->tzl]);


            Devices::updateAll([
                'view_status' => Devices::released_devices,
                 'border_port' => $prev['sales_point']
            ],
                ['in', 'sale_id', $data->sale_id]);

        } else {

            Devices::updateAll([
                'view_status' => Devices::released_devices

            ],
                ['tzl' => $data->tzl]);

            SalesTripSlaves::updateAll([
                'status' => 0],
                ['in', 'sale_id', $data->sale_id]);

            Devices::updateAll([
                'view_status' => Devices::released_devices],
                ['in', 'sale_id', $data->sale_id]);
        }


        SalesTrips::updateAll([
            'tzl' => $data->tzl . 'EDITED',
            'sale_id' => $data->sale_id,
            'sale_type' => $data->sale_type,
            'edited_at' => date('Y-m-d H:i:s'),
            'edited_by' => $data->sales_person,
            'trip_status' => SalesTrips::EDITED,
        ],
            ['tzl' => $data->tzl]);

        $model = new SalesTrips();
        $model->sale_date = date('Y-m-d H:i:s', strtotime($data->sale_date));
        $model->tzl = $data->tzl;
        $model->start_date_time = date('Y-m-d H:i:s', strtotime($data->start_date_time));
        $model->vehicle_number = $data->vehicle_number;
        $model->chasis_number = $data->chasis_number;
        $model->driver_name = $data->driver_name;
        $model->border_id = $data->border_id;
        $model->trip_status = $data->trip_status;
        $model->driver_number = $data->driver_number;
        $model->serial_no = $data->serial_no;
        $model->currency = $data->currency;
        $model->gate_number = $data->gate_number;
        $model->end_date = $data->end_date;
        $model->sales_person = $data->sales_person;
        $model->passport = $data->passport;
        $model->container_number = $data->container_number;
        $model->vehicle_type_id = $data->vehicle_type_id;
        $model->customer_type_id = $data->customer_type_id;

        $model->company_name = $data->company_name;
        $customer = User::findOne(['id' => $data->customer_id]);
        if ($customer) {
            $model->customer_id = $customer->id;
        }

        if (isset($data->customer_name)) {
            $model->customer_name = $data->customer_name;
        }

        $model->agent = $data->agent;
//            $model->cancelled_by = '';
//            $model->edited_by = '';
//            $model->edited_at = '';
//            $model->date_cancelled = '';
        $model->sale_type = $data->sale_type;
        $model->sale_id = $data->sale_id;

        $branch = Devices::find()
            ->where(['serial_no' => $model->serial_no])
            ->one();
        // $model->branch = $branch['branch'];
        $model->type = $branch['type'];

        $branch_user = User::find()
            ->where(['id' => $data->sales_person])
            ->one();
        $branch_id = $branch_user['branch'];
        $model->branch = $branch_id;
        if ($data->customer_type_id == 1) {
            $model->receipt_number = $model->sales_person . Receipt::findCash();
            $model->amount = $data->amount;
        } elseif ($data->customer_type_id == 2) {
            $model->receipt_number = $model->sales_person . Receipt::findBill();
            $model->amount = 0.00;
        }

        if ($compare) {
            $old = $compare->getAttributes(['sale_date', 'tzl', 'start_date_time', 'vehicle_number', 'chasis_number',
                'driver_name', 'border_id', 'trip_status', 'driver_number', 'serial_no', 'amount',
                'currency', 'gate_number', 'end_date', 'sales_person', 'passport', 'container_number',
                'vehicle_type_id', 'customer_type_id', 'customer_id', 'company_name', 'agent', 'sale_type',
                'sale_id', 'type', 'branch',]);
            $incoming = $model->getAttributes(['sale_date', 'tzl', 'start_date_time', 'vehicle_number', 'chasis_number',
                'driver_name', 'border_id', 'trip_status', 'driver_number', 'serial_no', 'amount',
                'currency', 'gate_number', 'end_date', 'sales_person', 'passport', 'container_number',
                'vehicle_type_id', 'customer_type_id', 'customer_id', 'company_name', 'agent', 'sale_type',
                'sale_id', 'type', 'branch']);

            if ($old == $incoming) {
                $response = [
                    'receipt_number' => $compare->getAttribute('receipt_number'),
                    'amount' => $compare->getAttribute('amount'),
                    'sale_id' => $compare->getAttribute('id'),
                ];

                $result = ['receipt' => $response];

                return $result;

            }
        }

        if ($model->save()) {
            Devices::updateAll([
                'view_status' => Devices::in_transit,
                'tzl' => $model->tzl,
                'border_port' => $model->border_id,
                'sales_person' => $model->sales_person,
                'vehicle_no' => $model->vehicle_number,
                'container_number' => $model->container_number,
                'created_by' => $model->sales_person,
                'sale_id' => $model->id,
                'received_at' => date('Y-m-d H:i:s'),
            ], ['serial_no' => $model->serial_no]);


            Yii::$app->db->createCommand()
                ->insert('in_transit_report', [
                    'serial_no' => $model->serial_no,
                    'tzl' => $model->tzl,
                    'border' => $model->border_id,
                    'sales_person' => $model->sales_person,
                    'vehicle_no' => $model->vehicle_number,
                    'container_number' => $model->container_number,
                    'created_by' => $model->sales_person,
                    'sale_id' => $model->id,
                    'branch' => $model->branch,
                    'type' => $model->type,
                    'created_at' => date('Y-m-d H:i:s'),
                ])->execute();

            if ($slaves_array != '') {
                foreach ($devices as $key => $value) {

                    $data = Devices::find()->where(['id' => $value])->one();
                    // $branch = Devices::find()->where(['serial_no' => $data['serial_no']])->one();
                    $type = $data['type'];

                    Yii::$app->db->createCommand()
                        ->insert('sales_trip_slaves', [
                            'serial_no' => $data['serial_no'],
                            'sale_id' => $model->id,
                            'branch' => $model->branch,
                            'created_at' => date('Y-m-d H:i:s'),
                        ])->execute();

                    Devices::updateAll([
                        'view_status' => Devices::in_transit,
                        'tzl' => $model->tzl,
                        'border_port' => $model->border_id,
                        'sales_person' => $model->sales_person,
                        'vehicle_no' => $model->vehicle_number,
                        'container_number' => $model->container_number,
                        'created_by' => $model->sales_person,
                        'sale_id' => $model->sale_id,
                        'received_at' => date('Y-m-d H:i:s'),
                    ], ['serial_no' => $data['serial_no']]);


                    Yii::$app->db->createCommand()
                        ->insert('in_transit_report', [
                            'serial_no' => $data['serial_no'],
                            'tzl' => $model->tzl,
                            'border' => $model->border_id,
                            'sales_person' => $model->sales_person,
                            'vehicle_no' => $model->vehicle_number,
                            'sale_id' => $model->sale_id,
                            'branch' => $model->branch,
                            'type' => $type,
                            'container_number' => $model->container_number,
                            'created_by' => $model->sales_person,
                            'created_at' => date('Y-m-d H:i:s'),
                        ])->execute();

                }

            }
            $response = [

                'receipt_number' => $model->receipt_number,
                'amount' => $model->amount,
                'sale_id' => $model->id,

            ];

            $result = ['receipt' => $response];

            return $result;


        } else {

            $result = ['message' => $model->getErrors()];
            return $result;
        }


//        } elseif ($data->sale_type == 3) {
//
//
//        } else {
//
//            $result = ['message' => "Invalid Sale Data Type"];
//            return $result;
//
//        }


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

    public function actionStaff()
    {
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;
        $query = new Query;
        $query->select(['id', 'full_name as names', 'id as uid'])
            ->from('user')
            ->where(['user_type' => \frontend\models\User::PORT_STAFF]);
        $command = $query->createCommand();
        $response['staffs'] = $command->queryAll();
        return $response;

    }

    public function actionStaffs($user_id)
    {
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;
        $user = User::findOne(['id' => $user_id]);
        $branch = $user->branch;
        $query = new Query;
        $query->select(['id', 'full_name as names', 'id as uid'])
            ->from('user')
            ->where(['user_type' => \frontend\models\User::PORT_STAFF])
            ->andWhere(['branch' => $branch]);

        $command = $query->createCommand();
        $response['staffs'] = $command->queryAll();
        return $response;

    }

    public function actionReleased($user_id)
    {
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;

        $query = new Query;
        $query->select(['id', 'serial_no as name'])
            ->from('devices')
            ->where(['released_to' => $user_id])
            ->andWhere(['in', 'type', [1, 2]])
            ->andWhere(['view_status' => Devices::released_devices]);
        $command = $query->createCommand();
        $response['devices'] = $command->queryAll();
        return $response;
    }


    public function actionChild($user_id)
    {
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;

        $query = new Query;
        $query->select(['id', 'serial_no as name'])
            ->from('devices')
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

        if ($updateSalesTrip != null) {

            $gateName = BorderPort::find()->where(['id' => $updateSalesTrip['gate_number']])->one();
            $device = Devices::find()->where(['serial_no' => $updateSalesTrip['serial_no']])->one();

            //$companyName=User::findOne(['id'=>$updateSalesTrip['company_id']])


            $username = User::find()->where(['id' => $updateSalesTrip['sales_person']])->one();

            $customer = User::find()->where(['id' => $updateSalesTrip['customer_id']])->one();

            $slaves = SalesTripSlaves::find()->select(['id', 'serial_no'])
                ->where(['sale_id' => $updateSalesTrip->id])
                ->andWhere(['status' => 1])
                ->all();
            $slave = ArrayHelper::getColumn($slaves, 'serial_no');


            $results = ArrayHelper::toArray($slaves, [
                'data' => [
                    'id',
                    'name',
                ],
            ]);

            if (!empty($customer)) {
                $company_name = $customer['company_name'];
                $customer_id = $customer['id'];
            } else {
                $company_name = null;
                $customer_id = null;
            }

            if (empty($slave)) {

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
                    'company_name' => $company_name,
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
                        'customer_id' => $customer_id,
                        'customer_name' => $company_name,
                    ],
                    'driver_number' => $updateSalesTrip['driver_number'],
                    'receipt_number' => $updateSalesTrip['receipt_number'],
                    'chasis_number' => $updateSalesTrip['chasis_number'],
                    'vehicle_type_id' => $updateSalesTrip['vehicle_type_id'],
                    'customer_type_id' => $updateSalesTrip['customer_type_id'],
                    'start_date_time' => $updateSalesTrip['start_date_time'],
                    'end_date' => $updateSalesTrip['end_date'],
                ]
                ];
                return Json::encode($result);
            } else {

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
                    'company_name' => $company_name,
                    'agent' => $updateSalesTrip['agent'],
                    'currency' => $updateSalesTrip['currency'],
                    'vehicle_number' => $updateSalesTrip['vehicle_number'],
                    'tzl' => $updateSalesTrip['tzl'],
                    'border_port' => [
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
                        'customer_id' => $customer_id,
                        'customer_name' => $company_name,
                    ],
                    'driver_number' => $updateSalesTrip['driver_number'],
                    'receipt_number' => $updateSalesTrip['receipt_number'],
                    'chasis_number' => $updateSalesTrip['chasis_number'],
                    'vehicle_type_id' => $updateSalesTrip['vehicle_type_id'],
                    'customer_type_id' => $updateSalesTrip['customer_type_id'],
                    'start_date_time' => $updateSalesTrip['start_date_time'],
                    'end_date' => $updateSalesTrip['end_date'],
                    'slaves' => $results
                ]
                ];
                return Json::encode($result);

            }

        } else {

            $result = ['message' => 'This tzl not found'];

            return Json::encode($result);
        }


    }


    public function actionEditSaleNew()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;

        $data = json_decode(file_get_contents("php://input"));
        $tzl = $data->tzdl;
        $updateSalesTrip = SalesTrips::find()->where(['tzl' => $tzl])->one();

        $gateName = BorderPort::find()->where(['id' => $updateSalesTrip['gate_number']])->one();
        $device = Devices::find()->where(['serial_no' => $updateSalesTrip['serial_no']])->one();
        $customer = User::find()->where(['id' => $updateSalesTrip['customer_name']])->one();
        $username = User::find()->where(['id' => $updateSalesTrip['sales_person']])->one();
        $slaves = SalesTripSlaves::find()
            ->select(['serial_no'])
            ->where(['sale_id' => $updateSalesTrip->id])->asArray()->all();
        //  print_r($slaves);
        // die;

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

        //  if ($tzl == '') {
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
                'border_port' => [
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
                    'customer_name' => $customer['company_name']
                ],
                'driver_number' => $updateSalesTrip['driver_number'],
                'receipt_number' => $updateSalesTrip['receipt_number'],
                'chasis_number' => $updateSalesTrip['chasis_number'],
                'vehicle_type_id' => $updateSalesTrip['vehicle_type_id'],
                'customer_type_id' => $updateSalesTrip['customer_type_id'],
                'start_date_time' => $updateSalesTrip['start_date_time'],
                'end_date' => $updateSalesTrip['end_date'],
                'slaves' => $slaves
            ]
            ];

            return Json::encode($result);

        } else {

            $result = ['message' => 'This tzl not found'];

            return Json::encode($result);
        }
//        } else {
//            $result = ['message' => 'You can not edit Sales before cancellation'];
//
//            return Json::encode($result);
//        }

    }


    ######################################### REPRINT SALE RECORD #######################################
    public function actionReprint()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;

        $data = json_decode(file_get_contents("php://input"));

        $tzl = $data->tzdl;

        $updateSalesTrip = SalesTrips::find()->where(['tzl' => $tzl])->one();
        $DateTime = $updateSalesTrip->sale_date;
        $saleDate = date('Y-m-d', strtotime($DateTime));
        $two_days_before = date('Y-m-d', strtotime('-2 days'));

        //   if ($saleDate > $two_days_before) {


        $gateName = BorderPort::find()->where(['id' => $updateSalesTrip['gate_number']])->one();
        $borderName = BorderPort::find()->where(['id' => $updateSalesTrip['border_id']])->one();
        $device = Devices::find()->where(['serial_no' => $updateSalesTrip['serial_no']])->one();
        $customerdata = User::find()->where(['id' => $updateSalesTrip['customer_id']])->one();
        if($customerdata){
            $customerID=$customerdata['id'];
            $customerName=$customerdata['company_name'];
        }
        else{
            $customerID='';
            $customerName='';
        }
        $username = User::find()->where(['id' => $updateSalesTrip['sales_person']])->one();
        $slaves = SalesTripSlaves::find()->select(['serial_no'])->where(['sale_id' => $updateSalesTrip['id']])->all();

        if (!empty($updateSalesTrip)) {
            $result = ['receipt' => [
                'port' => [
                    'port_point_id' => $updateSalesTrip['gate_number'],
                    'port_point_name' => $gateName['name']
                ],
                'driver_name' => $updateSalesTrip['driver_name'],
                'sale_id' => $updateSalesTrip['id'],
                'user_id' => $updateSalesTrip['sales_person'],
               // 'username' => $username['username'],
                'username' => $username['full_name'],
                'amount' => $updateSalesTrip['amount'],
                'company_name' => $updateSalesTrip['company_name'],
                'agent' => $updateSalesTrip['agent'],
                'currency' => $updateSalesTrip['currency'],
                'vehicle_number' => $updateSalesTrip['vehicle_number'],
                'tzl' => $updateSalesTrip['tzl'],
                'border' => [
                    'border_id' => $updateSalesTrip['gate_number'],
                    'border_name' => $borderName['name']
                ],
                'device' => [
                    'device_id' => $device['id'],
                    'device_serial' => $updateSalesTrip['serial_no']
                ],
                'passport' => $updateSalesTrip['passport'],
                'container_number' => $updateSalesTrip['container_number'],
                'customer' => [
                    'customer_id' => $customerID,
                    'customer_name' => $customerName
                ],
                'driver_number' => $updateSalesTrip['driver_number'],
                'receipt_number' => $updateSalesTrip['receipt_number'],
                'chasis_number' => $updateSalesTrip['chasis_number'],
                'vehicle_type_id' => $updateSalesTrip['vehicle_type_id'],
                'customer_type_id' => $updateSalesTrip['customer_type_id'],
                'start_date_time' => $updateSalesTrip['start_date_time'],
                'end_date' => $updateSalesTrip['end_date'],
                'slaves' => $slaves,
            ]
            ];

            return Json::encode($result);

        } else {

            $result = ['message' => 'This tzl not found'];

            return Json::encode($result);
        }
//        }
//        else {
//            $result = ['message' => 'Edit hours expired'];
//
//            return Json::encode($result);
//        }

    }

    public function actionTransfer()
    {

        \Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;

        $data = json_decode(file_get_contents("php://input"));

        $array = $data->devices;
        $devices = json_decode(json_encode($array), True);

        $from_id = $data->from_id;
        $to_id = $data->to_id;

// return to await receive
        if ($to_id == 42) {

            if ($devices != '' && $from_id != '' && $to_id != '') {

                foreach ($devices as $key => $value) {

                    $data = Devices::find()->where(['id' => $value])->one();


                    Devices::updateAll([
                        'received_from' => 2,
                        'border_port' => $data['border_port'],
                        'received_from_staff' => $data['released_to'],
                        'received_at' => date('Y-m-d H:i:s'),
                        'received_status' => 1,
                        'received_by' => $from_id,
                        'view_status' => Devices::awaiting_receive,
                    ], ['serial_no' => $data['serial_no']]);


                    $report = new AwaitingReceiveReport();
                    $report->serial_no = $data['serial_no'];
                    $report->received_from = 2;
                    $report->border_port = $data['border_port'];
                    $report->received_from_staff = $data['released_to'];
                    $report->received_at = date('Y-m-d H:i:s');
                    $report->received_status = 1;
                    $report->received_by = $from_id;
                    $report->save();


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

                    $data = Devices::find()->where(['id' => $value])->one();

                    Devices::updateAll([
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


                }

                $result = ['status' => 'true', 'message' => 'successfully'];

                return Json::encode($result);


            } else {

                $result = ['status' => 'Failed'];

                return Json::encode($result);
            }

        }

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
                //  ->where(['between', 'date_time(sale_date, "%Y-%m-%d %H:%i:%s")', $date_from, $to_date])

                ->join('left join',
                    'sales_trips as u',
                    'u.id =a.id  AND u.customer_type_id=' . $customer2 . '')
                ->join('left join',
                    'sales_trips as k',
                    'k.id =a.id  AND k.customer_type_id=' . $customer . '')
                ->filterWhere(['between', 'DATE_FORMAT(a.sale_date, "%Y-%m-%d %H:%i")', $date_from, $to_date])
                ->andFilterWhere(['a.sales_person' => $user_id]);

            $query1 = new Query;
            $query1->select(['count(id) remaining_devices'])
                ->from('devices')
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
        $branch = User::findOne(['id' => $user_id]);
        $query = new Query;
        $query->select(['id', 'serial_no as name'])
            ->from('devices')
            ->where(['status' => StockDevices::not_deactivated])
            ->andWhere(['view_status' => Devices::stock_devices])
            ->andWhere(['branch' => $branch->branch])
            ->andWhere(['border_port' => $userAllocated['border_port']]);
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

        \Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;

        $data = json_decode(file_get_contents("php://input"));

        $array = $data->devices;
        $devices = json_decode(json_encode($array), True);
        $user_id = $data->user_id;


        if ($devices != '' && $user_id != '') {

            $e = Devices::find()->select(['serial_no', 'border_port', 'id'])->where(['in', 'id', $devices])->all();

            if (!empty($e)) {

                foreach ($e as $singleProductlineID) {
                    $e = Devices::find()->where(['id' => $singleProductlineID->id])->one();

                    Devices::updateAll([
                        'received_from' => BorderPort::Border,
                        'received_status' => StockDevices::available,
                        'received_from_staff' => $e['created_by'],
                        'sale_id' => $e['sale_id'],
                        'received_by' => $user_id,
                        'received_at' => date('Y-m-d H:i:s'),
                        'view_status' => Devices::awaiting_receive,
                        'border_port' => $singleProductlineID->border_port,
                    ], ['serial_no' => $singleProductlineID->serial_no]);


                    $report = new AwaitingReceiveReport();
                    $report->serial_no = $singleProductlineID->serial_no;
                    $report->received_from = 1;
                    $report->border_port = $singleProductlineID->border_port;
                    $report->received_by = $user_id;
                    $report->received_at = date('Y-m-d H:i:s');
                    // $report->isNewRecord = true;
                    $report->received_status = 1;
                    $report->save();

                }

                $result = ['status' => '200', 'message' => 'Successfully Confirmed'];
                return Json::encode($result);

            } else {
                $result = ['status' => '400', 'message' => 'No device found'];
                return Json::encode($result);
            }


        } else {

            $result = ['status' => ' not device found'];

            return Json::encode($result);
        }


    }

    public function actionConfirmReceiveOld()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;

        $data = json_decode(file_get_contents("php://input"));

        $array = $data->devices;
        $devices = json_decode(json_encode($array), True);
        $user_id = $data->user_id;


        if ($devices != '' && $user_id != '') {

            $e = Devices::find()->select(['serial_no', 'border_port', 'tzl', 'sale_id'])
                ->where(['in', 'id', $devices])
                ->andWhere(['view_status' => Devices::in_transit])
                ->all();

            if (!empty($e)) {

                foreach ($e as $singleProductlineID) {


                    $all_slave = Devices::find()->select(['serial_no'])
                        ->where(['tzl' => $singleProductlineID->tzl])
                        ->andWhere(['view_status' => Devices::in_transit])
                        ->all();
                    // print_r($all_slave);
                    //  die;


                    Devices::updateAll([
                        'received_from' => 1,
                        'received_status' => 1,
                        'received_by' => $user_id,
                        'sale_id' => $singleProductlineID->sale_id,
                        'received_at' => date('Y-m-d H:i:s'),
                        'view_status' => Devices::awaiting_receive,
                        'border_port' => $singleProductlineID->border_port,
                    ], ['serial_no' => $singleProductlineID->serial_no]);


                    Devices::updateAll(['view_status' => Devices::awaiting_receive], ['serial_no' => $singleProductlineID->serial_no]);
                    Devices::updateAll(['view_status' => Devices::awaiting_receive], ['tzl' => $singleProductlineID->tzl]);
                    Devices::updateAll(['view_status' => Devices::awaiting_receive, 'sale_id' => $singleProductlineID->sale_id], ['in', 'serial_no', $all_slave]);

                    $report = new AwaitingReceiveReport();
                    $report->serial_no = $singleProductlineID->serial_no;
                    $report->received_from = 1;
                    $report->border_port = $singleProductlineID->border_port;
                    $report->received_by = $user_id;
                    $report->received_at = date('Y-m-d H:i:s');
                    // $report->isNewRecord = true;
                    $report->received_status = 1;
                    $report->save();

                }

                $result = ['status' => 'true', 'message' => 'Successfully Confirmed'];
                return Json::encode($result);

            } else {
                $result = ['status' => 'false', 'message' => 'No device found'];
                return Json::encode($result);

            }


        } else {

            $result = ['status' => ' not device found'];

            return Json::encode($result);
        }

    }

    public static function getAll()
    {
        return Devices::find()->select(['id', 'serial_no'])->where(['type' => 3, 'view_status' => 5,])
            ->all();
    }

    public function actionDeviceToConfirm($user_id)
    {
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;
        $userAllocated = BorderPortUser::find()->where(['name' => $user_id])->one();
        $branch = User::findOne(['id' => $user_id]);
        $query = new Query;
        $query->select(['id', 'serial_no as name'])
            ->from('devices')
            ->where(['branch' => $branch->branch])
            ->andWhere(['border_port' => $userAllocated['border_port']])
            ->andWhere(['view_status' => Devices::in_transit]);
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

    public function actionDeviceToConfirmOld($user_id)
    {

        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;

        $userAllocated = BorderPortUser::find()->where(['name' => $user_id])->one();

        $masters = Devices::find()
            ->where(['type' => 2])
            ->andwhere(['border_port' => $userAllocated['border_port']])
            ->andWhere(['view_status' => Devices::in_transit])
            ->all();

        foreach ($masters as $master) {

            $slave = Devices::find()
                ->select(['id', 'serial_no'])
                ->where(['type' => 3,])
                ->andWhere(['view_status' => Devices::in_transit,])
                ->andWhere(['tzl' => $master->tzl])
                ->all();
            $response['devices'][] = [

                'id' => $master['id'],
                'name' => $master['serial_no'],
                //'tzl' => $master['tzl'],
                'slaves' => $slave,
            ];


        }
        return $response;
    }


    public function actionReturn()
    {
        Yii::$app->db->transaction(function () {

            $date = date('Y-m-d', strtotime('-2 days'));
            $released = Devices::find()
                ->where(['date(released_date)' => $date])
                ->andWhere(['view_status' => Devices::released_devices])
                ->all();

            foreach ($released as $release) {

                $data = Devices::find()->where(['id' => $release->id])
                    ->andWhere(['view_status' => Devices::released_devices])->one();
                Devices::updateAll([
                    'received_from' => 2,
                    'border_port' => $data['border_port'],
                    'received_from_staff' => $data['released_to'],
                    'received_status' => 1,
                    'remark' => 'AUTOMATIC RETURN TO OFFICE AFTER 24 HOURS',
                    'received_at' => date('Y-m-d H:i:s'),
                    'received_by' => $data['released_to'],
                    'view_status' => Devices::awaiting_receive,
                ], ['serial_no' => $data['serial_no']]);

                $report = new AwaitingReceiveReport();
                $report->serial_no = $data['serial_no'];
                $report->received_from = 2;
                $report->border_port = $data['border_port'];
                $report->received_from_staff = $data['released_to'];
                $report->received_at = date('Y-m-d H:i:s');
                $report->received_status = 1;
                $report->remark = 'AUTOMATIC RETURN TO OFFICE AFTER 24 HOURS';
                $report->received_by = $data['released_to'];


            }

        });

    }


    protected function verbs()
    {
        return [
            'login' => ['POST'],
            'released' => ['GET'],
            'sale-add' => ['POST'],
            'borders' => ['GET'],
            'points' => ['GET'],
            'staffs' => ['GET'],
            'child' => ['GET'],
            'customers' => ['GET'],
            'transfer' => ['POST'],
            'sales-report' => ['POST'],
            'confirm-receive' => ['POST'],
            'transit-devices' => ['GET'],
            'device-to-confirm' => ['GET'],

        ];
    }
}