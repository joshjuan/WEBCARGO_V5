<?php

namespace frontend\controllers;

use frontend\models\AwaitingReceive;
use frontend\models\AwaitingReceiveReport;
use frontend\models\Devices;
use frontend\models\ReceivedDevices;
use frontend\models\ReleasedDevicesReport;
use Yii;
use frontend\models\ReleasedDevices;
use frontend\models\ReleasedDevicesSearch;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ReleasedDevicesController implements the CRUD actions for ReleasedDevices model.
 */
class ReleasedDevicesController extends Controller
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
     * Lists all ReleasedDevices models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ReleasedDevicesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSearch()
    {
        $searchModel = new ReleasedDevicesSearch();
        $dataProvider = $searchModel->searchSearch(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public  function actionReturn(){

        $date =date('Y-m-d', strtotime('-2 days'));
        $released=ReleasedDevices::find()
             ->where(['date(released_date)'=>$date])
            ->andWhere(['view_status'=>Devices::released_devices])
            ->all();

        foreach ($released as $release) {

            $data = ReleasedDevices::find()->where(['id' =>$release->id])
                ->andWhere(['view_status'=>Devices::released_devices])->one();
                AwaitingReceive::updateAll([
                    'received_from' => 2,
                    'border_port' => $data['sales_point'],
                    'received_from_staff' => $data['released_to'],
                    'received_status' => 1,
                    'remark' => 'AUTOMATIC RETURN TO OFFICE AFTER 24 HOURS',
                    'received_at' => date('Y-m-d H:i:s'),
                    'received_by' =>  $data['released_to'],
                    'view_status'=>Devices::awaiting_receive,
                ],['serial_no'=>$data['serial_no']]);

                $report = new AwaitingReceiveReport();
                $report->serial_no = $data['serial_no'];
                $report->received_from = 2;
                $report->border_port = $data['sales_point'];
                $report->received_from_staff = $data['released_to'];
                $report->received_at = date('Y-m-d H:i:s');
                $report->received_status = 1;
                $report->remark = 'AUTOMATIC RETURN TO OFFICE AFTER 24 HOURS';
                $report->received_by = $data['released_to'];

            ReleasedDevices::updateAll(['view_status'=>Devices::awaiting_receive,'released_date'=>null,
                'released_by'=>null,'released_to'=>null,'sales_point'=>null,'transferred_from'=>null,
                'transferred_to'=>null,'transferred_date'=>null,'transferred_by'=>null],['serial_no'=>$data['serial_no']]);


        }

    }

    /**
     * Displays a single ReleasedDevices model.
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
     * Creates a new ReleasedDevices model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ReleasedDevices();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ReleasedDevices model.
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
     * Deletes an existing ReleasedDevices model.
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

    /**
     * Finds the ReleasedDevices model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ReleasedDevices the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ReleasedDevices::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionTransfer()
    {

        if (Yii::$app->user->can('transferDevices')) {
            $action = Yii::$app->request->post('action');
            $points = Yii::$app->request->post('points');

            $selection = (array)Yii::$app->request->post('selection');
           //   print_r($action);
           //  exit;

            foreach ($selection as $id) {
                //  $e = StockDevices::findOne((int)$id);

                if ($selection != '') {

                    if ($action != '' && $points != '') {
                        $saler = ReleasedDevices::find()->where(['id' => $selection])->one();
                        foreach ($selection as $key => $value) {

                            $report = ReleasedDevices::find()->where(['id' => $value])->one();

                            ReleasedDevicesReport::updateAll(['transferred_from' => $saler['released_to'],
                                'transferred_by' => Yii::$app->user->identity->id, 'released_to' => $action,
                                'transferred_to' => $action, 'transferred_date' => date('Y-m-d H:i:s'),
                                'status' => 2, 'sales_point' => $points],
                                ['serial_no'=>$report['serial_no'],'released_to'=>$report['released_to']]);


                            ReleasedDevices::updateAll(['transferred_from' => $saler['released_to'],
                                'transferred_by' => Yii::$app->user->identity->id, 'released_to' => $action,
                                'transferred_to' => $action, 'transferred_date' => date('Y-m-d H:i:s'),
                                'status' => 2, 'sales_point' => $points], ['id' => $value]);



                        }

                        Yii::$app->session->setFlash('', [
                            'type' => 'success',
                            'duration' => 5000,
                            'icon' => 'fa fa-check',
                            'message' => 'Total device ' . count($selection) . ' have been  successfully transferred',
                            'positonY' => 'top',
                            'positonX' => 'right',
                        ]);

                        return $this->redirect(['index']);

                    }

                    if ($action == 42 || $action == 71) {

                        foreach ($selection as $key => $value) {

                                $data = ReleasedDevices::find()->where(['id' => $value])->one();

                                AwaitingReceive::updateAll([
                                    'received_from' => 2,
                                    'border_port' => $data['sales_point'],
                                    'received_from_staff' => $data['released_to'],
                                    'received_at' => date('Y-m-d H:i:s'),
                                    'received_status' => 1,
                                    'received_by' => Yii::$app->user->identity->getId(),
                                    'view_status' => Devices::awaiting_receive,
                                ], ['serial_no' => $data['serial_no']]);


                                $report = new AwaitingReceiveReport();
                                $report->serial_no = $data['serial_no'];
                                $report->received_from = 2;
                                $report->border_port = $data['sales_point'];
                                $report->received_from_staff = $data['released_to'];
                                $report->received_at = date('Y-m-d H:i:s');
                                $report->received_status = 1;
                                $report->received_by = Yii::$app->user->identity->getId();
                                $report->save();

                                ReleasedDevices::updateAll(['view_status' => Devices::awaiting_receive], ['serial_no' => $data['serial_no']]);

                            }
                        Yii::$app->session->setFlash('', [
                            'type' => 'success',
                            'duration' => 5000,
                            'icon' => 'fa fa-check',
                            'message' => 'Total device ' . count($selection) . ' have been  successfully transferred',
                            'positonY' => 'top',
                            'positonX' => 'right',
                        ]);

                        return $this->redirect(['index']);



                    }
                    else {
                        Yii::$app->session->setFlash('', [
                            'type' => 'danger',
                            'duration' => 5000,
                            'icon' => 'fa fa-check',
                            'message' => 'You have not selected Sales Person or Sales Point ',
                            'positonY' => 'top',
                            'positonX' => 'right',
                        ]);
                        return $this->redirect(['index']);
                    }
                } else {
                    Yii::$app->session->setFlash('', [
                        'type' => 'danger',
                        'duration' => 5000,
                        'icon' => 'fa fa-check',
                        'message' => 'Please select devices to transfer ',
                        'positonY' => 'top',
                        'positonX' => 'right',
                    ]);
                    return $this->redirect(['index']);
                }


            }

            return $this->redirect(['index']);
        } else {
            Yii::$app->session->setFlash('', [
                'type' => 'danger',
                'duration' => 5000,
                'icon' => 'fa fa-check',
                'message' => 'You do not have permission to transfer device',
                'positonY' => 'top',
                'positonX' => 'right',
            ]);
            return $this->redirect(['index']);
        }
    }

}
