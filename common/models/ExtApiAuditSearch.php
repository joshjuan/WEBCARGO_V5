<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ExtApiAudit;

/**
 * ExtApiAuditSearch represents the model behind the search form of `common\models\ExtApiAudit`.
 */
class ExtApiAuditSearch extends ExtApiAudit
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'endpoint_type', 'status_code', 'created_by'], 'integer'],
            [['request_to_backend', 'request_data', 'endpoint', 'response_data', 'created_at'], 'safe'],
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
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = ExtApiAudit::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'endpoint_type' => $this->endpoint_type,
            'status_code' => $this->status_code,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
        ]);

        $query->andFilterWhere(['like', 'request_to_backend', $this->request_to_backend])
            ->andFilterWhere(['like', 'request_data', $this->request_data])
            ->andFilterWhere(['like', 'endpoint', $this->endpoint])
            ->andFilterWhere(['like', 'response_data', $this->response_data]);

        return $dataProvider;
    }
}
