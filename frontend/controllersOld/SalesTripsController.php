<?php

namespace frontend\controllers;
set_time_limit(0);
ini_set('memory_limit', '20000M');

use backend\models\Receipt;
use frontend\models\BorderPort;
use frontend\models\Devices;
use frontend\models\InTransit;
use frontend\models\ReceivedDevices;
use frontend\models\ReleasedDevices;
use frontend\models\ReleasedDevicesReport;
use frontend\models\StockDevices;
use frontend\models\Uploads;
use frontend\models\User;
use Yii;
use frontend\models\SalesTrips;
use frontend\models\SalesTripsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * SalesTripsController implements the CRUD actions for SalesTrips model.
 */
class SalesTripsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all SalesTrips models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->can('viewSalesTripReport')) {
            $searchModel = new SalesTripsSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);

        } else {
            return $this->redirect(['site/index']);
        }
    }


    public function actionReport()
    {

        $currentDate = date('Y-m-d 09:00:00');
        $PreviousDate = date('Y-m-d 09:00:00', strtotime($currentDate . ' -1 day'));
        $amount = SalesTrips::find()
            //  ->select(['sales_person', 'count(id) as sold_items', 'date(sale_date) as sale_date', 'sum(amount) as amount'])
            ->select(['amount'])
            // ->where(['branch' => \Yii::$app->user->identity->branch])
            ->andWhere(['between', 'date(sale_date)', $PreviousDate, $currentDate])
            ->andWhere(['sale_type' => 1])
            //  ->andWhere(['customer_id'=>\Yii::$app->user->identity->getId()])
            ->andWhere(['trip_status' => SalesTrips::NORMAL])
            ->sum('amount');

        $devices = SalesTrips::find()
            //  ->select(['sales_person', 'count(id) as sold_items', 'date(sale_date) as sale_date', 'sum(amount) as amount'])
            ->select(['amount'])
            // ->where(['branch' => \Yii::$app->user->identity->branch])
            ->andWhere(['between', 'date(sale_date)', $PreviousDate, $currentDate])
            ->andWhere(['sale_type' => 1])
            //  ->andWhere(['customer_id'=>\Yii::$app->user->identity->getId()])
            ->andWhere(['trip_status' => SalesTrips::NORMAL])
            ->count('id');

        print_r($devices);

    }


    public function actionTwoDays()
    {
        if (Yii::$app->user->can('viewTwoDaysSalesTripReport')) {
            $searchModel = new SalesTripsSearch();
            $dataProvider = $searchModel->searchTwoDays(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } else {
            return $this->redirect(['site/index']);
        }

    }

    public function actionDevice()
    {
        $searchModel = new SalesTripsSearch();
        $dataProvider = $searchModel->searchDevice(Yii::$app->request->queryParams);

        return $this->render('device', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SalesTrips model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SalesTrips model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionNew()
    {
        $model = new SalesTrips();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    public function actionCreate()
    {
        // if (Yii::$app->user->can('createPaymentRecords')) {

        $model = new SalesTrips();

        $upload = new Uploads();
        $upload->created_at = date('Y-m-d H:i:s');
        $upload->created_by = Yii::$app->user->identity->getId();
        $model->created = Yii::$app->user->identity->username;

        if ($model->load(Yii::$app->request->post())) {


            if (empty($model->customer_id)) {
                Yii::$app->session->setFlash('', [
                    'type' => 'danger',
                    'duration' => 4500,
                    'icon' => 'fa fa-warning',
                    'title' => 'Notification',
                    'message' => 'Customer can not be empty',
                    'positonY' => 'top',
                    'positonX' => 'right'
                ]);
                return $this->redirect(['index',]);

            }

            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->file != '') {

                $model->file = UploadedFile::getInstance($model, 'file');
                $model->file->saveAs('uploads/devices/' . 'UPLOAD-' . date('YmdHi') . $model->customer_id . '.' . $model->file->extension);
                $upload->name = 'UPLOAD-' . date('YmdHi') . $model->customer_id . '.' . $model->file->extension;

                $upload->save(false);


                define('CSV_PATH', 'uploads/devices/' . $upload->name);
                $csv_file = CSV_PATH . '';
                $filecsv = file($csv_file);
                $items = $filecsv;
                $keys = array_keys($filecsv);
                $arraySize = count($filecsv);

                for ($i = 0; $i < $arraySize; $i++) {

                    $string = $keys[$i] . ',';
                    $string_serialize = rtrim($string, ", ");
                    $array = explode(' ', $string_serialize);


                    foreach ($array as $key => $single) {

                        $objects = $items[$single];
                        $fileop = explode(",", $objects);
                        $id = $fileop[0];
                        $date = $fileop[1];
                        $truck_no = $fileop[2];
                        $tzdl_no = $fileop[3];
                        $device_serial = $fileop[4];
                        $gate = $fileop[5];
                        $border = $fileop[6];
                        $chasis = $fileop[7];
                        $container = $fileop[8];
                        $driver_name = $fileop[9];
                        $driver_phone = $fileop[10];


                        if (empty($CheckTZDL)) {

                            $GateNo = BorderPort::find()
                                ->where(['like', 'name', $gate . '%', false])
                                ->one();

                            if (empty($date)) {
                                Yii::$app->session->setFlash('', [
                                    'type' => 'danger',
                                    'duration' => 4500,
                                    'icon' => 'fa fa-warning',
                                    'title' => 'Notification',
                                    'message' => 'Sale date can not be empty',
                                    'positonY' => 'top',
                                    'positonX' => 'right'
                                ]);
                                return $this->redirect(['index',]);

                            }

                            if (empty($tzdl_no)) {
                                Yii::$app->session->setFlash('', [
                                    'type' => 'danger',
                                    'duration' => 4500,
                                    'icon' => 'fa fa-warning',
                                    'title' => 'Notification',
                                    'message' => 'TZDL can not be empty',
                                    'positonY' => 'top',
                                    'positonX' => 'right'
                                ]);
                                return $this->redirect(['index',]);

                            }

                            if (empty($truck_no)) {
                                Yii::$app->session->setFlash('', [
                                    'type' => 'danger',
                                    'duration' => 4500,
                                    'icon' => 'fa fa-warning',
                                    'title' => 'Notification',
                                    'message' => 'Vehicle number can not be empty',
                                    'positonY' => 'top',
                                    'positonX' => 'right'
                                ]);
                                return $this->redirect(['index',]);

                            }

                            if (empty($GateNo)) {
                                Yii::$app->session->setFlash('', [
                                    'type' => 'danger',
                                    'duration' => 4500,
                                    'icon' => 'fa fa-warning',
                                    'title' => 'Notification',
                                    'message' => 'There is no gate number associated with your input',
                                    'positonY' => 'top',
                                    'positonX' => 'right'
                                ]);
                                return $this->redirect(['index',]);

                            }

                            $BorderNo = BorderPort::find()
                                ->where(['like', 'name', $border . '%', false])
                                ->one();

                            if (empty($BorderNo)) {
                                Yii::$app->session->setFlash('', [
                                    'type' => 'danger',
                                    'duration' => 4500,
                                    'icon' => 'fa fa-warning',
                                    'title' => 'Notification',
                                    'message' => 'There is no border number associated with your input',
                                    'positonY' => 'top',
                                    'positonX' => 'right'
                                ]);
                                return $this->redirect(['index',]);

                            }

                            if (!empty($id)) {

                                $companyName = User::findOne(['id' => $model->customer_id]);

                                Yii::$app->db->createCommand()
                                    ->insert('sales_trips', [
                                        'sale_date' => $date,
                                        'start_date_time' => $date,
                                        'end_date' => date('Y-m-d', strtotime($date . '+7 day')),
                                        'tzl' => $tzdl_no,
                                        'serial_no' => $device_serial,
                                        'vehicle_number' => $truck_no,
                                        'gate_number' => $GateNo->id,
                                        'border_id' => $BorderNo->id,
                                        'chasis_number' => $chasis,
                                        'container_number' => $container,
                                        'driver_name' => $driver_name,
                                        'driver_number' => $driver_phone,
                                        'customer_type_id' => SalesTrips::BILL,
                                        'customer_id' => $model->customer_id,
                                        'company_name' => $companyName->company_name,
                                        'amount' => 0.00,
                                        'trip_status' => SalesTrips::NORMAL,
                                        'sale_type' => SalesTrips::BILL,
                                        'branch' => 1,
                                        'type' => 1,
                                        'created_by' => Yii::$app->user->identity->getId(),
                                        'receipt_number' => $model->sales_person . Receipt::findBill(),
                                    ])->execute();


                            } else {

                                Yii::$app->session->setFlash('', [
                                    'type' => 'success',
                                    'duration' => 4500,
                                    'icon' => 'fa fa-warning',
                                    'title' => 'Notification',
                                    'message' => 'Successfully Created',
                                    'positonY' => 'top',
                                    'positonX' => 'right'
                                ]);
                                return $this->redirect(['index',]);

                            }

                        }

                    }
                }


            } else {

                Yii::$app->session->setFlash('', [
                    'type' => 'danger',
                    'duration' => 4500,
                    'icon' => 'fa fa-warning',
                    'title' => 'Notification',
                    'message' => 'Please Attach document file',
                    'positonY' => 'top',
                    'positonX' => 'right'
                ]);
                return $this->redirect(['index',]);

            }


            Yii::$app->session->setFlash('', [
                'type' => 'success',
                'duration' => 4500,
                'icon' => 'fa fa-warning',
                'title' => 'Notification',
                'message' => 'Successfully Created',
                'positonY' => 'top',
                'positonX' => 'right'
            ]);
            return $this->redirect(['index',]);


        }

        return $this->render('create', [
            'model' => $model,

        ]);


        //}

//        else {
//            Yii::$app->session->setFlash('', [
//                'type' => 'danger',
//                'duration' => 4500,
//                'icon' => 'fa fa-warning',
//                'title' => 'Notification',
//                'message' => 'You do not have permission to create',
//                'positonY' => 'top',
//                'positonX' => 'right'
//            ]);
//
//            return $this->redirect(['/',]);
//        }

    }

    /**
     * Updates an existing SalesTrips model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SalesTrips model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionCancel($id)
    {
        if (Yii::$app->user->can('deleteSalesTrip')) {

            $model = $this->findModel($id);
            $cancel = SalesTrips::find()->where(['id' => $id])->one();

            $canceled = $cancel['tzl'] . '-CANCELED';
            $check_canceled = SalesTrips::find()->where(['tzl' => $canceled])->one();
            if (empty($check_canceled)) {
                $model->tzl = $cancel['tzl'] . '-CANCELED';

                $model->cancelled_by = Yii::$app->user->identity->getId();
                $model->date_cancelled = date('Y-m-d H:m"i');
                $model->trip_status = SalesTrips::CANCELED;
            } else {
                $model->tzl = $cancel['tzl'] . '-CANCELED1';

                $model->cancelled_by = Yii::$app->user->identity->getId();
                $model->date_cancelled = date('Y-m-d H:m"i');
                $model->trip_status = SalesTrips::CANCELED;
            }


            if ($model->save()) {

                $report = ReleasedDevicesReport::find()->where(['serial_no' => $cancel['serial_no']])->orderBy(['released_date' => SORT_DESC])->one();

                Devices::updateAll([
                    'released_date' => $report['released_date'],
                    'released_by' => $report['released_by'],
                    'released_to' => $report['released_to'],
                    'border_port' => $report['sales_point'],
                    'transferred_from' => $report['transferred_from'],
                    'transferred_to' => $report['transferred_to'],
                    'transferred_date' => $report['transferred_date'],
                    'transferred_by' => $report['transferred_by'],
                    'status' => $report['status'],
                    'view_status' => Devices::released_devices,
                ], ['serial_no' => $report['serial_no']]);

                Devices::updateAll(['view_status' => Devices::released_devices], ['tzl' => $cancel['tzl']]);


            }

            Yii::$app->session->setFlash('', [
                'type' => 'success',
                'duration' => 5000,
                'icon' => 'fa fa-check',
                'message' => 'Successfully cancelled',
                'positonY' => 'top',
                'positonX' => 'right',
            ]);

            return $this->redirect(['index']);

        } else {
            Yii::$app->session->setFlash('', [
                'type' => 'danger',
                'duration' => 5000,
                'icon' => 'fa fa-warning',
                'message' => 'You dont have a permissions',
                'positonY' => 'top',
                'positonX' => 'right',
            ]);

            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the SalesTrips model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SalesTrips the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SalesTrips::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
