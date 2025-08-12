<?php

namespace frontend\controllers;

use common\models\Constants;
use common\models\ExtSalesOrder;
use common\models\ExtSalesOrderQuery;
use common\models\ApiRequestData;
use common\models\References;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use yii\web\BadRequestHttpException;

class GatewayController extends \yii\rest\ActiveController
{

    public $modelClass = 'frontend\models\SalesTransactions';


    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionGetRecords($userId = null, $merchantNo = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $request = \Yii::$app->request;
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $currentUrl = $request->getUrl();
        $messageType = $request->headers->get('message-type');

        $data = json_decode(file_get_contents("php://input"));

        try {

            if (empty($messageType)) {
                References::failResponse('Bad Request', 'Missing message-type in header', 400);
            }

            $filters = [
              "userId" => $userId,
                "merchantNo" => $merchantNo,
            ];

            return match ($messageType) {
                Constants::API_MESSAGE_TYPE_GET_DEVICES_PRICE => ApiRequestData::getDevicesPrice($filters),
                Constants::API_MESSAGE_TYPE_GET_PAYMENT_VALIDATION => ApiRequestData::getPaymentValidation($filters),
                Constants::API_MESSAGE_TYPE_GET_UNPAID_SALES_ORDER => ApiRequestData::getUnPaidSalesOrder($filters),
                Constants::API_MESSAGE_TYPE_GET_PAID_SALES_ORDER => ApiRequestData::getPaidSalesOrder($filters),
                Constants::API_MESSAGE_TYPE_GET_CUSTOMERS => ApiRequestData::getCustomersFromTespa($filters),
                default => References::failResponse('Bad Request', "Undefined message type: '$messageType' in header", 400),
            };

        } catch (\Exception $e) {

            \Yii::error([
                'action' => 'Request processing failed',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'stackTrace' => $e->getTraceAsString(),
                'requestDetails' => [
                    'httpMethod' => $httpMethod,
                    'url' => $currentUrl,
                    'headers' => [
                        'message-type' => $messageType
                    ],
                    'filters' => $data
                ]
            ], 'application');

            return References::failResponse('Internal Server Error', "Please contact System Administrator for more support due to " . $e->getMessage(), 500);

        }
    }

    public function actionPostRecords()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $request = \Yii::$app->request;
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $currentUrl = $request->getUrl();
        $messageType = $request->headers->get('message-type');

        $data = json_decode(file_get_contents("php://input"));

        try {

            if (empty($messageType)) {
                References::failResponse('Bad Request', 'Missing message-type in header', 400);
            }

            return match ($messageType) {
                Constants::API_MESSAGE_TYPE_CREATE_SALES_ORDER_TOP_UP => ApiRequestData::createSalesTripTopUp($data, $httpMethod),
                Constants::API_MESSAGE_TYPE_CREATE_DEVICE_SWAPPING => ApiRequestData::createSwappingDevices($data, $httpMethod),
                Constants::API_MESSAGE_TYPE_CREATE_SALES_TRIP => ApiRequestData::cancelSalesTrip($data, $httpMethod),
                Constants::API_MESSAGE_TYPE_CREATE_SALES_TRIP_ORDER => ApiRequestData::createSalesTrip($data, $httpMethod),
//                Constants::API_MESSAGE_TYPE_UPDATE_SALES_TRIP_ORDER => PostTespaDataQuery::updateSalesTrip($data, $httpMethod),
                Constants::API_MESSAGE_TYPE_CREATE_PAYMENT_ORDER => ApiRequestData::createPaymentOrder($data, $httpMethod),
                Constants::API_MESSAGE_TYPE_CREATE_SALES_ORDER => ApiRequestData::createSalesOrder($data, $httpMethod),
                default => References::failResponse('Bad Request', "Undefined message type: '$messageType' in header", 400),
            };

        } catch (\Exception $e) {

            \Yii::error([
                'action' => 'Request processing failed',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'stackTrace' => $e->getTraceAsString(),
                'requestDetails' => [
                    'httpMethod' => $httpMethod,
                    'url' => $currentUrl,
                    'headers' => [
                        'message-type' => $messageType
                    ],
                    'filters' => $data
                ]
            ], 'application');

            return References::failResponse('Internal Server Error', "Please contact System Administrator for more support due to " . $e->getMessage(), 500);

        }

    }

    protected function verbs()
    {
        return [
            'get-records' => ['GET'],
        ];
    }


}