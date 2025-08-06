<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\SysPensionersVerificationsLogs;

/**
 * SysPensionersVerificationsLogsSearch represents the model behind the search form of `backend\models\SysPensionersVerificationsLogs`.
 */
class SysPensionersVerificationsLogsSearch extends SysPensionersVerificationsLogs
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'uid', 'verified_by_uid', 'verification_status'], 'integer'],
            [['date_time', 'pension_number', 'full_names', 'verification_code', 'code_expiry_date', 'sent_date_time', 'batch_code', 'kin_mobile_number', 'verified_date_time', 'checker_remarks', 'date_renewed', 'start_date', 'expiry_date', 'verified_location', 'verified_by'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = SysPensionersVerificationsLogs::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'date_time' => $this->date_time,
            'uid' => $this->uid,
            'code_expiry_date' => $this->code_expiry_date,
            'sent_date_time' => $this->sent_date_time,
            'verified_by_uid' => $this->verified_by_uid,
            'verified_date_time' => $this->verified_date_time,
            'verification_status' => $this->verification_status,
            'date_renewed' => $this->date_renewed,
            'start_date' => $this->start_date,
            'expiry_date' => $this->expiry_date,
        ]);

        $query->andFilterWhere(['like', 'pension_number', $this->pension_number])
            ->andFilterWhere(['like', 'full_names', $this->full_names])
            ->andFilterWhere(['like', 'verification_code', $this->verification_code])
            ->andFilterWhere(['like', 'batch_code', $this->batch_code])
            ->andFilterWhere(['like', 'kin_mobile_number', $this->kin_mobile_number])
            ->andFilterWhere(['like', 'checker_remarks', $this->checker_remarks])
            ->andFilterWhere(['like', 'verified_location', $this->verified_location])
            ->andFilterWhere(['like', 'verified_by', $this->verified_by]);

        return $dataProvider;
    }
}
