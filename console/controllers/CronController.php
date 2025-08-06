<?php

namespace console\controllers;


use backend\models\AwaitingReceive;
use backend\models\AwaitingReceiveReport;
use backend\models\Devices;
use backend\models\ReceivedDevices;
use backend\models\ReleasedDevices;
use backend\models\SalesTrips;
use backend\models\StockDevices;
use DateTime;
use fedemotta\cronjob\models\CronJob;
use yii\console\Controller;
use yii\db\Expression;
use yii\helpers\ArrayHelper;


/**
 * ServicesController implements the CRUD actions for SmsController model.
 */
class CronController extends Controller
{

    /**
     * Run SomeModel::some_method for a period of time
     * @param string $from
     * @param string $to
     * @return int exit code
     */
    public function actionInit($from, $to){
        $dates  = CronJob::getDateRange($from, $to);
        $command = CronJob::run($this->id, $this->action->id, 0, CronJob::countDateRange($dates));
        if ($command === false){
            return Controller::EXIT_CODE_ERROR;
        }else{

            $command->finish();
            return Controller::EXIT_CODE_NORMAL;
        }
    }
    /**
     * Run SomeModel::some_method for today only as the default action
     * @return int exit code
     */
    public function actionIndex(){
        // return $this->actionInit(date("Y-m-d"), date("Y-m-d"));
        echo 'hello';
    }
    /**
     * Run SomeModel::some_method for yesterday
     * @return int exit code
     */
    public function actionYesterday(){

        $currentMonth = date('F');
        $leo= Date('m', strtotime($currentMonth . " last month"));

        $previousOne = sprintf("%01d",$leo);
        echo $leo;
    }


    public  function actionReturn(){

        $date =date('Y-m-d', strtotime('-2 days'));

        $released= \frontend\models\Devices::find()
            ->where(['<','date(released_date)',$date])
            ->andWhere(['view_status'=>Devices::released_devices])
            ->all();


        foreach ($released as $release) {

            $data = Devices::find()
                ->where(['id' =>$release->id])
                ->andWhere(['view_status'=>Devices::released_devices])
                ->one();

            Devices::updateAll([
                'received_from' => 2,
                'border_port' => $data['border_port'],
                'received_from_staff' => $data['released_to'],
                'received_status' => 1,
                'remark' => 'AUTOMATIC RETURN TO OFFICE AFTER 24 HOURS',
                'received_at' => date('Y-m-d H:i:s'),
                'received_by' =>  $data['released_to'],
                'view_status'=>Devices::awaiting_receive,
            ],['serial_no'=>$data['serial_no']]);

            $report = new \frontend\models\AwaitingReceiveReport();
            $report->serial_no = $data['serial_no'];
            $report->received_from = 2;
            $report->border_port = $data['border_port'];
            $report->branch = $data['branch'];
            $report->received_from_staff = $data['released_to'];
            $report->received_at = date('Y-m-d H:i:s');
            $report->received_status = 1;
            $report->remark = 'AUTOMATIC RETURN TO OFFICE AFTER 24 HOURS';
            $report->received_by = $data['released_to'];

            $DATA=$data['serial_no'];
            echo "SUCCESS $DATA ";

        }

    }


    public function actionReturnOld(){

        $released=Devices::find()
            ->where(['<', 'date(released_date)',date('Y-m-d')])
            ->andWhere(['view_status'=>Devices::released_devices])
            ->all();

        foreach ($released as $release) {

                $data = ReleasedDevices::find()
                    ->where(['id' =>$release->id])
                    ->andWhere(['view_status'=>Devices::released_devices])
                    ->one();

               Devices::updateAll([
                    'received_from' => 2,
                  //  'border_port' => $data['sales_point'],
                  //  'received_from_staff' => $data['released_to'],
                    'received_status' => 1,
                    'remark' => 'AUTOMATIC RETURN TO OFFICE AFTER 24 HOURS',
                    'received_at' => date('Y-m-d H:i:s'),
                    //'received_by' =>  $data['released_to'],
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

               ReleasedDevices::updateAll(['view_status'=>Devices::awaiting_receive],['serial_no'=>$data['serial_no']]);

        }

    }

}
