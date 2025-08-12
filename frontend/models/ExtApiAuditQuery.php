<?php

namespace app\models;

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
