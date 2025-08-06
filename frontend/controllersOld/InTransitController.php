<?php

namespace frontend\controllers;

use frontend\models\AwaitingReceive;
use frontend\models\AwaitingReceiveReport;
use frontend\models\BorderPort;
use frontend\models\BorderPortUser;
use frontend\models\Devices;
use frontend\models\ReceivedDevices;
use frontend\models\StockDevices;
use Yii;
use frontend\models\InTransit;
use frontend\models\InTransitSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InTransitController implements the CRUD actions for InTransit model.
 */
class InTransitController extends Controller
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
     * Lists all InTransit models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InTransitSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSearch()
    {
        $searchModel = new InTransitSearch();
        $dataProvider = $searchModel->searchClean(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPending()
    {
        $searchModel = new InTransitSearch();
        $dataProvider = $searchModel->searchPending(Yii::$app->request->queryParams);

        return $this->render('pending_slaves', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single InTransit model.
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
     * Creates a new InTransit model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new InTransit();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing InTransit model.
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
     * Deletes an existing InTransit model.
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
     * Finds the InTransit model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InTransit the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InTransit::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionConfirm(){

        if(Yii::$app->user->can('confirmReceived')){

            $selection=(array)Yii::$app->request->post('selection');

            foreach($selection as $id) {

                if ($selection != ''){
                    $e = InTransit::findOne((int)$id);

                    foreach ($selection as $key => $value) {
                        $e = InTransit::find()->where(['id'=>$value])->one();


                        AwaitingReceive::updateAll([
                            'received_from' =>  BorderPort::Border,
                            'border_port' => $e['border'],
                            'sale_id' => $e['sale_id'],
                            'received_from_staff' =>  $e['created_by'],
                            'received_status' =>  StockDevices::available,
                            'received_at' => date('Y-m-d H:i:s'),
                            'received_by' => Yii::$app->user->identity->id,
                            'view_status'=>Devices::awaiting_receive,
                            'remark'=>'',
                        ],['serial_no'=>$e['serial_no']]);

                        InTransit::updateAll(['view_status'=>Devices::awaiting_receive],['serial_no'=>$e['serial_no']]);

                        $awaiting = new AwaitingReceiveReport();
                        $awaiting->serial_no = $e['serial_no'];
                        $awaiting->received_from = BorderPort::Border;
                        $awaiting->border_port = $e['border'];
                        $awaiting->received_from_staff = $e['created_by'];
                        $awaiting->received_status = StockDevices::available;
                        $awaiting->received_by = Yii::$app->user->identity->id;
                        $awaiting->received_at = date('Y-m-d H:i:s');
                        $awaiting->save();
                    }

                    Yii::$app->session->setFlash('', [
                        'type' => 'success',
                        'duration' => 5000,
                        'icon' => 'fa fa-check',
                        'message' => 'Total device '.count($selection).' have been  confirmed',
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
                        'message' => 'No devices selected',
                        'positonY' => 'top',
                        'positonX' => 'right',
                    ]);

                    return $this->redirect(['index']);
                }


            }

            return $this->redirect(['index']);

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

    public function actionConfirm1(){
        Yii::$app->db->transaction(function(){
            if(Yii::$app->user->can('confirmReceived')){

                $selection=(array)Yii::$app->request->post('selection');

                foreach($selection as $id) {

                    if ($selection != ''){
                        $e = InTransit::findOne((int)$id);

                        foreach ($selection as $key => $value) {
                            $e = InTransit::find()->where(['id'=>$value])->one();


                            AwaitingReceive::updateAll([
                                'received_from' =>  BorderPort::Border,
                                'border_port' => $e['border'],
                                'received_from_staff' =>  $e['created_by'],
                                'received_status' =>  StockDevices::available,
                                'received_at' => date('Y-m-d H:i:s'),
                                'received_by' => Yii::$app->user->identity->id,
                                'view_status'=>Devices::awaiting_receive,
                                'remark'=>'',
                            ],['serial_no'=>$e['serial_no']]);

                            InTransit::updateAll(['view_status'=>Devices::awaiting_receive],['serial_no'=>$e['serial_no']]);

                            $awaiting = new AwaitingReceiveReport();
                            $awaiting->serial_no = $e['serial_no'];
                            $awaiting->received_from = BorderPort::Border;
                            $awaiting->border_port = $e['border'];
                            $awaiting->received_from_staff = $e['created_by'];
                            $awaiting->received_status = StockDevices::available;
                            $awaiting->received_by = Yii::$app->user->identity->id;
                            $awaiting->received_at = date('Y-m-d H:i:s');
                            $awaiting->save();


                            $slaves = InTransit::find()->where(['sale_id'=>$e['sale_id']])->all();
                            foreach($slaves as $slave) {
                                $e = InTransit::find()->where(['id'=>$slave['id']])->one();


                                AwaitingReceive::updateAll([
                                    'received_from' =>  BorderPort::Border,
                                    'border_port' => $e['border'],
                                    'received_from_staff' =>  $e['created_by'],
                                    'received_status' =>  StockDevices::available,
                                    'received_at' => date('Y-m-d H:i:s'),
                                    'received_by' => Yii::$app->user->identity->id,
                                    'view_status'=>Devices::awaiting_receive,
                                    'remark'=>'',
                                ],['serial_no'=>$e['serial_no']]);

                                InTransit::updateAll(['view_status'=>Devices::awaiting_receive],['serial_no'=>$e['serial_no']]);

                                $awaiting = new AwaitingReceiveReport();
                                $awaiting->serial_no = $e['serial_no'];
                                $awaiting->received_from = BorderPort::Border;
                                $awaiting->border_port = $e['border'];
                                $awaiting->received_from_staff = $e['created_by'];
                                $awaiting->received_status = StockDevices::available;
                                $awaiting->received_by = Yii::$app->user->identity->id;
                                $awaiting->received_at = date('Y-m-d H:i:s');
                                $awaiting->save();
                            }

                        }

                        Yii::$app->session->setFlash('', [
                            'type' => 'success',
                            'duration' => 5000,
                            'icon' => 'fa fa-check',
                            'message' => 'Total device '.count($selection).' have been  confirmed',
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
                            'message' => 'No devices selected',
                            'positonY' => 'top',
                            'positonX' => 'right',
                        ]);

                        return $this->redirect(['index']);
                    }


                }

                return $this->redirect(['index']);

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
        });


    }

}
