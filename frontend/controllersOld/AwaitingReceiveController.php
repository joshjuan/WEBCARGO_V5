<?php

namespace frontend\controllers;

use frontend\models\AwaitingReceiveReportSearch;
use frontend\models\BorderPort;
use frontend\models\Devices;
use frontend\models\InTransit;
use frontend\models\ReceivedDevices;
use frontend\models\ReceivedDevicesReport;
use frontend\models\StockDevices;
use common\models\LoginForm;
use frontend\models\StockDevicesReport;
use Yii;
use frontend\models\AwaitingReceive;
use frontend\models\AwaitingReceiveSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AwaitingReceiveController implements the CRUD actions for AwaitingReceive model.
 */
class AwaitingReceiveController extends Controller
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
     * Lists all AwaitingReceive models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            $searchModel = new AwaitingReceiveSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
        else {
            $model = new LoginForm();
            return $this->redirect(['site/login',
                'model' => $model,
            ]);
        }
    }

    public function actionActive()
    {
        $searchModel = new AwaitingReceiveSearch();
        $dataProvider = $searchModel->searchActive(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AwaitingReceive model.
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
     * Creates a new AwaitingReceive model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AwaitingReceive();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing AwaitingReceive model.
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
     * Deletes an existing AwaitingReceive model.
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
     * Finds the AwaitingReceive model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AwaitingReceive the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AwaitingReceive::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionReceive(){


        Yii::$app->db->transaction(function(){

            $action=Yii::$app->request->post('action');

            $selection=(array)Yii::$app->request->post('selection');

            foreach($selection as $id) {
                //  $e = ReceivedDevices::findOne((int)$id);


                if ($action == 1){
                    foreach ($selection as $key => $value) {

                        try {
                            $e = AwaitingReceive::find()->where(['id' => $value])->one();

                            ReceivedDevices::updateAll([
                                'received_from' =>  BorderPort::Border,
                                'border_port' => $e['border_port'],
                                'received_status' =>  StockDevices::available,
                                'received_at' => date('Y-m-d H:i:s'),
                                'received_by' => Yii::$app->user->identity->id,
                                'view_status'=>Devices::received_devices,
                            ],['serial_no'=>$e['serial_no']]);

                            AwaitingReceive::updateAll(['view_status'=>Devices::received_devices,'remark'=>null],['serial_no'=>$e['serial_no']]);


                            $stock = new ReceivedDevicesReport();
                            $stock->serial_no = $e['serial_no'];
                            $stock->received_status = StockDevices::available;
                            $stock->received_by = Yii::$app->user->identity->id;
                            $stock->received_from = $e['received_from'];
                            $stock->border_port = $e['border_port'];
                            $stock->received_at = date('Y-m-d H:i:s');
                            $stock->save();

                        } catch (\Exception $e) {
                            Yii::$app->session->setFlash('', [
                                'type' => 'success',
                                'duration' => 5000,
                                'icon' => 'fa fa-check',
                                'message' => 'Total device '.count($selection).' have been  successfully received',
                                'positonY' => 'top',
                                'positonX' => 'right',
                            ]);

                            return $this->redirect(['index']);
                        }

                    }


                    Yii::$app->session->setFlash('', [
                        'type' => 'success',
                        'duration' => 5000,
                        'icon' => 'fa fa-check',
                        'message' => 'Total device '.count($selection).' have been  successfully received',
                        'positonY' => 'top',
                        'positonX' => 'right',
                    ]);

                    return $this->redirect(['index']);

                }
                elseif ($action == 2){
                    foreach ($selection as $key => $value) {
                        $e = AwaitingReceive::find()->where(['id'=>$value])->one();

                        StockDevices::updateAll(['status'=>StockDevices::not_deactivated,
                            'created_by'=>Yii::$app->user->identity->id,
                            'location_from'=>$e['border_port'],
                            'view_status'=>Devices::stock_devices,
                            'created_at' => date('Y-m-d H:i:s')],['serial_no'=>$e['serial_no']]);

                        AwaitingReceive::updateAll(['view_status'=>Devices::stock_devices],['serial_no'=>$e['serial_no']]);

                        $stock = new StockDevicesReport();
                        $stock->serial_no = $e['serial_no'];
                        $stock->status = StockDevices::not_deactivated;
                        $stock->location_from =$e['border_port'];
                        $stock->created_by = Yii::$app->user->identity->id;
                        $stock->created_at = date('Y-m-d H:i:s');
                        $stock->save();

                    }


                    Yii::$app->session->setFlash('', [
                        'type' => 'success',
                        'duration' => 5000,
                        'icon' => 'fa fa-check',
                        'message' => 'Total device '.count($selection).' have been  successfully allocated as available',
                        'positonY' => 'top',
                        'positonX' => 'right',
                    ]);

                    return $this->redirect(['index']);

                }

                else{
                    Yii::$app->session->setFlash('', [
                        'type' => 'danger',
                        'duration' => 5000,
                        'icon' => 'fa fa-check',
                        'message' => 'You have not selected allocation point',
                        'positonY' => 'top',
                        'positonX' => 'right',
                    ]);
                    return $this->redirect(['index']);
                }


            }

            return $this->redirect(['index']);
        });


    }

}
