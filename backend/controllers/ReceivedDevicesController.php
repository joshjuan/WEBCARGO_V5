<?php

namespace backend\controllers;

use backend\models\Border;
use backend\models\BorderPort;
use backend\models\BorderPortUser;
use backend\models\BorderUser;
use backend\models\Devices;
use backend\models\FaultDevices;
use backend\models\FaultDevicesReport;
use backend\models\ReceivedDevicesReport;
use backend\models\StockDevices;
use backend\models\StockDevicesReport;
use backend\models\User;
use common\models\LoginForm;
use Yii;
use backend\models\ReceivedDevices;
use backend\models\ReceivedDevicesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ReceivedDevicesController implements the CRUD actions for ReceivedDevices model.
 */
class ReceivedDevicesController extends Controller
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
     * Lists all ReceivedDevices models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            $searchModel = new ReceivedDevicesSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
        else{
            $model = new LoginForm();
            return $this->redirect(['site/login',
                'model' => $model,
            ]);
    }

    }

    public function actionSearch()
    {
        if (!Yii::$app->user->isGuest) {
            $searchModel = new ReceivedDevicesSearch();
            $dataProvider = $searchModel->searchClean(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
        else{
                $model = new LoginForm();
                return $this->redirect(['site/login',
                    'model' => $model,
                ]);
            }

        }

    /**
     * Displays a single ReceivedDevices model.
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
     * Creates a new ReceivedDevices model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ReceivedDevices();

        if(Yii::$app->user->can('addReceivedDevices')){

            if ($model->load(Yii::$app->request->post())) {

                // $model->received_at=date('Y-m-d H:i:s');
                //$model->received_by=Yii::$app->user->identity->id;

                $serial = $model->serial_no;
                //  $status = $model->received_status;
                $received_from = $model->received_from;
                $border = $model->border_port;
                $staff = $model->received_from_staff;
                $remark = $model->remark;
                $received_by = $model->received_by = Yii::$app->user->identity->id;
                $received_at = $model->received_at = date('Y-m-d H:i:s');



                $line_data = preg_split("/\\r\\n|\\r|\\n/", $serial);
                foreach ($line_data as $key => $value) {

                    $model = new ReceivedDevices();
                    $model->serial_no = $value;
                    $model->received_at = $received_at;
                    $model->received_by = $received_by;
                    $model->received_from = $received_from;
                    $model->border_port = $border;
                    $model->received_from_staff = $staff;
                    $model->remark = $remark;
                    //  $model->received_status = $status;
                    $model->save();

                    $modelReport = new ReceivedDevicesReport();
                    $modelReport->serial_no = $value;
                    $modelReport->received_at = $received_at;
                    $modelReport->received_by = $received_by;
                    $modelReport->received_from = $received_from;
                    $modelReport->border_port = $border;
                    $modelReport->received_from_staff = $staff;
                    $modelReport->remark = $remark;
                    $modelReport->save();

                }

                Yii::$app->session->setFlash('', [
                    'type' => 'success',
                    'duration' => 5000,
                    'icon' => 'fa fa-check',
                    'message' => 'Total device '.count($line_data).' have been  successfully received',
                    'positonY' => 'top',
                    'positonX' => 'right',
                ]);

                return $this->redirect(['index']);
            }

            return $this->render('create', [
                'model' => $model,
            ]);

        }else{
            Yii::$app->session->setFlash('', [
                'type' => 'danger',
                'duration' => 5000,
                'icon' => 'fa fa-warning',
                'message' => 'You dont have a permission',
                'positonY' => 'top',
                'positonX' => 'right',
            ]);

            return $this->redirect(['index']);
        }

    }

    /**
     * Updates an existing ReceivedDevices model.
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
     * Deletes an existing ReceivedDevices model.
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
     * Finds the ReceivedDevices model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ReceivedDevices the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ReceivedDevices::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionBorderPort() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $cat_id = $parents[0];
                $out = self::getBorderPortList($cat_id);

                return ['output'=>$out, 'selected'=>''];
            }
        }
        return ['output'=>'', 'selected'=>''];
    }


    public function actionUserLocation() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);

            //  $list = Border::find()->where(['id' => $id])->one();
            // $list = Border::find()->where(['id' => $id])->one();
            $list = BorderPortUser::find()->where(['border_port' => $id])->asArray()->all();
            //   $list = User::find()->where(['id'=>$id])->asArray()->all();
            $selected  = null;
            if ($id != null && count($list) > 0) {
                $selected = '';
                foreach ($list as $i => $account) {
                    $out[] = ['id' => $account['id'], 'name' => $account['name']];
                    if ($i == 0) {
                        $selected = $account['id'];
                    }
                }
                // Shows how you can preselect a value
                return ['output' => $out, 'selected' => $selected];
            }
        }
        return ['output' => '', 'selected' => ''];
    }



    public function getBorderPortList($id)
    {
        $data = BorderPort::find()->where(['location'=>$id])->select(['id','name'])->asArray()->all();
        $value = (count($data) == 0) ? ['' => ''] : $data;
        return $value;
    }



    public function actionStore(){
        Yii::$app->db->transaction(function(){
            $action=Yii::$app->request->post('action');

            $selection=(array)Yii::$app->request->post('selection');

            foreach($selection as $id) {
                //  $e = ReceivedDevices::findOne((int)$id);


                if ($action == 1){
                    foreach ($selection as $key => $value) {
                        $e = ReceivedDevices::find()->where(['id'=>$value])->one();

                        StockDevices::updateAll(['status'=>StockDevices::available,
                            'created_by'=>Yii::$app->user->identity->id,
                            'view_status'=>Devices::stock_devices,
                            'location_from'=>$e['border_port'],
                            'created_at' => date('Y-m-d H:i:s')],['serial_no'=>$e['serial_no']]);

                        ReceivedDevices::updateAll(['view_status'=>Devices::stock_devices],['serial_no'=>$e['serial_no']]);


                        Yii::$app->db->createCommand()
                            ->upsert(
                                'stock_devices_report',
                                [
                                    'serial_no' => $e['serial_no'],
                                    'status' => StockDevices::available,
                                    'created_by' => Yii::$app->user->identity->id,
                                    'location_from' => $e['border_port'],
                                    'created_at' => date('Y-m-d H:i:s'),
                                ],
                                false
                            )
                            ->execute();


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

                elseif ($action == 2){
                    foreach ($selection as $key => $value) {
                        $e = ReceivedDevices::find()->where(['id'=>$value])->one();

                        StockDevices::updateAll(['status'=>StockDevices::not_deactivated,
                            'created_by'=>Yii::$app->user->identity->id,
                            'location_from'=>$e['border_port'],
                            'view_status'=>Devices::stock_devices,
                            'created_at' => date('Y-m-d H:i:s')],['serial_no'=>$e['serial_no']]);

                        ReceivedDevices::updateAll(['view_status'=>Devices::stock_devices],['serial_no'=>$e['serial_no']]);

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
                elseif ($action ==3){

                    foreach ($selection as $key => $value) {
                        $e = ReceivedDevices::find()->where(['id'=>$value])->one();

                        FaultDevices::updateAll(['status'=>StockDevices::not_deactivated,
                            'created_by'=>Yii::$app->user->identity->id,
                            'location_from'=>$e['border_port'],
                            'view_status'=>Devices::fault_devices,
                            'created_at' => date('Y-m-d H:i:s')],['serial_no'=>$e['serial_no']]);

                        ReceivedDevices::updateAll(['view_status'=>Devices::fault_devices],['serial_no'=>$e['serial_no']]);

                        $fault = new FaultDevicesReport();
                        $fault->serial_no = $e['serial_no'];
                        $fault->status = FaultDevices::fault_device;
                        $fault->created_by = Yii::$app->user->identity->id;
                        $fault->created_at = date('Y-m-d H:i:s');
                        $fault->save();

                    }

                    Yii::$app->session->setFlash('', [
                        'type' => 'success',
                        'duration' => 5000,
                        'icon' => 'fa fa-check',
                        'message' => 'Total device '.count($selection).' have been  successfully sent for maintenance',
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
