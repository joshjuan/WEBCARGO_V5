<?php

namespace backend\controllers;

use backend\models\Devices;
use backend\models\FaultDevices;
use backend\models\ReceivedDevices;
use backend\models\ReleasedDevices;
use backend\models\ReleasedDevicesReport;
use backend\models\StockDevicesReport;
use common\models\LoginForm;
use Yii;
use backend\models\StockDevices;
use backend\models\StockDevicesSearch;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StockDevicesController implements the CRUD actions for StockDevices model.
 */
class StockDevicesController extends Controller
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
     * Lists all StockDevices models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
        $searchModel = new StockDevicesSearch();
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

    public function actionAvailable()
    {
        $searchModel = new StockDevicesSearch();
        $dataProvider = $searchModel->searchAvailable(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionActive()
    {
        $searchModel = new StockDevicesSearch();
        $dataProvider = $searchModel->searchActive(Yii::$app->request->queryParams);

        return $this->render('not_deactivated', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionClean()
    {
        $searchModel = new StockDevicesSearch();
        $dataProvider = $searchModel->searchClean(Yii::$app->request->queryParams);

        return $this->render('not_deactivated', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDeactivate(){
        Yii::$app->db->transaction(function(){
            if(Yii::$app->user->can('deactivateDevices')){

                $selection=(array)Yii::$app->request->post('selection');

                foreach($selection as $id) {

                    if ($selection != ''){
                        $e = StockDevices::findOne((int)$id);

                        foreach ($selection as $key => $value) {
                            StockDevices::updateAll(['status'=>StockDevices::available],['id' => $value]);

                            $stock = new StockDevicesReport();
                            $stock->serial_no = $e['serial_no'];
                            $stock->location_from = $e['location_from'];
                            $stock->status = StockDevices::available;
                            $stock->created_by = Yii::$app->user->identity->id;
                            $stock->created_at = date('Y-m-d H:i:s');
                            $stock->save();

                        }

                        Yii::$app->session->setFlash('', [
                            'type' => 'success',
                            'duration' => 5000,
                            'icon' => 'fa fa-check',
                            'message' => 'Total device '.count($selection).' have been  confirmed',
                            'positonY' => 'top',
                            'positonX' => 'right',
                        ]);

                        return $this->redirect(['active']);
                    }
                    else{
                        Yii::$app->session->setFlash('', [
                            'type' => 'danger',
                            'duration' => 5000,
                            'icon' => 'fa fa-check',
                            'message' => 'No devices selected',
                            'positonY' => 'top',
                            'positonX' => 'right',
                        ]);

                        return $this->redirect(['active']);
                    }


                }

                return $this->redirect(['active']);

            }else{
                Yii::$app->session->setFlash('', [
                    'type' => 'danger',
                    'duration' => 5000,
                    'icon' => 'fa fa-warning',
                    'message' => 'You dont have a permissions',
                    'positonY' => 'top',
                    'positonX' => 'right',
                ]);

                return $this->redirect(['active']);
            }

        } );

    }


    /**
     * Displays a single StockDevices model.
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

    public function actionCreate()
    {
        $model = new StockDevices();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing StockDevices model.
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
     * Deletes an existing StockDevices model.
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
     * Finds the StockDevices model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StockDevices the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StockDevices::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


      public function actionAllocated(){
          Yii::$app->db->transaction(function(){
              $action=Yii::$app->request->post('action');

              $selection=(array)Yii::$app->request->post('selection');
              $points=Yii::$app->request->post('points');

              //  print_r($action);
              // exit;

              foreach($selection as $id) {
                  //  $e = StockDevices::findOne((int)$id);

                  if ($action != '' && $points != ''){

                      foreach ($selection as $key => $value) {
                          $e = StockDevices::find()->where(['id'=>$value])->one();
                          $type = Devices::find()->where(['serial'=>$e['serial_no']])->one();

                          try {

                              ReleasedDevices::updateAll(['status'=>StockDevices::available,
                                  'released_by'=>Yii::$app->user->identity->id,
                                  'released_to'=>$action,
                                  'sales_point'=>$points,
                                  'view_status'=>Devices::released_devices,
                                  'transferred_from'=>null,
                                 // 'type'=>$type['type'],
                                  'transferred_to'=>null,
                                  'transferred_date'=>null,
                                  'transferred_by'=>null,
                                  'released_date' => date('Y-m-d H:i:s')],['serial_no'=>$e['serial_no']]);

                              StockDevices::updateAll(['view_status'=>Devices::released_devices],['serial_no'=>$e['serial_no']]);


                              $stock = new ReleasedDevicesReport();
                              $stock->serial_no = $e['serial_no'];
                              $stock->released_to = $action;
                              $stock->sales_point = $points;
                              $stock->status = StockDevices::available;
                              $stock->released_by = Yii::$app->user->identity->id;
                              $stock->released_date = date('Y-m-d H:i:s');
                              $stock->save();


                          } catch (\Exception $e) {
                              Yii::$app->session->setFlash('', [
                                  'type' => 'danger',
                                  'duration' => 5000,
                                  'icon' => 'fa fa-warning',
                                  'message' => 'Fail',
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
                          'message' => 'Total device '.count($selection).' have been  successfully released',
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
                          'message' => 'You have not selected Sales Person or Sales Point',
                          'positonY' => 'top',
                          'positonX' => 'right',
                      ]);
                      return $this->redirect(['index']);
                  }


              }

              return $this->redirect(['index']);
          });

    }


     public function actionAvailableReversed()
    {
        Yii::$app->db->transaction(function(){

            $selection = (array)Yii::$app->request->post('selection');

            foreach ($selection as $id) {

                foreach ($selection as $key => $value) {

                    $e = StockDevices::find()->where(['id' => $value])->one();


                    try {

                        ReceivedDevices::updateAll([
                            'received_from' => 0,
                            'border_port' => $e['location_from'],
                            'received_from_staff' => '',
                            'received_status' => 1,
                            'remark' => null,
                            'received_at' => date('Y-m-d H:i:s'),
                            'received_by' => Yii::$app->user->identity->id,
                            'view_status'=>Devices::received_devices,
                        ],['serial_no'=>$e['serial_no']]);

                        StockDevices::updateAll(['view_status'=>Devices::received_devices],['serial_no'=>$e['serial_no']]);

                        Yii::$app->db->createCommand()
                            ->upsert(
                                'received_devices_report',
                                [
                                    'serial_no' => $e['serial_no'],
                                    'received_from' => 0,
                                    'border_port' => $e['location_from'],
                                    'received_from_staff' => '',
                                    'received_status' => 1,
                                    'remark' => null,
                                    'received_at' => date('Y-m-d H:i:s'),
                                    'received_by' => Yii::$app->user->identity->id,
                                ],
                                false
                            )
                            ->execute();

                    } catch (Exception $e) {
                        Yii::$app->session->setFlash('', [
                            'type' => 'danger',
                            'duration' => 5000,
                            'icon' => 'fa fa-warning',
                            'message' => 'Fail',
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
                    'message' => 'Total device ' . count($selection) . ' have been  successfully reversed to received status',
                    'positonY' => 'top',
                    'positonX' => 'right',
                ]);

                return $this->redirect(['index']);

            }

            Yii::$app->session->setFlash('', [
                'type' => 'danger',
                'duration' => 5000,
                'icon' => 'fa fa-warning',
                'message' => 'You have to select at least one serial number',
                'positonY' => 'top',
                'positonX' => 'right',
            ]);


            return $this->redirect(['index']);

        });

    }
    
}
