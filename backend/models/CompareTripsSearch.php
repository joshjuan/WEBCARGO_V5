<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\CompareTrips;

/**
 * CompareTripsSearch represents the model behind the search form of `backend\models\CompareTrips`.
 */
class CompareTripsSearch extends CompareTrips
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'total_activation', 'upload_by'], 'integer'],
            [['document_path', 'document_name', 'date_from', 'date_to', 'status', 'upload_date'], 'safe'],
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
        $query = CompareTrips::find();

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
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'total_activation' => $this->total_activation,
            'upload_by' => $this->upload_by,
            'upload_date' => $this->upload_date,
        ]);

        $query->andFilterWhere(['like', 'document_path', $this->document_path])
            ->andFilterWhere(['like', 'document_name', $this->document_name])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
