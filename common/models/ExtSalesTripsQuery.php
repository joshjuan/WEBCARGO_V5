<?php

namespace common\models;

use frontend\models\Devices;
use Yii;

/**
 * This is the ActiveQuery class for [[ExtSalesTrips]].
 *
 * @see ExtSalesTrips
 */
class ExtSalesTripsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/
    public static function createTripInTransaction($customer, $master, $slaves, $gateId, $borderId, $tripNo,
                                                   $chassisNo, $vehicleType, $vehicleNo, $trailerNo, $merchantNo,
                                                   $agent, $driver, $cargoTypeId, $cargoNo, $containerNo, $startDate, $endDate, $branch, $userId)
    {
        $price = ExtDevicePrices::findOne(['created_at' => date('Y-m-d')]);
        $time = date('Y-m-d H:i:s');

        $model = new ExtSalesTrips();
        $model->customer = json_encode($customer);
        $model->master = $master;
        $model->slaves = json_encode($slaves);
        $model->gate_id = $gateId;
        $model->border_id = $borderId;
        $model->trip_no = $tripNo;
        $model->vehicle_no = $vehicleNo;
        $model->trailer_no = $trailerNo;
        $model->merchant_no = $merchantNo;
        $model->receipt_no = References::generateReceiptNo();
        $model->agent = json_encode($agent);
        $model->driver = json_encode($driver);
        $model->cargo_type_id = $cargoTypeId;
        $model->cargo_no = $cargoNo;
        $model->container_no = $containerNo;
        $model->vehicle_type = $vehicleType;
        $model->chassis_no = $chassisNo;
        $model->device_price = ($price) ? $price->value : "";
        $model->start_date = $startDate;
        $model->end_date = $endDate;
        $model->created_at = $time;
        $model->created_by = $userId;
        $model->branch = $branch;

        if (!$model->save(false)) {
            return false;
        }

        $movementID = strtoupper(Yii::$app->security->generateRandomString());

        $deviceData = Devices::find()
            ->where(['serial_no' => $model->serial_no])
            ->andWhere(['view_status' => Devices::released])
            ->one();

        if ($deviceData) {
            Devices::updateAll([
                'view_status' => Devices::on_road,
                'tzl' => $model->tzl,
                'border_port' => $model->border_id,
                'gate_number' => $model->gate_number,
                'sales_person' => $model->sales_person,
                'vehicle_no' => $model->vehicle_number,
                'container_number' => $model->container_number,
                'created_by' => $model->sales_person,
                'sale_id' => $model->id,
                'created_at' => $model->created_at,
                'movement_unique_id' => $movementID,
            ], ['serial_no' => $model->serial_no]);

            Yii::$app->db->createCommand()
                ->upsert(
                    'devices_reports',
                    [
                        'vehicle_no' => $model->vehicle_number,
                        'container_number' => $model->container_number,
                        'serial_no' => $deviceData['serial_no'],
                        'border_port' => $model->border_id,
                        'gate_number' => $model->gate_number,
                        'received_from' => Devices::released,
                        'received_to' => Devices::on_road,
                        'type' => $deviceData->type,
                        'device_category' => $deviceData->device_category,
                        'branch' => $deviceData->branch,
                        'created_by' => $model->sales_person,
                        'created_at' => $model->created_at,
                        'movement_unique_id' => $movementID,
                    ],
                    false
                )
                ->execute();

        }

        /*Save Slaves*/
        if (!empty($slaves)) {
            foreach ($slaves as $key => $slave) {
                $ID = $slave;
                $new = new ExtSalesTripsSlaves();
                $new->sales_id = $model->id;
                $new->serial_no = $ID;
                $new->created_at = $time;
                $new->status = 1;
                if (!$new->save(false)) {
                    return false;
                }

                if (DevicesRotation::updateAll([
                        'border_port' => $model->border_id,
                        'sale_gate' => $model->gate_id,
                        'received_from' => Devices::port,
                        'view_status' => Devices::intransit,
                        'vehicle_no' => $model->vehicle_no,
                        'container_number' => $model->container_no,
                        'received_by' => $model->created_by,
                        'sales_person' => $model->created_by,
                        'tzl' => $model->trip_no,
                        'sale_id' => $model->id,
                        'received_at' => $time,
                    ], ['serial_no' => $ID]) === false) {
                    return false;
                }

                $lost = new LogsReport();
                $lost->serial_no = $ID;
                $lost->status = Devices::port;
                $lost->location_from = Devices::port;
                $lost->location_to = Devices::intransit;
                $lost->branch = $branch;
                $lost->sale_id = $model->id;
                $lost->border_port = $model->border_id;
                $lost->gate_number = $model->gate_id;
                $lost->created_by = $model->created_by;
                $lost->created_at = date('Y-m-d H:i:s');
                if (!$lost->save(false)) {
                    return false;
                }
            }
        }

        $lost = new LogsReport();
        $lost->serial_no = $model->master;
        $lost->status = Devices::port;
        $lost->location_from = Devices::port;
        $lost->location_to = Devices::intransit;
        $lost->branch = $branch;
        $lost->border_port = $model->border_id;
        $lost->gate_number = $model->gate_id;
        $lost->sale_id = $model->id;
        $lost->created_by = $model->created_by;
        $lost->created_at = date('Y-m-d H:i:s');
        if (!$lost->save(false)) {
            return false;
        }

        if (DevicesRotation::updateAll([
                'border_port' => $model->border_id,
                'sale_gate' => $model->gate_id,
                'received_from' => Devices::port,
                'view_status' => Devices::intransit,
                'vehicle_no' => $model->vehicle_no,
                'container_number' => $model->container_no,
                'received_by' => $model->created_by,
                'sales_person' => $model->created_by,
                'sales_order_status' => null,
                'tzl' => $model->trip_no,
                'sale_id' => $model->id,
                'received_at' => $time,
            ], ['serial_no' => $model->master]) === false) {
            return false;
        }

        $gate = BorderPort::findOne($model->gate_id);
        $border = BorderPort::findOne($model->border_id);

        $cargoType = $model->cargo_type_id == "1" ? "DRY CARGO" :
            ($model->cargo_type_id == "2" ? 'IT' :
                ($model->cargo_type_id == "3" ? 'WET CARGO' : ''));

        $slavesArray = json_decode($model->slaves);
        $slavesString = (is_array($slavesArray)) ? implode(', ', $slavesArray) : "";

        return [
            "customerName" => json_decode($model->customer)->customerName,
            "master" => $model->master,
            "slaves" => $slavesString,
            "gateName" => ($gate) ? $gate->name : "",
            "borderName" => ($border) ? $border->name : "",
            "tripNo" => $model->trip_no,
            "vehicleNo" => $model->vehicle_no,
            "trailerNo" => $model->trailer_no,
            "merchantNo" => $model->merchant_no,
            "receiptNo" => $model->receipt_no,
            "agentName" => json_decode($model->agent)->agentName,
            "agentPhoneNo" => json_decode($model->agent)->agentPhoneNo,
            "driverName" => json_decode($model->driver)->driverName,
            "driverMobile" => json_decode($model->driver)->driverPhoneNo,
            "driverLicense" => json_decode($model->driver)->driverLicenseNo,
            "driverPassport" => json_decode($model->driver)->passportNo,
            "cargoType" => $cargoType,
            "cargoNo" => $model->cargo_no,
            "containerNo" => $model->container_no,
            "startDate" => $model->start_date,
            "endDate" => $model->end_date,
            "salesTripId" => $model->id
        ];
    }

    public static function checkIfExist($tripNo)
    {
        return ExtSalesTrips::find()
            ->where(['trip_no' => $tripNo])
            ->andFilterWhere(['trip_status' => ExtSalesTrips::NEW_TRIP_SALE])
            ->one();
    }

    /**
     * {@inheritdoc}
     * @return ExtSalesTrips[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ExtSalesTrips|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
