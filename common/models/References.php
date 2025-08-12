<?php

namespace common\models;

class References
{

    /* TESPA Information */
    const baseUrl = 'https://tespa.demo.ictpack.net/api/v1/tespa/';
    const apiUsernameTESPA = "webcorp";
    const apiTokenTESPA = "9752304a-9c24-4732-9e66-92dc3b9dfe8e";
    const apiCompanyCode = "WCLJ3X7";


    public static function sendApiRequest($url, string $method, $data = null, array $curlOptions = []): array
    {
        $api_username = References::apiUsernameTESPA;
        $api_token = References::apiTokenTESPA;

        $credentials = "$api_username:$api_token";
        $encoded_credentials = base64_encode($credentials);

        $headers = [
            'Content-Type: application/json',
            'Authorization: Basic ' . $encoded_credentials
        ];

        $curl = curl_init();
        $method = strtoupper($method);

        if (!empty($data)) {
            $jsonData = json_encode($data);

            if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
                $curlOptions[CURLOPT_POSTFIELDS] = $jsonData;
            }
        }

        $defaultOptions = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => $headers
        ];

        curl_setopt_array($curl, $defaultOptions + $curlOptions);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        curl_close($curl);

        $decodedResponse = json_decode($response, true) ?? [];

        return [
            'httpStatusCode' => $httpCode,
            'response' => $decodedResponse,
            'error' => $error ?: null
        ];
    }

    public static function failResponse($message, $details, $code): array
    {
        \Yii::$app->response->statusCode = $code;
        return [
            'error' => true,
            'message' => $message,
            'details' => $details,
        ];
    }

}