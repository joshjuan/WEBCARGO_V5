<?php

namespace frontend\controllers;

use backend\models\Devices;
use backend\models\InTransit;
use backend\models\ReceivedDevices;
use backend\models\ReleasedDevices;
use backend\models\ReleasedDevicesReport;
use backend\models\StockDevices;
use DateTime;
use frontend\models\SalesTripSlaves;
use Yii;
use frontend\models\SalesTrips;
use frontend\models\SalesTripsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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


            $branch = \frontend\models\Branches::find()
                ->where(['id' => Yii::$app->user->identity->branch])
                ->one();

            if ($branch->branch_type == 1) {
                $searchModel = new SalesTripsSearch();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


                return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            } else {

                $searchModel = new SalesTripsSearch();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

                return $this->render('index_partner', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }


        } else {

            Yii::$app->session->setFlash('', [
                'type' => 'danger',
                'duration' => 5000,
                'icon' => 'fa fa-check',
                'message' => 'You do not have permission',
                'positonY' => 'top',
                'positonX' => 'right',
            ]);

            return $this->redirect(['site/index']);
        }
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
        if (Yii::$app->user->can('viewSalesTripReport')) {

            $searchModel = new SalesTripsSearch();
            $dataProvider = $searchModel->searchDevice(Yii::$app->request->queryParams);

            return $this->render('device', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);

        } else {
            Yii::$app->session->setFlash('', [
                'type' => 'danger',
                'duration' => 5000,
                'icon' => 'fa fa-check',
                'message' => 'You do not have permission',
                'positonY' => 'top',
                'positonX' => 'right',
            ]);

            return $this->redirect(['site/index']);

        }
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
    public function actionCreate()
    {
        $model = new SalesTrips();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
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
        return $this->redirect(['index']);

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
        //  $this->findModel($id)->delete();

        //   return $this->redirect(['index']);
    }

    public function actionCancelOld($id)
    {
        if (Yii::$app->user->can('cancelSaleTrip')) {

            $model = $this->findModel($id);
            $cancel = SalesTrips::find()->where(['id' => $id])->one();

            $canceled = $cancel['tzl'] . '-CANCELED';
            $check_canceled = SalesTrips::find()->where(['tzl' => $canceled])->one();

            if (empty($check_canceled)) {
                $model->tzl = $cancel['tzl'] . '-CANCELED';
                $model->cancelled_by = Yii::$app->user->identity->getId();
                $model->date_cancelled = date('Y-m-d H:m"i');
                $model->trip_status = SalesTrips::CANCELED;
                //  $model->remarks = SalesTrips::CANCELED;

                if ($model->save(false)) {

//                $report = ReleasedDevicesReport::find()->where(['serial_no' => $cancel['serial_no']])->orderBy(['released_date' => SORT_DESC])->one();
//
//                ReleasedDevices::updateAll([
//                    'released_date' => $report['released_date'],
//                    'released_by' => $report['released_by'],
//                    'released_to' => $report['released_to'],
//                    'sales_point' => $report['sales_point'],
//                    'transferred_from' => $report['transferred_from'],
//                    'transferred_to' => $report['transferred_to'],
//                    'transferred_date' => $report['transferred_date'],
//                    'transferred_by' => $report['transferred_by'],
//                    'status' => $report['status'],
//                    'view_status' => Devices::released,
//                ], ['serial_no' => $report['serial_no']]);
//
//                InTransit::updateAll(['view_status' => Devices::released], ['tzl' => $cancel['tzl']]);


                    Yii::$app->session->setFlash('', [
                        'type' => 'success',
                        'duration' => 5000,
                        'icon' => 'fa fa-check',
                        'message' => 'Cancel is successfully',
                        'positonY' => 'top',
                        'positonX' => 'right',
                    ]);

                    return $this->redirect(['view', 'id' => $id]);

                } else {
                    Yii::$app->session->setFlash('', [
                        'type' => 'danger',
                        'duration' => 5000,
                        'icon' => 'fa fa-check',
                        'message' => 'Failed to cancel',
                        'positonY' => 'top',
                        'positonX' => 'right',
                    ]);

                    return $this->redirect(['view', 'id' => $id]);
                }


            } else {

                Yii::$app->session->setFlash('', [
                    'type' => 'success',
                    'duration' => 5000,
                    'icon' => 'fa fa-check',
                    'message' => 'Sales trip can not be cancelled twice',
                    'positonY' => 'top',
                    'positonX' => 'right',
                ]);

                return $this->redirect(['view', 'id' => $id]);
            }


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

    public function actionCancel($id)
    {
        if (Yii::$app->user->can('cancelSaleTrip')) {
            $model = $this->findModel($id);

            $time = new \DateTime('now');
            $datetime2 = new DateTime($model->sale_date);
            $interval = $time->diff($datetime2)->days;

            if($interval >3){

                Yii::$app->session->setFlash('', [
                    'type' => 'danger',
                    'duration' => 5000,
                    'icon' => 'fa fa-check',
                    'message' => 'You can not cancel records which has more than 3 days',
                    'positonY' => 'top',
                    'positonX' => 'right',
                ]);
                return $this->redirect(['view', 'id' => $id]);
                //return $this->redirect(['index']);
            }


            $new = new SalesTrips();

            if ($model->trip_status == 3) {

                Yii::$app->session->setFlash('', [
                    'type' => 'danger',
                    'duration' => 5000,
                    'icon' => 'fa fa-check',
                    'message' => 'You can not cancel twice',
                    'positonY' => 'top',
                    'positonX' => 'right',
                ]);

                return $this->redirect(['view', 'id' => $id]);

              //  return $this->redirect(['index']);

            }

            if ($model->load(Yii::$app->request->post())) {
                $remarks = $_POST['SalesTrips']['remarks'];
                $model->remarks = $remarks;

                $model->cancelled_by = Yii::$app->user->identity->getId();
                $model->date_cancelled = date('Y-m-d H:i:s');
                $model->trip_status = SalesTrips::CANCELED;
                if ($model->save(false)) {

                    SalesTripSlaves::updateAll([
                        'status' => 0
                    ],
                        ['in', 'sale_id', $id]);

                    Devices::updateAll([
                        'view_status' => Devices::awaiting_allocation,
                        'remark' => 'Device sale cancelled',
                    ],
                        ['in', 'sale_id', $id]);

                    Yii::$app->session->setFlash('', [
                        'type' => 'success',
                        'duration' => 5000,
                        'icon' => 'fa fa-check',
                        'message' => 'Cancel is successfully',
                        'positonY' => 'top',
                        'positonX' => 'right',
                    ]);

                    return $this->redirect(['index']);
                    //return $this->redirect(['view', 'id' => $id]);

                } else {
                    Yii::$app->session->setFlash('', [
                        'type' => 'danger',
                        'duration' => 5000,
                        'icon' => 'fa fa-check',
                        'message' => 'Failed to cancel',
                        'positonY' => 'top',
                        'positonX' => 'right',
                    ]);

                    return $this->redirect(['view', 'id' => $id]);
                }
            }

        } else {

            Yii::$app->session->setFlash('', [
                'type' => 'success',
                'duration' => 5000,
                'icon' => 'fa fa-check',
                'message' => 'You do not have permission',
                'positonY' => 'top',
                'positonX' => 'right',
            ]);

            return $this->redirect(['view', 'id' => $id]);
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
