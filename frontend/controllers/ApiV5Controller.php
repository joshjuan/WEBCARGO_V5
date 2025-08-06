<?php

namespace frontend\controllers;

ini_set('memory_limit', '10024M');


use frontend\models\Audit;
use frontend\models\AwaitingReceiveReport;
use frontend\models\BorderPort;
use frontend\models\BorderPortUser;
use frontend\models\Branches;
use frontend\models\DeviceLogs;
use frontend\models\Devices;

use frontend\models\DevicesReports;
use frontend\models\Receipt;

use frontend\models\ReleasedDevicesReport;
use frontend\models\SalesTrips;
use frontend\models\SalesTripSlaves;
use frontend\models\StockDevices;
use common\models\LoginForm;
use common\models\User;
use http\Exception;
use search;
use Yii;

use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;


class ApiV5Controller extends \yii\rest\ActiveController
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

    /**********************END POINT FOR USER LOGIN ***********************************************/
    public function actionLogin()
    {
        Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;

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

                $response = [
                    'user_id' => Yii::$app->user->identity->id,
                    'name' => Yii::$app->user->identity->full_name,
                    'username' => Yii::$app->user->identity->username,
                    'email' => Yii::$app->user->identity->email,
                    'status' => Yii::$app->user->identity->status,
                    'message' => "You are now logged in",
                    'access_token' => Yii::$app->user->identity->getAuthKey(),
                    'user_type' => Yii::$app->user->identity->user_type,
                    'border_port' => $id,
                    'border_port_name' => $name,
                    'role' => Yii::$app->user->identity->role,
                    'branch' => Yii::$app->user->identity->branch,

                ];

                return $response;

            } else {
                $response['error'] = false;
                $response['status'] = 'error';
                $model->validate($model->password);
                $response['errors'] = $model->getErrors();
                $response['message'] = 'wrong password';
                return $response;
            }


        } else {
            $response['error'] = false;
            $response['status'] = 'error';
            $model->validate($model->password);
            $response['errors'] = $model->getErrors();
            $response['message'] = 'user is disabled or does not exist!';
            return $response;
        }
    }


    public function actionSaleAddOld()
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
            $prev = ReleasedDevicesReport::find()
                ->where(['serial_no' => $compare['serial_no']])
                ->orderBy(['id' => SORT_DESC])
                ->one();


            Devices::updateAll([
                'view_status' => Devices::released,
                'border_port' => $prev['sales_point']

            ],
                ['tzl' => $data->tzl]);


            Devices::updateAll([
                'view_status' => Devices::released,
                'border_port' => $prev['sales_point']
            ],
                ['in', 'sale_id', $data->sale_id]);

        } else {

            Devices::updateAll([
                'view_status' => Devices::released

            ],
                ['tzl' => $data->tzl]);

            SalesTripSlaves::updateAll([
                'status' => 0],
                ['in', 'sale_id', $data->sale_id]);

            Devices::updateAll([
                'view_status' => Devices::released],
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

    public function actionSaleAdd()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $data = json_decode(file_get_contents("php://input"));
        $offline_receipt_number = $data->offline_receipt_number;

        $isDataExist = SalesTrips::find()
            ->where(['offline_receipt_number' => $offline_receipt_number])
            ->one();

        if (!empty($isDataExist)) {

            return [
                'receipt_number' => $isDataExist->receipt_number,
                'amount' => $isDataExist->amount,
                'sale_id' => $isDataExist->id,
                'status' => 200,
                'message' => "Data exist, but saved successfully",

            ];
        }

        if (isset($data->slaves)) {
            $slaves_array = $data->slaves;
            $devices = json_decode(json_encode($slaves_array), True);

            $serial_no = array();
            foreach ($devices as $key => $value) {

                $serial_no[] = $value['serial_no'];
            }

            $slaves_serial = implode(",", $serial_no);


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

        $model = new SalesTrips();
        $model->sale_date = date('Y-m-d H:i:s', strtotime($data->sale_date));
        $model->tzl = $data->tzl;
        $model->start_date_time = date('Y-m-d H:i:s', strtotime($data->start_date_time));
        $model->created_at = date('Y-m-d H:i:s');
        $model->vehicle_number = $data->vehicle_number;
        $model->chasis_number = $data->chasis_number;
        $model->trailerno = $data->trailerno;
        $model->offline_receipt_number = $data->offline_receipt_number;
        $model->prev_offline_receipt_number = $data->prev_offline_receipt_number;
        $model->driver_name = $data->driver_name;
        $model->border_id = $data->border_id;
        $model->trip_status = 1;
        $model->driver_number = $data->driver_number;
        $model->serial_no = $data->serial_no;
        $model->slaves_serial = $slaves_serial;
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
        $model->sale_type = $data->sale_type;
        $model->sale_id = $data->sale_id;

        $branch = Devices::find()
            ->where(['serial_no' => $model->serial_no])
            ->one();
        // $model->branch = $branch['branch'];
        $model->type = $branch['type'];

//        $branch_user = User::find()
//            ->where(['id' => $data->sales_person])
//            ->one();
        $branch_id = $branch['branch'];
        $model->branch = $branch_id;

        if ($data->customer_type_id == 1) {
            $model->receipt_number = $model->sales_person . Receipt::findCash();
            $model->amount = $data->amount;
        } elseif ($data->customer_type_id == 2) {
            $model->receipt_number = $model->sales_person . Receipt::findBill();
            $model->amount = 0.00;
        }

        if ($model->validate()) {

            $model->save();
            $movementID = strtoupper(Yii::$app->security->generateRandomString());

            $deviceData = Devices::find()
                ->where(['serial_no' => $model->serial_no])
                ->andWhere(['view_status' => Devices::released])
                ->one();

            if ($deviceData) {
                Devices::updateAll([
                    'view_status' => Devices::on_road,
                    'tzl' => $model->tzl,
                    'border_port' => $model->border_id,
                    'gate_number' => $model->gate_number,
                    'sales_person' => $model->sales_person,
                    'vehicle_no' => $model->vehicle_number,
                    'container_number' => $model->container_number,
                    'created_by' => $model->sales_person,
                    'sale_id' => $model->id,
                    'created_at' => $model->created_at,
                    'movement_unique_id' => $movementID,
                ], ['serial_no' => $model->serial_no]);

                Yii::$app->db->createCommand()
                    ->upsert(
                        'devices_reports',
                        [
                            'vehicle_no' => $model->vehicle_number,
                            'container_number' => $model->container_number,
                            'serial_no' => $deviceData['serial_no'],
                            'border_port' => $model->border_id,
                            'gate_number' => $model->gate_number,
                            'received_from' => Devices::released,
                            'received_to' => Devices::on_road,
                            'type' => $deviceData->type,
                            'device_category' => $deviceData->device_category,
                            'branch' => $deviceData->branch,
                            'created_by' => $model->sales_person,
                            'created_at' => $model->created_at,
                            'movement_unique_id' => $movementID,
                        ],
                        false
                    )
                    ->execute();

            }

            if ($slaves_array != '') {

                foreach ($devices as $key => $value) {

                    $serial_no = $value['serial_no'];
                    $slaveData = Devices::find()
                        ->where(['serial_no' => $serial_no])
                        ->andWhere(['view_status' => Devices::released])
                        ->one();

                    if ($slaveData) {

                        Yii::$app->db->createCommand()
                            ->insert('sales_trip_slaves', [
                                'serial_no' => $serial_no,
                                'sale_id' => $model->id,
                                'branch' => $model->branch,
                                'created_at' => $model->created_at,
                            ])->execute();

                        Devices::updateAll([
                            'view_status' => Devices::on_road,
                            'tzl' => $model->tzl,
                            'border_port' => $model->border_id,
                            'gate_number' => $model->gate_number,
                            'sales_person' => $model->sales_person,
                            'vehicle_no' => $model->vehicle_number,
                            'container_number' => $model->container_number,
                            'created_by' => $model->sales_person,
                            'sale_id' => $model->id,
                            'created_at' => $model->created_at,
                            'movement_unique_id' => $movementID,
                        ], ['serial_no' => $serial_no]);;


                        Yii::$app->db->createCommand()
                            ->upsert(
                                'devices_reports',
                                [
                                    'vehicle_no' => $model->vehicle_number,
                                    'container_number' => $model->container_number,
                                    'serial_no' => $serial_no,
                                    'border_port' => $model->border_id,
                                    'gate_number' => $model->gate_number,
                                    'received_from' => Devices::released,
                                    'received_to' => Devices::on_road,
                                    'type' => $slaveData->type,
                                    'device_category' => $slaveData->device_category,
                                    'branch' => $slaveData->branch,
                                    'created_by' => $model->sales_person,
                                    'created_at' => $model->created_at,
                                    'movement_unique_id' => $movementID,
                                ],
                                false
                            )
                            ->execute();
                    }


                }

            }


            return [

                'receipt_number' => $model->receipt_number,
                'amount' => $model->amount,
                'sale_id' => $model->id,
                'status' => 200,
                'message' => "Submitted Successfully",

            ];

        } else {
            throw new BadRequestHttpException(json_encode($model->getErrors()));

        }


    }

    public function actionEditSale()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $data = json_decode(file_get_contents("php://input"));

        $tzl = $data->tzl;
        $prev_offline_receipt_number = $data->prev_offline_receipt_number;
        $offline_receipt_number = $data->offline_receipt_number;


        $isDataExist = SalesTrips::find()
            ->where(['offline_receipt_number' => $offline_receipt_number])
            ->one();


        if (!empty($isDataExist)) {

            return [

                'receipt_number' => $isDataExist->receipt_number,
                'amount' => $isDataExist->amount,
                'sale_id' => $isDataExist->id,
                'status' => 200,
                'message' => "Data exist, but saved successfully",

            ];
        }


        $checkIfExist = SalesTrips::find()
            ->where(['tzl' => $tzl])
            ->andWhere(['trip_status' => SalesTrips::NORMAL])
            ->one();

        if ($checkIfExist) {


            if ($checkIfExist->scanned_status == 1) {
                throw new BadRequestHttpException("This $tzl was already verified, you can not edit it");
            }

            $releaseDate = $checkIfExist->created_at;
            $time = new \DateTime('now');
            $datetime2 = new \DateTime($releaseDate);
            $interval = $time->diff($datetime2)->days;

            if ($interval > 1) {

                $message = "sale with more than 2 days cannot be edited";

                throw new BadRequestHttpException($message);
            }


            ########## UPDATE EXISTING RECORDS FIRST TO SHOW EDIT WAS DONE #############
            SalesTrips::updateAll([
                'sale_id' => $checkIfExist->id,
                'edited_at' => date('Y-m-d H:i:s'),
                'edited_by' => $data->sales_person,
                'trip_status' => SalesTrips::EDITED,
            ],
                ['tzl' => $data->tzl]);


            SalesTripSlaves::updateAll([
                'status' => 0
            ],
                ['in', 'sale_id', $checkIfExist->id]);

            Devices::updateAll([
                'view_status' => Devices::released,
                'tzl' => null,
                'border_port' => null,
                'gate_number' => null,
                'sales_person' => null,
                'vehicle_no' => null,
                'container_number' => null,
                'sale_id' => null,
            ],
                ['in', 'sale_id', $checkIfExist->id]);


            ################## START PROCESSING NEW SALE AFTER EDIT ################
            if (isset($data->slaves)) {

                $slaves_array = $data->slaves;
                $devices = json_decode(json_encode($slaves_array), True);

                $serial_no = array();
                foreach ($devices as $key => $value) {

                    $serial_no[] = $value['serial_no'];
                }

                $slaves_serial = implode(",", $serial_no);


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

            $model = new SalesTrips();
            $model->sale_date = date('Y-m-d H:i:s', strtotime($data->sale_date));
            $model->tzl = $data->tzl;
            $model->start_date_time = date('Y-m-d H:i:s', strtotime($data->start_date_time));
            $model->created_at = date('Y-m-d H:i:s');
            $model->offline_receipt_number = $data->offline_receipt_number;
            $model->prev_offline_receipt_number = $data->prev_offline_receipt_number;
            $model->vehicle_number = $data->vehicle_number;
            $model->chasis_number = $data->chasis_number;
            $model->trailerno = $data->trailerno;
            $model->driver_name = $data->driver_name;
            $model->border_id = $data->border_id;
            $model->trip_status = 1;
            $model->driver_number = $data->driver_number;
            $model->serial_no = $data->serial_no;
            $model->slaves_serial = $slaves_serial;
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
            $model->sale_type = $data->sale_type;
            $model->sale_id = $data->sale_id;

            $branch = Devices::find()
                ->where(['serial_no' => $model->serial_no])
                ->one();
            // $model->branch = $branch['branch'];
            $model->type = $branch['type'];

//            $branch_user = User::find()
//                ->where(['id' => $data->sales_person])
//                ->one();
            $branch_id = $branch['branch'];
            $model->branch = $branch_id;

            if ($data->customer_type_id == 1) {
                $model->receipt_number = $model->sales_person . Receipt::findCash();
                $model->amount = $data->amount;
            } elseif ($data->customer_type_id == 2) {
                $model->receipt_number = $model->sales_person . Receipt::findBill();
                $model->amount = 0.00;
            }

            if ($model->validate()) {

                $model->save();
                $movementID = strtoupper(Yii::$app->security->generateRandomString());

                $deviceData = Devices::find()
                    ->where(['serial_no' => $model->serial_no])
                    ->andWhere(['view_status' => Devices::released])
                    ->one();

                if ($deviceData) {
                    Devices::updateAll([
                        'view_status' => Devices::on_road,
                        'tzl' => $model->tzl,
                        'border_port' => $model->border_id,
                        'gate_number' => $model->gate_number,
                        'sales_person' => $model->sales_person,
                        'vehicle_no' => $model->vehicle_number,
                        'container_number' => $model->container_number,
                        'created_by' => $model->sales_person,
                        'sale_id' => $model->id,
                        'created_at' => $model->created_at,
                        'movement_unique_id' => $movementID,
                    ], ['serial_no' => $model->serial_no]);

                    Yii::$app->db->createCommand()
                        ->upsert(
                            'devices_reports',
                            [
                                'vehicle_no' => $model->vehicle_number,
                                'container_number' => $model->container_number,
                                'serial_no' => $deviceData['serial_no'],
                                'border_port' => $model->border_id,
                                'gate_number' => $model->gate_number,
                                'received_from' => Devices::released,
                                'received_to' => Devices::on_road,
                                'type' => $deviceData->type,
                                'device_category' => $deviceData->device_category,
                                'branch' => $deviceData->branch,
                                'created_by' => $model->sales_person,
                                'created_at' => $model->created_at,
                                'movement_unique_id' => $movementID,
                            ],
                            false
                        )
                        ->execute();

                }

                if ($slaves_array != '') {

                    foreach ($devices as $key => $value) {

                        $serial_no = $value['serial_no'];
                        $slaveData = Devices::find()
                            ->where(['serial_no' => $serial_no])
                            ->andWhere(['view_status' => Devices::released])
                            ->one();

                        if ($slaveData) {

                            Yii::$app->db->createCommand()
                                ->insert('sales_trip_slaves', [
                                    'serial_no' => $serial_no,
                                    'sale_id' => $model->id,
                                    'branch' => $model->branch,
                                    'created_at' => $model->created_at,
                                ])->execute();

                            Devices::updateAll([
                                'view_status' => Devices::on_road,
                                'tzl' => $model->tzl,
                                'border_port' => $model->border_id,
                                'gate_number' => $model->gate_number,
                                'sales_person' => $model->sales_person,
                                'vehicle_no' => $model->vehicle_number,
                                'container_number' => $model->container_number,
                                'created_by' => $model->sales_person,
                                'sale_id' => $model->id,
                                'created_at' => $model->created_at,
                                'movement_unique_id' => $movementID,
                            ], ['serial_no' => $serial_no]);;


                            Yii::$app->db->createCommand()
                                ->upsert(
                                    'devices_reports',
                                    [
                                        'vehicle_no' => $model->vehicle_number,
                                        'container_number' => $model->container_number,
                                        'serial_no' => $serial_no,
                                        'border_port' => $model->border_id,
                                        'gate_number' => $model->gate_number,
                                        'received_from' => Devices::released,
                                        'received_to' => Devices::on_road,
                                        'type' => $slaveData->type,
                                        'device_category' => $slaveData->device_category,
                                        'branch' => $slaveData->branch,
                                        'created_by' => $model->sales_person,
                                        'created_at' => $model->created_at,
                                        'movement_unique_id' => $movementID,
                                    ],
                                    false
                                )
                                ->execute();
                        }


                    }

                }


                return [

                    'receipt_number' => $model->receipt_number,
                    'amount' => $model->amount,
                    'sale_id' => $model->id,
                    'status' => 200,
                    'message' => "Submitted Successfully",

                ];

            } else {
                throw new BadRequestHttpException(json_encode($model->getErrors()));

            }

        } else {

            $checkIfExist = SalesTrips::find()
                ->where(['prev_offline_receipt_number' => $prev_offline_receipt_number])
                ->andWhere(['trip_status' => SalesTrips::NORMAL])
                ->one();

            if ($checkIfExist) {


                if ($checkIfExist->scanned_status == 1) {
                    throw new BadRequestHttpException("This $tzl was already verified, you can not edit it");
                }

                $releaseDate = $checkIfExist->created_at;
                $time = new \DateTime('now');
                $datetime2 = new \DateTime($releaseDate);
                $interval = $time->diff($datetime2)->days;

                if ($interval > 1) {

                    $message = "sale with more than 2 days cannot be edited";

                    throw new BadRequestHttpException($message);
                }

                ########## UPDATE EXISTING RECORDS FIRST TO SHOW EDIT WAS DONE #############
                SalesTrips::updateAll([
                    'sale_id' => $checkIfExist->id,
                    'edited_at' => date('Y-m-d H:i:s'),
                    'edited_by' => $data->sales_person,
                    'trip_status' => SalesTrips::EDITED,
                ],
                    ['id' => $checkIfExist->id]);


                SalesTripSlaves::updateAll([
                    'status' => 0
                ],
                    ['in', 'sale_id', $checkIfExist->id]);

                Devices::updateAll([
                    'view_status' => Devices::released,
                    'tzl' => null,
                    'border_port' => null,
                    'gate_number' => null,
                    'sales_person' => null,
                    'vehicle_no' => null,
                    'container_number' => null,
                    'sale_id' => null,
                ],
                    ['in', 'sale_id', $checkIfExist->id]);


                ################## START PROCESSING NEW SALE AFTER EDIT ################
                if (isset($data->slaves)) {

                    $slaves_array = $data->slaves;
                    $devices = json_decode(json_encode($slaves_array), True);

                    $serial_no = array();
                    foreach ($devices as $key => $value) {

                        $serial_no[] = $value['serial_no'];
                    }

                    $slaves_serial = implode(",", $serial_no);


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

                $model = new SalesTrips();
                $model->sale_date = date('Y-m-d H:i:s', strtotime($data->sale_date));
                $model->tzl = $data->tzl;
                $model->start_date_time = date('Y-m-d H:i:s', strtotime($data->start_date_time));
                $model->created_at = date('Y-m-d H:i:s');
                $model->vehicle_number = $data->vehicle_number;
                $model->chasis_number = $data->chasis_number;
                $model->trailerno = $data->trailerno;
                $model->offline_receipt_number = $data->offline_receipt_number;
                $model->prev_offline_receipt_number = $data->prev_offline_receipt_number;
                $model->driver_name = $data->driver_name;
                $model->border_id = $data->border_id;
                $model->trip_status = 1;
                $model->driver_number = $data->driver_number;
                $model->serial_no = $data->serial_no;
                $model->slaves_serial = $slaves_serial;
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

                if ($model->validate()) {

                    $model->save();
                    $movementID = strtoupper(Yii::$app->security->generateRandomString());

                    $deviceData = Devices::find()
                        ->where(['serial_no' => $model->serial_no])
                        ->andWhere(['view_status' => Devices::released])
                        ->one();

                    if ($deviceData) {
                        Devices::updateAll([
                            'view_status' => Devices::on_road,
                            'tzl' => $model->tzl,
                            'border_port' => $model->border_id,
                            'gate_number' => $model->gate_number,
                            'sales_person' => $model->sales_person,
                            'vehicle_no' => $model->vehicle_number,
                            'container_number' => $model->container_number,
                            'created_by' => $model->sales_person,
                            'sale_id' => $model->id,
                            'created_at' => $model->created_at,
                            'movement_unique_id' => $movementID,
                        ], ['serial_no' => $model->serial_no]);

                        Yii::$app->db->createCommand()
                            ->upsert(
                                'devices_reports',
                                [
                                    'vehicle_no' => $model->vehicle_number,
                                    'container_number' => $model->container_number,
                                    'serial_no' => $deviceData['serial_no'],
                                    'border_port' => $model->border_id,
                                    'gate_number' => $model->gate_number,
                                    'received_from' => Devices::released,
                                    'received_to' => Devices::on_road,
                                    'type' => $deviceData->type,
                                    'device_category' => $deviceData->device_category,
                                    'branch' => $deviceData->branch,
                                    'created_by' => $model->sales_person,
                                    'created_at' => $model->created_at,
                                    'movement_unique_id' => $movementID,
                                ],
                                false
                            )
                            ->execute();

                    }

                    if ($slaves_array != '') {

                        foreach ($devices as $key => $value) {

                            $serial_no = $value['serial_no'];
                            $slaveData = Devices::find()
                                ->where(['serial_no' => $serial_no])
                                ->andWhere(['view_status' => Devices::released])
                                ->one();

                            if ($slaveData) {

                                Yii::$app->db->createCommand()
                                    ->insert('sales_trip_slaves', [
                                        'serial_no' => $serial_no,
                                        'sale_id' => $model->id,
                                        'branch' => $model->branch,
                                        'created_at' => $model->created_at,
                                    ])->execute();

                                Devices::updateAll([
                                    'view_status' => Devices::on_road,
                                    'tzl' => $model->tzl,
                                    'border_port' => $model->border_id,
                                    'gate_number' => $model->gate_number,
                                    'sales_person' => $model->sales_person,
                                    'vehicle_no' => $model->vehicle_number,
                                    'container_number' => $model->container_number,
                                    'created_by' => $model->sales_person,
                                    'sale_id' => $model->id,
                                    'created_at' => $model->created_at,
                                    'movement_unique_id' => $movementID,
                                ], ['serial_no' => $serial_no]);;


                                Yii::$app->db->createCommand()
                                    ->upsert(
                                        'devices_reports',
                                        [
                                            'vehicle_no' => $model->vehicle_number,
                                            'container_number' => $model->container_number,
                                            'serial_no' => $serial_no,
                                            'border_port' => $model->border_id,
                                            'gate_number' => $model->gate_number,
                                            'received_from' => Devices::released,
                                            'received_to' => Devices::on_road,
                                            'type' => $slaveData->type,
                                            'device_category' => $slaveData->device_category,
                                            'branch' => $slaveData->branch,
                                            'created_by' => $model->sales_person,
                                            'created_at' => $model->created_at,
                                            'movement_unique_id' => $movementID,
                                        ],
                                        false
                                    )
                                    ->execute();
                            }


                        }

                    }


                    return [

                        'receipt_number' => $model->receipt_number,
                        'amount' => $model->amount,
                        'sale_id' => $model->id,
                        'status' => 200,
                        'message' => "Submitted Successfully",

                    ];

                } else {
                    throw new BadRequestHttpException(json_encode($model->getErrors()));

                }


            } else {
                throw new NotFoundHttpException("offline $offline_receipt_number not found, please check it in offline list and then send again ");

            }
        }


    }

    public function actionSearchTzl($tzl)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        //$data = json_decode(file_get_contents("php://input"));
        //$tzl = $data->tzl;
        $updateSalesTrip = SalesTrips::find()
            ->where(['tzl' => $tzl])
            ->andWhere(['!=', 'trip_status', SalesTrips::CANCELED])
            ->orderBy(['id' => SORT_DESC])
            ->one();

        if ($updateSalesTrip != null) {

            $gateName = BorderPort::find()
                ->where(['id' => $updateSalesTrip['gate_number']])
                ->one();

            $borderName = BorderPort::find()
                ->where(['id' => $updateSalesTrip['border_id']])
                ->one();

            $device = Devices::find()
                ->where(['serial_no' => $updateSalesTrip['serial_no']])
                ->one();

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
                    'serial_no',
                ],
            ]);

            if (!empty($customer)) {
                $customer_name = $customer['company_name'];
                $company_name = $customer['company_name'];
                $customer_id = $customer['id'];
            } else {
                $company_name = $updateSalesTrip['company_name'];
                $customer_id = null;
                $customer_name = null;
            }


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
                    'border_id' => $updateSalesTrip['border_id'],
                    'border_name' => $borderName['name']
                ],
                'device' => [
                    'device_id' => $device['id'],
                    'device_serial' => $updateSalesTrip['serial_no']
                ],
                'passport' => $updateSalesTrip['passport'],
                'container_number' => $updateSalesTrip['container_number'],
                'customer' => [
                    'customer_id' => $customer_id,
                    'customer_name' => $customer_name,
                ],
                'driver_number' => $updateSalesTrip['driver_number'],
                'offline_receipt_number' => $updateSalesTrip['offline_receipt_number'],
                'prev_offline_receipt_number' => $updateSalesTrip['prev_offline_receipt_number'],
                'receipt_number' => $updateSalesTrip['receipt_number'],
                'chasis_number' => $updateSalesTrip['chasis_number'],
                'vehicle_type_id' => $updateSalesTrip['vehicle_type_id'],
                'customer_type_id' => $updateSalesTrip['customer_type_id'],
                'sale_type' => $updateSalesTrip['sale_type'],
                'trailerno' => $updateSalesTrip['trailerno'],
                'start_date_time' => $updateSalesTrip['start_date_time'],
                'end_date' => $updateSalesTrip['end_date'],
                'slaves' => $results
            ]
            ];
            return $result;


        } else {

            throw new NotFoundHttpException("TZL $tzl not found");
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

    public function actionPortGates()
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

        if (empty($user)) {
            throw new NotFoundHttpException("Company branch user not found");
        }
        $branch = $user->branch;
        $query = new Query;
        $query->select(['id', 'full_name as names'])
            ->from('user')
            ->where(['user_type' => \frontend\models\User::PORT_STAFF])
            ->andWhere(['branch' => $branch]);
        if ($query) {
            $command = $query->createCommand();
            $response['staffs'] = $command->queryAll();
            return $response;
        }


    }

    public function actionAllDevices($user_id)
    {
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;
        $query = new Query;
        $query->select(['id', 'serial_no'])
            ->from('devices')
            ->where(['released_to' => $user_id])
            // ->andWhere(['in', 'type', [1, 2]])
            ->andWhere(['view_status' => Devices::released]);
        $command = $query->createCommand();
        $response['devices'] = $command->queryAll();
        return $response;
    }

    public function actionBorderDevices($user_id)
    {
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;
        $query = new Query;
        $query->select(['id', 'serial_no'])
            ->from('devices')
            ->where(['released_to' => $user_id])
            ->andWhere(['view_status' => Devices::in_transit]);
        $command = $query->createCommand();
        $response['devices'] = $command->queryAll();
        return $response;
    }

    public function actionReleased($user_id)
    {
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;

        $date = date('Y-m-d');
        $endDate = date("Y-m-d", strtotime("$date -2 day"));

        $query = new Query;
        $query->select(['id', 'serial_no'])
            ->from('devices')
            ->where(['released_to' => $user_id])
            ->andWhere(['in', 'type', [1, 2]])
            ->andWhere(['view_status' => Devices::released])
            ->andWhere(['>', 'date(released_date)', $endDate]);
        $command = $query->createCommand();
        $response['devices'] = $command->queryAll();
        return $response;
    }


    public function actionChild($user_id)
    {
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;

        $date = date('Y-m-d');
        $endDate = date("Y-m-d", strtotime("$date -2 day"));

        $query = new Query;
        $query->select(['id', 'serial_no'])
            ->from('devices')
            ->where(['released_to' => $user_id])
            ->andWhere(['type' => 3])
            ->andWhere(['view_status' => Devices::released])
            ->andWhere(['>', 'date(released_date)', $endDate]);
        $command = $query->createCommand();
        $response['devices'] = $command->queryAll();
        return $response;
    }


    public function actionCustomers($user_id)
    {
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;

        $branch = User::findOne(['id' => $user_id]);
        $query = new Query;
        $query->select(['id', 'company_name'])
            ->from('user')
            ->where(['user_type' => 5])
            ->andWhere(['branch' => $branch->branch]);

        $command = $query->createCommand();
        $response['customers'] = $command->queryAll();
        return $response;

    }

######################################### PULL DATA FOR EDITING SALE RECORD #######################################
    public function actionEditSaleOld()
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
        if ($customerdata) {
            $customerID = $customerdata['id'];
            $customerName = $customerdata['company_name'];
        } else {
            $customerID = '';
            $customerName = '';
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

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $data = json_decode(file_get_contents("php://input"));

        $array = $data->devices;
        $devices = json_decode(json_encode($array), True);

        $from_id = $data->from_id;
        $to_id = $data->to_id;

        if (empty($from_id)) {
            throw new BadRequestHttpException("From can not be empty");
        }

        if (empty($to_id)) {
            throw new BadRequestHttpException("To can not be empty");
        }

        // Move to awaiting storage
        $movementID = strtoupper(Yii::$app->security->generateRandomString());

        foreach ($devices as $key => $value) {

            // try {
            $e = Devices::find()
                // ->select(['serial_no'])
                ->where(['id' => $value])
                ->andWhere(['view_status' => Devices::released])
                ->one();

            if ($e) {

                $total = $key + 1;
                $datetime = date('Y-m-d H:i:s');
                $releaseDate = $e['created_at'];
                $time = new \DateTime('now');
                $datetime2 = new \DateTime($releaseDate);
                $interval = $time->diff($datetime2)->days;

                if ($interval > 2) {

                    $message = "Device with more than 2 days cannot be transferred to tagger";

                    throw new BadRequestHttpException($message);
                } else {

                    Devices::updateAll([
                        'received_from' => Devices::released,
                        // 'border_port' => $e['border_port'],
                        //'received_status' => StockDevices::available,
                        //  'released_by' => $from_id,
                        'released_to' => $to_id,
                        // 'created_at' => $datetime,
                        // 'created_by' => Yii::$app->user->identity->id,
                        // 'received_at' => $datetime,
                        // 'released_date' => $datetime,
                        // 'received_by' => Yii::$app->user->identity->id,
                        //'view_status' => Devices::released,
                        'movement_unique_id' => $movementID,
                        'transferred_from' => $e['released_to'],
                        'transferred_to' => $to_id,
                        'transferred_date' => $datetime,
                        'transferred_by' => $from_id
                    ], ['serial_no' => $e['serial_no']]);


                    Yii::$app->db->createCommand()
                        ->upsert(
                            'devices_reports',
                            [
                                'serial_no' => $e['serial_no'],
                                'received_from' => Devices::released,
                                // 'received_to' => Devices::released,
                                'type' => $e['type'],
                                'device_category' => $e['device_category'],
                                'branch' => $e['branch'],
                                'created_by' => $from_id,
                                'created_at' => $datetime,
                                'movement_unique_id' => $movementID,
                                // 'released_by' => Yii::$app->user->identity->id,
                                'released_to' => $to_id,
                                'transferred_from' => $e['released_to'],
                                'transferred_to' => $to_id,
                                'transferred_date' => $datetime,
                                'transferred_by' => $from_id,
                                'remark' => "Transfer By Tagger",
                            ],
                            false
                        )
                        ->execute();
                }
            } else {
                throw new NotFoundHttpException("Serial No is not at released stage");
            }

//            } catch (\Exception $e) {
//                throw new BadRequestHttpException($e);
//            }

        }

        return [
            'status' => 200,
            'message' => "Transfer successfully",

        ];


    }

    public function actionTransferOld()
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
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $data = json_decode(file_get_contents("php://input"));

        $date_from = $data->from_date;
        $user_id = $data->user_id;
        $to_date = $data->to_date;
        $customer = 1;
        $customer2 = 2;
        if ($date_from != '' && $user_id != '' && $to_date != '') {

            $salesBill = SalesTrips::find()
                ->where(['customer_type_id' => $customer2])
                ->andWhere(['sales_person' => $user_id])
                ->andWhere(['between', 'DATE_FORMAT(sale_date, "%Y-%m-%d")', $date_from, $to_date])
                ->all();
            if ($salesBill) {
                $salesBillCount = count($salesBill);
                $salesBillAmount = "0.00";
            } else {
                $salesBillCount = 0;
                $salesBillAmount = "0.00";
            }

            $salesCashTzs = SalesTrips::find()
                ->where(['customer_type_id' => $customer])
                ->andWhere(['sales_person' => $user_id])
                ->andWhere(['currency' => 'TZS'])
                ->andWhere(['between', 'DATE_FORMAT(sale_date, "%Y-%m-%d")', $date_from, $to_date])
                ->all();

            $salesCashTzsSum = SalesTrips::find()
                ->where(['customer_type_id' => $customer])
                ->andWhere(['sales_person' => $user_id])
                ->andWhere(['currency' => 'TZS'])
                ->andWhere(['between', 'DATE_FORMAT(sale_date, "%Y-%m-%d")', $date_from, $to_date])
                ->sum('amount');
            if ($salesCashTzs) {
                $salesTzsCount = count($salesCashTzs);
                $salesTzsAmount = $salesCashTzsSum;
            } else {
                $salesTzsCount = 0;
                $salesTzsAmount = "0.00";
            }

            $salesCashUsd = SalesTrips::find()
                ->where(['customer_type_id' => $customer])
                ->andWhere(['sales_person' => $user_id])
                ->andWhere(['currency' => 'USD'])
                ->andWhere(['between', 'DATE_FORMAT(sale_date, "%Y-%m-%d")', $date_from, $to_date])
                ->all();

            $salesCashUsdSum = SalesTrips::find()
                ->where(['customer_type_id' => $customer])
                ->andWhere(['sales_person' => $user_id])
                ->andWhere(['currency' => 'USD'])
                ->andWhere(['between', 'DATE_FORMAT(sale_date, "%Y-%m-%d")', $date_from, $to_date])
                ->sum('amount');
            if ($salesCashUsd) {
                $salesUsdCount = count($salesCashUsd);
                $salesUsdAmount = $salesCashUsdSum;
            } else {
                $salesUsdCount = 0;
                $salesUsdAmount = "0.00";
            }


            $remained = Devices::find()
                ->where(['released_to' => $user_id])
                ->andWhere(['view_status' => Devices::released])
                // ->andWhere(['between', 'DATE_FORMAT(sale_date, "%Y-%m-%d %H:%i")', $date_from, $to_date])
                ->all();
            if ($remained) {
                $remainedDevices = count($remained);
            } else {
                $remainedDevices = 0;
            }


            $result = [
                'report' => [
                    'total_number_bill_sales' => $salesBillCount,
                    'total_amount_bill_sales' => $salesBillAmount,
                    'total_number_cash_sales_tzs' => $salesTzsCount,
                    'total_amount_cash_sales_tzs' => $salesTzsAmount,
                    'total_number_cash_sales_usd' => $salesUsdCount,
                    'total_amount_cash_sales_usd' => $salesUsdAmount,
                    'remained_devices' => $remainedDevices,
                ],
            ];

            return $result;

        } else {

            throw new NotFoundHttpException("Input data No found");
        }


    }


    public function actionIntransitDevices($user_id)
    {
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;
        $userAllocated = BorderPortUser::find()
            ->where(['name' => $user_id])
            ->one();
        if ($userAllocated) {
            $branch = User::findOne(['id' => $user_id]);
            $query = new Query;
            $query->select(['id', 'serial_no'])
                ->from('devices')
                ->where(['branch' => $branch->branch])
                ->andWhere(['border_port' => $userAllocated['border_port']])
                ->andWhere(['view_status' => Devices::in_transit]);
            $command = $query->createCommand();
            $response['devices'] = $command->queryAll();

            if ($response != '') {
                return $response;
            } else {
                throw new NotFoundHttpException("Devices not deactivated not found");
            }
        } else {

            throw new NotFoundHttpException("User not assigned Border");
        }


    }

    public function actionReturnIntransit()
    {

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $data = json_decode(file_get_contents("php://input"));

        $selection = $data->devices;
        $devices = json_decode(json_encode($selection), True);
        $user_id = $data->user_id;

        if ($devices != '' && $user_id != '') {

            foreach ($devices as $key => $value) {

                //$serial=$value['serial_no'];

                $e = Devices::find()
                    ->where(['id' => $value])
                    ->andWhere(['view_status' => Devices::in_transit])
                    ->one();

                if ($e) {

                    $movementID = strtoupper(Yii::$app->security->generateRandomString());
                    $datetime = date('Y-m-d H:i:s');

                    Devices::updateAll([
                        'received_from' => Devices::in_transit,
                        'created_at' => $datetime,
                        'created_by' => $user_id,
                        'view_status' => Devices::return_to_office,
                        'movement_unique_id' => $movementID,
                    ], ['serial_no' => $e['serial_no']]);


                    $success = Yii::$app->db->createCommand()
                        ->upsert(
                            'devices_reports',
                            [
                                'serial_no' => $e['serial_no'],
                                'received_from' => Devices::in_transit,
                                'received_to' => Devices::return_to_office,
                                'type' => $e['type'],
                                'device_category' => $e['device_category'],
                                'branch' => $e['branch'],
                                'created_by' => $user_id,
                                'created_at' => $datetime,
                                'movement_unique_id' => $movementID,
                            ],
                            false
                        )
                        ->execute();

                } else {
                    throw new NotFoundHttpException("Devices is not at On road Stage");
                }

            }

            return ['status' => '200', 'message' => 'Successfully Confirmed'];


        } else {

            throw new NotFoundHttpException("Devices or user can not be bank");
        }


    }


    public function actionDeviceToConfirm($user_id)
    {
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;
        $userAllocated = BorderPortUser::find()
            ->where(['name' => $user_id])
            ->one();
        if ($userAllocated) {

            $company = User::find()
                ->where(['id' => $user_id])
                ->one();

            if ($company) {
                $branch = $company->branch;

                if ($branch == 1) {

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


                    //   $branch = User::findOne(['id' => $user_id]);

                    $query = new Query;
                    $query->select(['id', 'serial_no'])
                        ->from('devices')
                        // ->where(['branch' => $branch->branch])
                        ->where(['in', 'branch', $branches])
                        ->andWhere(['border_port' => $userAllocated['border_port']])
                        ->andWhere(['view_status' => Devices::on_road]);

                    $command = $query->createCommand();
                    $response['devices'] = $command->queryAll();

                    if ($response != '') {
                        return $response;
                    } else {
                        throw new NotFoundHttpException("Devices moving to border not found");
                    }


                } else {

                    $branch = User::findOne(['id' => $user_id]);
                    $query = new Query;
                    $query->select(['id', 'serial_no'])
                        ->from('devices')
                        ->where(['branch' => $branch->branch])
                        //  ->where(['in','branch',$branches])
                        ->andWhere(['border_port' => $userAllocated['border_port']])
                        ->andWhere(['view_status' => Devices::on_road]);

                    $command = $query->createCommand();
                    $response['devices'] = $command->queryAll();

                    if ($response != '') {
                        return $response;
                    } else {
                        throw new NotFoundHttpException("Devices moving to border not found");
                    }


                }


            } else {
                throw new NotFoundHttpException("User not found");
            }
        } else {

            throw new NotFoundHttpException("User not assigned Border");
        }


    }

    public function actionConfirmReceive()
    {

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $data = json_decode(file_get_contents("php://input"));

        $selection = $data->devices;
        $devices = json_decode(json_encode($selection), True);
        $user_id = $data->user_id;

        if ($devices != '' && $user_id != '') {

            foreach ($devices as $key => $value) {

                //$serial=$value['serial_no'];

                $e = Devices::find()
                    ->where(['id' => $value])
                    ->andWhere(['view_status' => Devices::on_road])
                    ->one();

                if ($e) {

                    $movementID = strtoupper(Yii::$app->security->generateRandomString());
                    $datetime = date('Y-m-d H:i:s');

                    Devices::updateAll([
                        'received_from' => Devices::on_road,
                        'created_at' => $datetime,
                        'created_by' => $user_id,
                        'view_status' => Devices::border_received,
                        'movement_unique_id' => $movementID,
                    ], ['serial_no' => $e['serial_no']]);


                    $success = Yii::$app->db->createCommand()
                        ->upsert(
                            'devices_reports',
                            [
                                'serial_no' => $e['serial_no'],
                                'received_from' => Devices::on_road,
                                'received_to' => Devices::border_received,
                                'type' => $e['type'],
                                'device_category' => $e['device_category'],
                                'branch' => $e['branch'],
                                'created_by' => $user_id,
                                'created_at' => $datetime,
                                'movement_unique_id' => $movementID,
                            ],
                            false
                        )
                        ->execute();

                } else {
                    throw new NotFoundHttpException("Devices is not at On road Stage");
                }

            }

            return ['status' => '200', 'message' => 'Successfully Confirmed'];


        } else {

            throw new NotFoundHttpException("Devices or user can not be bank");
        }


    }

    public function actionDevicesAtBorderOld($user_id)
    {
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;
        $userAllocated = BorderPortUser::find()
            ->where(['name' => $user_id])
            ->one();

        if ($userAllocated) {
            $branch = User::findOne(['id' => $user_id]);
            $query = new Query;
            $query->select(['id', 'serial_no'])
                ->from('devices')
                ->where(['branch' => $branch->branch])
                ->andWhere(['border_port' => $userAllocated['border_port']])
                ->andWhere(['view_status' => Devices::border_received]);
            $command = $query->createCommand();
            $response['devices'] = $command->queryAll();

            if ($response != '') {
                return $response;
            } else {
                throw new NotFoundHttpException("Devices at border not found");
            }
        } else {

            throw new NotFoundHttpException("User not assigned Border");
        }


    }

    public function actionDevicesAtBorder($user_id)
    {
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;
        $userAllocated = BorderPortUser::find()
            ->where(['name' => $user_id])
            ->one();
        if ($userAllocated) {

            $company = User::find()
                ->where(['id' => $user_id])
                ->one();

            if ($company) {
                $branch = $company->branch;

                if ($branch == 1) {

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


                    //   $branch = User::findOne(['id' => $user_id]);

                    $query = new Query;
                    $query->select(['id', 'serial_no'])
                        ->from('devices')
                        // ->where(['branch' => $branch->branch])
                        ->where(['in', 'branch', $branches])
                        ->andWhere(['border_port' => $userAllocated['border_port']])
                        ->andWhere(['view_status' => Devices::border_received]);

                    $command = $query->createCommand();
                    $response['devices'] = $command->queryAll();

                    if ($response != '') {
                        return $response;
                    } else {
                        throw new NotFoundHttpException("Devices moving to border not found");
                    }


                } else {

                    $branch = User::findOne(['id' => $user_id]);
                    $query = new Query;
                    $query->select(['id', 'serial_no'])
                        ->from('devices')
                        ->where(['branch' => $branch->branch])
                        //  ->where(['in','branch',$branches])
                        ->andWhere(['border_port' => $userAllocated['border_port']])
                        ->andWhere(['view_status' => Devices::border_received]);

                    $command = $query->createCommand();
                    $response['devices'] = $command->queryAll();

                    if ($response != '') {
                        return $response;
                    } else {
                        throw new NotFoundHttpException("Devices moving to border not found");
                    }


                }


            } else {
                throw new NotFoundHttpException("User not found");
            }
        } else {

            throw new NotFoundHttpException("User not assigned Border");
        }


    }


    public function actionDevicesReturned($user_id, $date_from, $date_to)
    {
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;
        $userAllocated = BorderPortUser::find()
            ->where(['name' => $user_id])
            ->one();

        if ($userAllocated) {
            $branch = User::findOne(['id' => $user_id]);

            $border = BorderPort::find()
                ->where(['id' => $userAllocated->border_port])
                ->one();

            $data = DevicesReports::find()
                ->select(['devices_reports.id', 'devices_reports.serial_no', 'border_port.name as borderName', 'devices_reports.created_at'])
                ->leftJoin('border_port', 'border_port.id=devices_reports.border_port')
                ->from('devices_reports')
                ->where(['devices_reports.branch' => $branch->branch])
                //->andWhere(['devices_reports.border_port' => $userAllocated['border_port']])
                ->andWhere(['received_to' => Devices::return_to_office])
                ->andWhere(['between', 'date(devices_reports.created_at)', $date_from, $date_to])
                ->orderBy(['id' => SORT_DESC])
                ->asArray()
                ->all();
            if ($data) {

                $count = count($data);
                $response = [
                    'user' => $branch->username,
                    'total' => $count,
                    'border' => $border->name,
                    'date_from' => $date_from,
                    'date_to' => $date_to,
                    'devices' => $data
                ];
                return $response;

            } else {
                throw new NotFoundHttpException("Devices returned not found");
            }
        } else {

            throw new NotFoundHttpException("User not assigned Border");
        }


    }

    public function actionOfficeReturn()
    {

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $data = json_decode(file_get_contents("php://input"));

        $selection = $data->devices;
        $devices = json_decode(json_encode($selection), True);
        $user_id = $data->user_id;

        if ($devices != '' && $user_id != '') {

            foreach ($devices as $key => $value) {

                //$serial=$value['serial_no'];

                $e = Devices::find()
                    ->where(['id' => $value])
                    ->andWhere(['view_status' => Devices::border_received])
                    ->one();

                if ($e) {

                    $movementID = strtoupper(Yii::$app->security->generateRandomString());
                    $datetime = date('Y-m-d H:i:s');

                    Devices::updateAll([
                        'received_from' => Devices::border_received,
                        'created_at' => $datetime,
                        'created_by' => $user_id,
                        'view_status' => Devices::return_to_office,
                        'movement_unique_id' => $movementID,
                    ], ['serial_no' => $e['serial_no']]);


                    $success = Yii::$app->db->createCommand()
                        ->upsert(
                            'devices_reports',
                            [
                                'serial_no' => $e['serial_no'],
                                'received_from' => Devices::border_received,
                                'received_to' => Devices::return_to_office,
                                'type' => $e['type'],
                                'border_port' => $e['border_port'],
                                'device_category' => $e['device_category'],
                                'branch' => $e['branch'],
                                'created_by' => $user_id,
                                'created_at' => $datetime,
                                'movement_unique_id' => $movementID,
                            ],
                            false
                        )
                        ->execute();

                } else {
                    throw new NotFoundHttpException("Devices is not at On road Stage");
                }

            }

            return ['status' => '200', 'message' => 'Successfully Confirmed'];


        } else {

            throw new NotFoundHttpException("Devices or user can not be bank");
        }


    }


    public function actionScan()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $data = json_decode(file_get_contents("php://input"));
        $receipt_no = $data->receipt_no;
        $user_id = $data->user_id;

        if ($receipt_no != '' && $user_id != '') {

            $sale = SalesTrips::find()
                // ->where(['trip_status'=>1])
                ->andWhere(['receipt_number' => $receipt_no])
                ->one();
            if ($sale) {

                $status = $sale->trip_status;

                if ($status == 1) {

                    $verified = $sale->scanned_status;

                    if ($verified == 1) {

                        throw new NotAcceptableHttpException("Receipt was already scanned");

                    } else {
                        SalesTrips::updateAll([
                            'scanned_status' => 1,
                            'scanned_by' => $user_id,
                            'scanned_date' => date('Y-m-d H:i:s'),
                        ], ['receipt_number' => $receipt_no]);

                        return ['status' => '200', 'message' => 'Successfully Verified'];
                    }


                } elseif ($status == 2) {

                    SalesTrips::updateAll([
                        'scanned_by' => 1,
                        'scanned_date' => date('Y-m-d H:i:s'),
                    ], ['receipt_number' => $receipt_no]);

                    // return ['status' => '200', 'message' => 'This receipt was edited'];

                    throw new NotFoundHttpException("This receipt was edited");


                } else {

                    SalesTrips::updateAll([
                        'scanned_by' => 1,
                        'scanned_date' => date('Y-m-d H:i:s'),
                    ], ['receipt_number' => $receipt_no]);

                    // return ['status' => '200', 'message' => 'This receipt was cancelled'];

                    throw new NotFoundHttpException("This receipt was cancelled");

                }

            } else {
                $sale = SalesTrips::find()
                    //  ->where(['trip_status'=>1])
                    ->andWhere(['offline_receipt_number' => $receipt_no])
                    ->one();
                if ($sale) {

                    $status = $sale->trip_status;
                    if ($status == 1) {

                        $verified = $sale->scanned_status;
                        if ($verified == 1) {

                            throw new NotAcceptableHttpException("Receipt was already scanned");

                        } else {
                            SalesTrips::updateAll([
                                'scanned_status' => 1,
                                'scanned_by' => $user_id,
                                'scanned_date' => date('Y-m-d H:i:s'),
                            ], ['offline_receipt_number' => $receipt_no]);

                            return ['status' => '200', 'message' => 'Successfully Verified'];
                        }


                    } elseif ($status == 2) {

                        SalesTrips::updateAll([
                            'scanned_status' => 1,
                            'scanned_by' => $user_id,
                            'scanned_date' => date('Y-m-d H:is:s'),
                        ], ['offline_receipt_number' => $receipt_no]);

                        // return ['status' => '200', 'message' => 'This receipt was edited'];

                        throw new NotFoundHttpException("This receipt was edited");

                    } else {

                        SalesTrips::updateAll([
                            'scanned_status' => 1,
                            'scanned_by' => $user_id,
                            'scanned_date' => date('Y-m-d H:is:s'),
                        ], ['offline_receipt_number' => $receipt_no]);

                        throw new NotFoundHttpException("This receipt was cancelled");
                        // return ['status' => '200', 'message' => 'This receipt was cancelled'];

                    }

                } else {
                    $user = User::find()
                        ->where(['id' => $user_id])
                        ->one();
                    if ($user) {
                        Audit::setActivity($user->username . " Amescan receipt yenye namba $receipt_no , ambayo kwenye database haipo", 'SaleReport', 'Index', '', '');
                        throw new NotFoundHttpException("This receipt is fake OR Still is offline");
                    } else {
                        throw new NotFoundHttpException("user not found");
                    }


                }

            }


        } else {
            throw new BadRequestHttpException("User ID and receipt no can not be empty");
        }

    }


    public static function getAll()
    {
        return Devices::find()->select(['id', 'serial_no'])->where(['type' => 3, 'view_status' => 5,])
            ->all();
    }


    protected function verbs()
    {
        return [
            'login' => ['POST'],
            'released' => ['GET'],
            'sale-add' => ['POST'],
            'scan' => ['POST'],
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
            'search-tzl' => ['GET'],

        ];
    }
}