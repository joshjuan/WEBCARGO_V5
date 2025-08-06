<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\BorderPortUser;

/**
 * BorderPortUserSearch represents the model behind the search form of `backend\models\BorderPortUser`.
 */
class BorderPortUserSearch extends BorderPortUser
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'border_port', 'assigned_by'], 'integer'],
            [['name', 'assigned_date'], 'safe'],
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

        $query = BorderPortUser::find()
            ->where(['branch' => \Yii::$app->user->identity->branch]);
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }// grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'border_port' => $this->border_port,
            'assigned_date' => $this->assigned_date,
            'assigned_by' => $this->assigned_by,
        ]);
        $query->andFilterWhere(['like', 'name', $this->name]);
        return $dataProvider;

    }
}
