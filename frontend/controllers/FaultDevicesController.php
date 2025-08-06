<?php

namespace frontend\controllers;

use backend\models\ReceivedDevices;
use backend\models\StockDevices;
use backend\models\StockDevicesReport;
use Yii;
use backend\models\FaultDevices;
use backend\models\FaultDevicesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FaultDevicesController implements the CRUD actions for FaultDevices model.
 */
class FaultDevicesController extends Controller
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
     * Lists all FaultDevices models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FaultDevicesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSearch()
    {
        $searchModel = new FaultDevicesSearch();
        $dataProvider = $searchModel->searchClean(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single FaultDevices model.
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
     * Creates a new FaultDevices model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FaultDevices();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing FaultDevices model.
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
     * Deletes an existing FaultDevices model.
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
     * Finds the FaultDevices model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FaultDevices the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FaultDevices::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionAvailable()
    {
        Yii::$app->db->transaction(function () {
            $action = Yii::$app->request->post('action');

            $selection = (array)Yii::$app->request->post('selection');
            //  print_r($action);
            // exit;

            foreach ($selection as $id) {
                //  $e = StockDevices::findOne((int)$id);

                if ($action != '') {

                    foreach ($selection as $key => $value) {
                        $e = FaultDevices::find()->where(['id' => $selection])->one();


                        $stock = new StockDevices();
                        $stock->serial_no = $e['serial_no'];
                        $stock->status = StockDevices::available;
                        $stock->created_by = Yii::$app->user->identity->id;
                        $stock->created_at = date('Y-m-d H:m');
                        $stock->save();

                        $stock = new StockDevicesReport();
                        $stock->serial_no = $e['serial_no'];
                        $stock->status = StockDevices::available;
                        $stock->created_by = Yii::$app->user->identity->id;
                        $stock->created_at = date('Y-m-d H:m');
                        $stock->save();

                        FaultDevices::deleteAll(['id' => $value]);

                    }


                    Yii::$app->session->setFlash('', [
                        'type' => 'success',
                        'duration' => 5000,
                        'icon' => 'fa fa-check',
                        'message' => 'Total device ' . count($selection) . ' have been  successfully allocated as available',
                        'positonY' => 'top',
                        'positonX' => 'right',
                    ]);

                    return $this->redirect(['index']);

                } else {
                    Yii::$app->session->setFlash('', [
                        'type' => 'danger',
                        'duration' => 5000,
                        'icon' => 'fa fa-check',
                        'message' => 'You have not selected Sales Person ',
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
