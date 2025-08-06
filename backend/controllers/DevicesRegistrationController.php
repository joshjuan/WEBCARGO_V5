<?php

namespace backend\controllers;

use backend\models\Devices;
use backend\models\DevicesRegistration;
use backend\models\DevicesRegistrationSearch;
use common\models\LoginForm;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DevicesRegistrationController implements the CRUD actions for DevicesRegistration model.
 */
class DevicesRegistrationController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all DevicesRegistration models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new DevicesRegistrationSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DevicesRegistration model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new DevicesRegistration model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */

    public function actionCreate()
    {

        if (!Yii::$app->user->isGuest) {
            $model = new DevicesRegistration();

            if (Yii::$app->user->can('registerNewDevices')) {

                if ($model->load(Yii::$app->request->post())) {
                    try {
                        $createdBY = $model->created_by = Yii::$app->user->identity->id;
                        $serial = $model->serial_no;
                        $status = Devices::registration;
                        $branch = $model->branch;
                        $type = $model->type;
                        $timeCreated = $model->created_at = date('Y-m-d H:i:s');
                       // $received_from = $model->received_from;
                        $received_from = 3;
                      //  $border = $model->border_port;
                        $border = 27;
                        $staff = $model->received_from_staff;
                        $remark = $model->remark;
                        $partner = $model->partiner;
                        $category = $model->device_category;
                        $line_data = preg_split("/\\r\\n|\\r|\\n/", $serial);
                        foreach ($line_data as $key => $value) {

                            $checkSerial = DevicesRegistration::findOne(['serial_no' => $value]);
                            if (empty($checkSerial)) {

                                $model = new DevicesRegistration();
                                $model->serial_no = $value;
                                $model->received_from = $received_from;
                                $model->device_from = $received_from;
                                $model->device_category = $category;
                                $model->partiner = $partner;
                                $model->border_port = $border;
                              //  $model->stock_status = $border;
                               // $model->created_at = $timeCreated;
                               // $model->created_by = $createdBY;
                                $model->view_status = $status;
                                $model->branch = $branch;
                                $model->type = $type;
                                $model->remark = $remark;
                                $model->registration_by = $createdBY;
                                $model->registration_date=$timeCreated;
                                $model->save();


                                $modelNew = new Devices();
                                $modelNew->serial_no = $value;
                                $modelNew->received_from = $received_from;
                                $modelNew->device_from = $received_from;
                                $modelNew->device_category = $category;
                                $modelNew->partiner = $partner;
                                $modelNew->border_port = $border;
                               // $modelNew->stock_status = $border;
                               // $modelNew->created_at = $timeCreated;
                                //$modelNew->created_by = $createdBY;
                                $modelNew->view_status = $status;
                                $modelNew->branch = $branch;
                                $modelNew->type = $type;
                                $modelNew->remark = $remark;
                                $modelNew->registration_by = $createdBY;
                                $modelNew->registration_date=$timeCreated;
                                $modelNew->save();

                                $data = count($line_data);
                                $error="success";
                                $message="have been  registered successfully";
                            }

                            else{
                                $data = count($line_data);
                                $error="danger";
                                $message="device registration failed";

                            }

                        }

                        Yii::$app->session->setFlash('', [
                            'type' => "$error",
                            'duration' => 5000,
                            'icon' => 'fa fa-check',
                            'message' => "Total devices $data ,$message",
                            'positonY' => 'top',
                            'positonX' => 'right',
                        ]);
                        return $this->redirect(['devices/index']);

                    } catch (\Exception $e) {
                        Yii::$app->session->setFlash('', [
                            'type' => 'danger',
                            'duration' => 5000,
                            'icon' => 'fa fa-check',
                            'message' => "Fail, there is an error occurred $e",
                            'positonY' => 'top',
                            'positonX' => 'right',
                        ]);
                        return $this->redirect(['devices/index']);
                    }
                }

                return $this->render('create', [
                    'model' => $model,
                ]);

            } else {
                Yii::$app->session->setFlash('', [
                    'type' => 'danger',
                    'duration' => 5000,
                    'icon' => 'fa fa-warning',
                    'message' => 'You dont have a permission',
                    'positonY' => 'top',
                    'positonX' => 'right',
                ]);

                return $this->redirect(['/']);
            }
        } else {
            $model = new LoginForm();
            return $this->redirect(['site/login',
                'model' => $model,
            ]);
        }

    }



    public function actionCreateOLD()
    {
        $model = new DevicesRegistration();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing DevicesRegistration model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing DevicesRegistration model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the DevicesRegistration model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return DevicesRegistration the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DevicesRegistration::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
