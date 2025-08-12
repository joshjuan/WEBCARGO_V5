<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[ExtDevicePrices]].
 *
 * @see ExtDevicePrices
 */
class ExtDevicePricesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/
    public static function createDevicePrice(mixed $data)
    {
        $model = new ExtDevicePrices();
        $model->value = json_encode($data);
        $model->created_at = date('Y-m-d H:i:s');
        $model->save(false);
    }

    /**
     * {@inheritdoc}
     * @return ExtDevicePrices[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ExtDevicePrices|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
