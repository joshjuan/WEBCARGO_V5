<?php
namespace backend\controllers;

use backend\models\AwaitingReceive;
use backend\models\AwaitingReceiveReport;
use backend\models\Devices;
use backend\models\ReleasedDevices;
use backend\models\StockDevices;
use frontend\models\User;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin1()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
           $this->actionReturn();
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }



    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

          $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if (\Yii::$app->user->identity->branch == '1'
                && \Yii::$app->user->identity->user_type != User::PARTNER
                && \Yii::$app->user->identity->role == 'Admin'
            )  {

                return $this->goBack();
            }
            else {
                Yii::$app->user->logout();

                Yii::$app->session->setFlash('failure', "You do not have permission to login in Admin Panel");

                return $this->render('login', [
                    'model' => $model,
                ]);

                //redirect again page to login form.
                return $this->redirect(['site/login1']);
            }


        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }




    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
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

    private  function actionReturn1(){

        $released=ReleasedDevices::find()
            // ->where(['<', 'date(released_date)',date('Y-m-d')])
            ->where(['view_status'=>Devices::released_devices])
            ->all();


        foreach ($released as $release) {

            $data = ReleasedDevices::find()->where(['id' =>$release->id])
                ->andWhere(['view_status'=>Devices::released_devices])->one();
            $now=date('Y-m-d H:i:s');

            $diff =strtotime($now)-strtotime($data['released_date']);
            $hours= floor($diff/(60*60));

            if ($hours >=25){
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
            else{
                $result = ['status' => ' not device found which exceed 24 hours'];

                return Json::encode($result);
            }


        }

    }



}
