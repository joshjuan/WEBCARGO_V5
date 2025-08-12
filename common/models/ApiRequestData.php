<?php

namespace common\models;

use frontend\models\BorderPort;
use frontend\models\Devices;
use Yii;

class ApiRequestData
{
    public static function getPaymentValidation($filter)
    {
        $userId = $filter['userId'];
        $merchantNo = $filter['merchantNo'];

        if (empty($userId) || empty($merchantNo)) {
            return References::failResponse('Bad Request', "userId or merchantNo can't be empty", 400);
        }


        $tagger = User::findOne($userId);

        $requestToBackend = [
            "userId"=>$tagger->id,
            "merchantNo"=>$merchantNo,
        ];

        $data = [
            'args' => [
                [
                    "order_transid" => $merchantNo,
                ]
            ]
        ];

        $hostURL = References::baseUrl . 'sale.order/call/validate_transaction_id';
        $result = References::sendApiRequest($hostURL, "PATCH", $data, []);
        $response = $result['response'];

        $statusCode = $result['httpStatusCode'];

        ExtApiAuditQuery::saveLogs($requestToBackend,$data, $hostURL, ExtApiAudit::GET_PAYMENT_VALIDATION, $statusCode, $response, $tagger->id);

        if ($statusCode == 200) {

            if ($response['data'] != 0 && $response['status'] == 'Success') {

                $response = $response['data'];
                $orderId = $result['response']['data']['oder_id'];

                $order = ExtSalesOrder::findOne(['order_id' => $orderId]);
                $customer = ($order != null) ? json_decode($order['customer']) : "";
                $device = ($order != null) ? json_decode($order['devices']) : "";

                return [
                    "data" => [
                        "payment" => $response,
                        "customer" => $customer,
                        "devices" => $device
                    ]
                ];
            }else{
                return References::failResponse("No Payment Found", $result['response']['message'], 400);
            }


        } else {
            return References::failResponse($response['error'], $response['error_descrip'], 400);
        }
    }

    public static function getCustomersFromTespa($filter)
    {

        $userId = $filter['userId'];
        if (empty($userId)) {
            return References::failResponse('Bad Request', "userId is required", 400);
        }

        $tagger = User::findOne($userId);

        $requestToBackend = [
            "userId"=>$tagger->id
        ];

        $array = [
            'args' => []
        ];

        $hostURL = References::baseUrl . 'sale.order/call/return_ects_customers';
        $result = References::sendApiRequest($hostURL, Constants::HTTP_METHOD_PATCH, $data, []);

        $statusCode = $result['httpStatusCode'];
        $response = $result['response'];

        ExtApiAuditQuery::saveLogs($requestToBackend, $array, $hostURL,ExtApiAudit::GET_CUSTOMER_ORDER, $statusCode, $response, $tagger->id);

        if ($response['status'] == 'Success') {

            $array1 = array([
                'id' => 0,
                'name' => "SELECT",
                'email' => "",
                'phone' => ""
            ]);

//            $response['data'] = array_merge($array1, $response['data']);
            return ["data" => $response['data']];

        } else {
            return References::failResponse($response['error'], $response['error_descrip'], 400);
        }

    }


    public static function getPaidSalesOrder($filter)
    {
        $userId = $filter['userId'];
        if (empty($userId)) {
            return References::failResponse('Bad Request', "userId is required", 400);
        }

        $tagger = User::findOne($userId);

        $requestToBackend = [
            "userId"=>$tagger->id
        ];


        $apiParams = [
            'args' => [[
                "employee_name" => $tagger->full_name,
                "company_code" => References::apiCompanyCode,
            ]]
        ];

        $hostURL = References::baseUrl . 'sale.order/call/return_employee_sale_orders';
        $result = References::sendApiRequest($hostURL, "PATCH", $apiParams, []);

        ExtApiAuditQuery::saveLogs(
            $requestToBackend,
            $apiParams,
            $hostURL,
            ExtApiAudit::GET_PAID_SALES_ORDER,
            $result['httpStatusCode'],
            $result['response'],
            $tagger->id
        );

        if ($result['response']['status'] !== 'Success') {
            $error = $result['response'];
            return References::failResponse($error['error'], $error['error_descrip'], 400);
        }

        $apiOrders = $result['response']['data'];
        $allOrderIds = array_column($apiOrders, 'id');

        $existingOrders = ExtSalesOrder::find()
            ->select(['order_id', 'payment_status', 'selcome_url'])
            ->where(['order_id' => $allOrderIds])
            ->andWhere(['created_by' => $tagger->id])
            ->indexBy('order_id')
            ->asArray()
            ->all();

        // Save new orders that don't exist in ext_sales_order
        $newOrders = array_filter($apiOrders, function($order) use ($existingOrders) {
            return !isset($existingOrders[$order['id']]);
        });

        foreach ($newOrders as $order) {
            $extOrder = new ExtSalesOrder();
            $extOrder->order_id = $order['id'];
            $extOrder->customer = json_encode([
                'customerId' => $order['customer_id'] ?? null,
                'customerName' => $order['customer_name'] ?? null
            ]);
            $extOrder->devices = json_encode($order['devices'] ?? []);
            $extOrder->products = json_encode($order['products'] ?? []);
            $extOrder->total_amount = $order['total_amount'] ?? 0;
            $extOrder->payment_status = $order['payment_status'] ?? 'un-paid';
            $extOrder->created_at = date('Y-m-d H:i:s');
            $extOrder->created_by = $tagger->id;

            // Store selcome_url if available from API
            if (isset($order['selcome_url'])) {
                $extOrder->selcome_url = $order['selcome_url'];
            }

            $extOrder->save();
        }

        // Filter paid orders from API response (payment_status = 'paid' OR has transaction_id)
        $paidOrders = array_filter($apiOrders, function($order) {
            return $order['payment_status'] === 'paid' ||
                (isset($order['transaction_id']) &&
                    $order['transaction_id'] !== null &&
                    $order['transaction_id'] !== 'N/A');
        });


        return [
            'data' => array_values($paidOrders)
        ];
    }

    public static function getUnPaidSalesOrder($filter)
    {
        $userId = $filter['userId'];
        if (empty($userId)) {
            return References::failResponse('Bad Request', "userId is required", 400);
        }


        $tagger = User::findOne($userId);

        $requestToBackend = [
            "userId"=>$tagger->id
        ];


        // Prepare and make API request
        $apiParams = [
            'args' => [[
                "employee_name" => $tagger->full_name,
                "company_code" => References::apiCompanyCode,
            ]]
        ];

        $hostURL = References::baseUrl . 'sale.order/call/return_employee_sale_orders';
        $result = References::sendApiRequest($hostURL, "PATCH", $apiParams, []);

        // Log API audit
        ExtApiAuditQuery::saveLogs(
            $requestToBackend,
            $apiParams,
            $hostURL,
            ExtApiAudit::GET_UNPAID_SALES_ORDER,
            $result['httpStatusCode'],
            $result['response'],
            $tagger->id
        );

        // Check API response status
        if ($result['response']['status'] !== 'Success') {
            $error = $result['response'];
            return References::failResponse($error['error'], $error['error_descrip'], 400);
        }

        $apiOrders = $result['response']['data'];

        // If no orders returned from API, return empty array
        if (empty($apiOrders)) {
            return ["data" => []];
        }

        $allOrderIds = array_column($apiOrders, 'id');

        // Get existing orders from database
        $existingOrders = ExtSalesOrder::find()
            ->select(['order_id', 'payment_status', 'selcome_url'])
            ->where(['order_id' => $allOrderIds])
            ->andWhere(['created_by' => $tagger->id])
            ->indexBy('order_id')
            ->asArray()
            ->all();

        // Filter orders to include:
        // 1. Orders not in database
        // 2. Orders in database that are unpaid and have no selcome_url
        $filteredOrders = array_filter($apiOrders, function($order) use ($existingOrders) {
            // Include if order doesn't exist in database
            if (!isset($existingOrders[$order['id']])) {
                return true;
            }

            // Include if exists in database and is unpaid with no selcome_url
            return $existingOrders[$order['id']]['payment_status'] === 'un-paid' &&
                empty($existingOrders[$order['id']]['selcome_url']);
        });

        // Process new orders (not in database)
        $newOrders = array_filter($filteredOrders, function($order) use ($existingOrders) {
            return !isset($existingOrders[$order['id']]);
        });

        foreach ($newOrders as $order) {
            $extOrder = new ExtSalesOrder();
            $extOrder->order_id = $order['id'];
            $extOrder->customer = json_encode([
                'customerId' => $order['customer_id'] ?? null,
                'customerName' => $order['customer_name'] ?? null
            ]);
            $extOrder->devices = json_encode($order['devices'] ?? []);
            $extOrder->products = json_encode($order['products'] ?? []);
            $extOrder->total_amount = $order['total_amount'] ?? 0;
            $extOrder->payment_status = $order['payment_status'] ?? 'un-paid';
            $extOrder->created_at = date('Y-m-d H:i:s');
            $extOrder->created_by = $tagger->id;

            if (isset($order['selcome_url'])) {
                $extOrder->selcome_url = $order['selcome_url'];
            }

            $extOrder->save();
        }

        // Final filter for unpaid status (payment_status != 'paid' and no valid transaction_id)
        $unpaidOrders = array_filter($filteredOrders, function($order) {
            return ($order['payment_status'] !== 'paid') &&
                (empty($order['transaction_id']) ||
                    $order['transaction_id'] === null ||
                    $order['transaction_id'] === 'N/A');
        });

        return ["data" => array_values($unpaidOrders)];
    }


    public static function createSalesOrder(mixed $data, mixed $httpMethod)
    {
        if (is_null($data)) {
            return References::failResponse('Bad Request', "Bad Json Format provided", 400);
        }

        $transaction = \Yii::$app->db->beginTransaction();
        try {

            $customer = $data->customer;
            $devices = $data->devices;
            $tagger = User::findOne($data->userId);
            $taggerName = ($tagger) ? $tagger->full_name : "";
            $slaves = $devices->slaves;
            $userId = $data->userId;

            $orderLines = [
                [
                    'product_id' => 1,
                    'quantity' => 1
                ]
            ];

            if (!empty($slaves)) {
                $orderLines[] = [
                    'product_id' => 2,
                    'quantity' => count($slaves)
                ];
            }

            $requestToBackend = $data;

            $array = [
                'args' => [
                    [
                        'partner_id' => $customer->customerId,
                        'employee_name' => $taggerName,
                        'order_lines' => $orderLines
                    ]
                ]
            ];

            // First create the sales order in our database (within transaction)
            $model = ExtSalesOrderQuery::createSalesOrderTemp($customer, $devices, $userId);

            $devices = json_decode($model->devices);
            $masterSerialNo = $devices->master->serial_no;


            $hostURL = References::baseUrl . 'sale.order/call/create_sale_order';
            $result = References::sendApiRequest($hostURL, "PATCH", $array, []);

            $statusCode = $result['httpStatusCode'];
            $response = $result['response'];

            ExtApiAuditQuery::saveLogs($requestToBackend, $array, $hostURL, ExtApiAudit::CREATE_SALES_ORDER, $statusCode, $response, $userId);

            if ($statusCode != 200) {
                throw new \Exception($response['error_descrip'] ?? 'External system error');
            }

            /* Update with final details */
            ExtSalesOrderQuery::updateSalesOrder($model, $response);

            /*Update master device status to created*/
            Devices::updateAll(['sales_order_status' => Devices::SALES_ORDER_CREATED_STATUS], ['serial_no' => $masterSerialNo]);

            // Commit transaction if everything succeeded
            $transaction->commit();

            return ['data' => $response];

        } catch (\Exception $e) {
            $transaction->rollBack();
            return References::failResponse('Order Processing Failed', $e->getMessage(), 500);
        }
    }

    public static function updateSalesOrder(ExtSalesOrder $model, mixed $response)
    {
        $model->order_id = $response['id'];
        $model->total_amount = $response['amount_total'];
        $model->payment_status = $response['payment_status'];
        $model->products = json_encode($response['products']);

        if (!$model->save()) {
            throw new \Exception('Failed to update order with external data');
        }
    }

    public static function getDevicesPrice($filter)
    {

        $userId = $filter['userId'];
        if (empty($userId)) {
            return References::failResponse('Bad Request', "userId is required", 400);
        }

        $tagger = User::findOne($userId);

        $requestToBackend = [
            "userId"=>$tagger->id
        ];

        $data = [
            'args' => []
        ];

        $hostURL = References::baseUrl . 'sale.order/call/return_today_prices';
        $result = References::sendApiRequest($hostURL, "PATCH", $data, []);

        $statusCode = $result['httpStatusCode'];
        $response = $result['response'];

        ExtApiAuditQuery::saveLogs($requestToBackend,$data, $hostURL, ExtApiAudit::GET_DEVICE_PRICE, $statusCode, $response, $tagger->id);

        if ($statusCode == 200) {

            ExtDevicePricesQuery::createDevicePrice($response['data']);

            return $response['data'];
        } else {
            return References::failResponse($response['error'], $response['error_descrip'], 400);
        }

    }

    public static function createPaymentOrder(mixed $data, mixed $httpMethod)
    {
        if (is_null($data)) {
            return References::failResponse('Bad Request', "Bad Json Format provided", 400);
        }

        $orderId = $data->orderId;
        $userId = $data->userId;

        $requestToBackend = $data;

        $array = [
            'args' => [
                (int)$orderId
            ]
        ];

        $hostURL = References::baseUrl . 'sale.order/call/action_create_payment_order';
        $result = References::sendApiRequest($hostURL, "PATCH", $array, []);

        $statusCode = $result['httpStatusCode'];
        $response = $result;

        ExtApiAuditQuery::saveLogs($requestToBackend,$array, $hostURL, ExtApiAudit::CREATE_PAYMENT_ORDER, $statusCode, $response, $userId);


        if (!empty($statusCode != 200)) {
            return References::failResponse($response['response']['error'], $response['response']['error_descrip'], $statusCode);
        }

        ExtSalesOrder::updateAll([
            'selcome_url' => $response['response']['url'],
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $userId,
        ],
            ['order_id' => $orderId]);

        return $response['response'];

    }

    public static function createSwappingDevices(mixed $data, mixed $httpMethod)
    {
        $userId = $data->userId;
        $tripNo = $data->tripNo;
        $masterSerialNo = $data->masterSerialNo;
        $slaveSerialNo = $data->slaveSerialNo;

        $requestToBackend = $data;

        $array = [
            'args' => [
                [
                    "number" => $tripNo,
                    "e_seal_id" => $masterSerialNo,
                    "slave_ids" => $slaveSerialNo,
                    "company_id" => References::apiCompanyCode
                ]
            ]
        ];

        $hostURL = References::baseUrl . 'ects.trip/call/update_trip_devices';
        $result = References::sendApiRequest($hostURL, "PATCH", $array, []);
        $statusCode = $result['httpStatusCode'];
        $response = $result;

        ExtApiAuditQuery::saveLogs($requestToBackend, $array, $hostURL, ExtApiAudit::CREATE_DEVICE_SWAPPING, $statusCode, $response, $userId);

        /* To handel response in clear */
        if ($httpMethod == 200) {
            return $response;
        }

        return References::failResponse($response['response']['error'], $response['response']['error_descrip'], $statusCode);

    }

    public static function createSalesTripTopUp(mixed $data, mixed $httpMethod)
    {
        if (is_null($data)) {
            return References::failResponse('Bad Request', "Bad Json Format provided", 400);
        }

        $requestToBackend = $data;

        $userId = $data->userId;
        $orderId = $data->orderId;
        $amount = $data->amount;

        $array = [
            'args' => [
                [
                    "order_id" => $orderId,
                    "amount" => $amount
                ]
            ]
        ];

        $hostURL = References::baseUrl . 'sale.order/call/create_topup_order';
        $result = References::sendApiRequest($hostURL, "PATCH", $array, []);
        $statusCode = $result['httpStatusCode'];
        $response = $result['response'];

        ExtApiAuditQuery::saveLogs($requestToBackend,$array, $hostURL, ExtApiAudit::CREATE_SALES_TRIP_TOP_UP, $statusCode, $response, $userId);

        /* To handel response in clear */
        if ($httpMethod == 200) {

            return $response;
        }


        return References::failResponse($response['error'], $response['error_descrip'], $statusCode);

    }

    public static function createSalesTrip($data, $httpMethod)
    {
        $hostURL = '';
        $array = [];
        $userId = null;
        $response = null;
        $statusCode = null;
        $errorMessage = null;

        try {

            if (is_null($data)) {
                $errorMessage = "Bad Json Format provided";
                throw new \Exception($errorMessage);
            }

            $requestToBackend = $data;

            /*Customer Details*/
            $customer = $data->customer;
            $customerId = $customer->customerId;

            $master = $data->master;
            $slaves = $data->slaves;

            /*Gate Details*/
            $gateId = $data->gateId;
            $gate = BorderPort::findOne($gateId);
            $gateName = ($gate) ? $gate->name : "";

            /*Border Details*/
            $borderId = $data->borderId;
            $border = BorderPort::findOne($borderId);
            $borderName = ($border) ? $border->name : "";

            $tripNo = $data->tripNo;
            $merchantNo = $data->merchantNo;
            $vehicleNo = $data->vehicleNo;
            $chassisNo = $data->chassisNo;
            $vehicleType = $data->vehicleType;;
            $trailerNo = $data->trailerNo;

            /*Driver Details*/
            $driver = $data->driver;
            $driverName = $driver->driverName;
            $driverNo = $driver->driverPhoneNo;
            $driverLicense = $driver->driverLicenseNo;
            $driverPassport = $driver->passportNo;

            /*Agent Details*/
            $agent = $data->agent;
            $agentName = $agent->agentName;
            $agentPhoneNo = $agent->agentPhoneNo;

            $containerNo = $data->containerNo;
            $cargoTypeId = $data->cargoTypeId;
            $carType = $data->vehicleType;
            $cargoNo = $data->cargoNo;

            $startDate = $data->startDate;
            $endDate = $data->endDate;

            /*Tagger Details*/
            $userId = $data->userId;
            $tagger = User::findOne($data->userId);
            $taggerName = ($tagger) ? $tagger->full_name : "";
            $branch = ($tagger) ? $tagger->branch : "";

            $isExist = ExtSalesTripsQuery::checkIfExist($tripNo);
            if ($isExist) {
                $errorMessage = "Trip Number has already exist";
                throw new \Exception($errorMessage);
            }

            $cargoType = null;
            if ($cargoTypeId == 1) {
                $cargoType = "DRY CARGO";
            } elseif ($cargoTypeId == 2) {
                $cargoType = "IT";
            } elseif ($cargoTypeId == 3) {
                $cargoType = "WET CARGO";
            }

            // Start transaction
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model = ExtSalesTripsQuery::createTripInTransaction(
                    $customer, $master, $slaves, $gateId, $borderId, $tripNo,
                    $chassisNo, $vehicleType, $vehicleNo, $trailerNo, $merchantNo,
                    $agent, $driver, $cargoTypeId, $cargoNo, $containerNo, $startDate, $endDate, $branch, $userId
                );

                if (!$model) {
                    $errorMessage = "Failed to save trip data locally";
                    throw new \Exception($errorMessage);
                }

                $array = [
                    'args' => [
                        [
                            "number" => $tripNo,
                            "tagger_name" => $taggerName,
                            "customer_name" => (int)$customerId,
                            "agent_fullname" => $agentName,
                            "agent_phone_no" => $agentPhoneNo,
                            "source_location" => "$gateName",
                            "destination" => $borderName,
                            "driver_name" => $driverName,
                            "driver_license" => $driverLicense,
                            "passport_number" => $driverPassport,
                            "e_seal_id" => $master,
                            "slave_ids" => $slaves,
                            "cargo_number" => $cargoNo,
                            "cargo_type" => $cargoType,
                            "car_type" => $carType,
                            "container_horse" => $containerNo,
                            "container_trailer" => $trailerNo,
                            "car_plate_number" => $vehicleNo,
                            "gate_number" => "",
                            "company_id" => References::apiCompanyCode,
                            "payment_reference" => $merchantNo
                        ]
                    ]
                ];

                $hostURL = References::baseUrl . 'ects.trip/call/create';
                $result = References::sendApiRequest($hostURL, "PATCH", $array, []);

                $statusCode = $result['httpStatusCode'];
                $response = $result['response'];

                if ($statusCode != 200) {
                    $errorMessage = $response['error_descrip'];
                    throw new \Exception($errorMessage);
                }

                $transaction->commit();

                ExtApiAuditQuery::saveLogs($requestToBackend, $array, $hostURL, ExtApiAudit::CREATE_SALES_TRIP, $statusCode, $response, $userId);

                return ['receipt' => $model];

            } catch (\Exception $e) {
                // Something failed - roll back the transaction
                if (isset($transaction)) {
                    $transaction->rollBack();
                }

                $errorMessage = $e->getMessage();
                Yii::error("Failed to create sales trip: " . $errorMessage);

                // Log failed API call (will be handled in the finally block)
                throw $e;
            }
        } catch (\Exception $e) {
            // This outer catch will handle all exceptions
            $statusCode = $statusCode ?? 500;
            $response = $response ?? ['error' => $errorMessage];

            // Ensure we have the minimal required data for auditing
            if (!isset($hostURL)) {
                $hostURL = 'N/A - Request not sent due to validation error';
            }
            if (!isset($array)) {
                $array = ['error' => $errorMessage];
            }
            if (!isset($userId)) {
                $userId = null; // or you might want to get it from somewhere else
            }

            // Log the failed attempt
            ExtApiAuditQuery::saveLogs($data, $array, $hostURL, ExtApiAudit::CREATE_SALES_TRIP, $statusCode, $response, $userId);

            return References::failResponse("Bad Request", "Failed to create Sales Trip: " . $errorMessage, 500);
        }
    }

    public static function cancelSalesTrip(mixed $data, mixed $httpMethod)
    {
        if (is_null($data)) {
            return References::failResponse('Bad Request', "Bad Json Format provided", 400);
        }

        $userId = $data->userId;
        $tripNo = $data->tripNo;

        $result = ApiRequestData::updateSalesTrip($tripNo, $userId);
        $statusCode = $result['httpStatusCode'];
        $response = $result;

        /* To handel response in clear */
        if ($httpMethod == 200) {
            return $response;
        }

        return References::failResponse($response['response']['status'], $response['response']['message'], $statusCode);
    }

    public static function updateSalesTrip($tripNo, $userId)
    {
        $requestToBackend = [
            "trip"=>$tripNo,
            "userId"=>$userId
        ];

        $array = [
            'args' => [
                [
                    "trip_number" => $tripNo,
                    "company_code" => References::apiCompanyCode
                ]
            ]
        ];

        $hostURL = References::baseUrl . 'ects.trip/call/cancel_trip';
        $result = References::sendApiRequest($hostURL, "PATCH", $array, []);

        $statusCode = $result['httpStatusCode'];
        ExtApiAuditQuery::saveLogs($requestToBackend, $array, $hostURL, ExtApiAudit::CANCEL_SALES_TRIP, $statusCode, $result, $userId);

        return $result;

    }

}