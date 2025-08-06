<?php

namespace backend\controllers;

use backend\models\Devices;
use backend\models\InTransit;
use backend\models\ReceivedDevices;
use backend\models\ReleasedDevices;
use backend\models\ReleasedDevicesReport;
use backend\models\SalesTripSlaves;
use backend\models\StockDevices;
use DateTime;
use Yii;
use backend\models\SalesTrips;
use backend\models\SalesTripsSearch;
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
            }


            $new=new SalesTrips();
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
            }

            if ($model->load(Yii::$app->request->post())) {


                $remarks = $_POST['SalesTrips']['remarks'];
                $model->remarks=$remarks;
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
