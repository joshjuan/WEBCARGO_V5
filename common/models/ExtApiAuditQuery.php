<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[ExtApiAudit]].
 *
 * @see ExtApiAudit
 */
class ExtApiAuditQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/
    public static function saveLogs($requestToBackend, $array, $hostURL, $endpointType, $statusCode, $response, $userId)
    {
        try {
            $model = new ExtApiAudit();
            $model->request_to_backend = json_encode($requestToBackend);
            $model->request_data = json_encode($array);
            $model->endpoint = $hostURL;
            $model->endpoint_type = $endpointType;
            $model->status_code = $statusCode;
            $model->response_data = json_encode($response);
            $model->created_at = date('Y-m-d H:i:s');
            $model->created_by = $userId;
            $model->save(false);
        } catch (\Exception $e) {

        }
    }

    /**
     * {@inheritdoc}
     * @return ExtApiAudit[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ExtApiAudit|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
