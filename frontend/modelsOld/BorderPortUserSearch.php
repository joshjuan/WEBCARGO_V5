<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\BorderPortUser;

/**
 * BorderPortUserSearch represents the model behind the search form of `frontend\models\BorderPortUser`.
 */
class BorderPortUserSearch extends BorderPortUser
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'border_port', 'type', 'assigned_by'], 'integer'],
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


        $query = BorderPortUser::find();
        // add conditions that should always apply here
        $user = User::find()->select('id')->where(['branch' => \Yii::$app->user->identity->branch])->asArray();
        $query->where(['in', 'name', $user]);

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
            'type' => $this->type,
            'assigned_date' => $this->assigned_date,
            'assigned_by' => $this->assigned_by,
        ]);
        $query->andFilterWhere(['like', 'name', $this->name]);
        return $dataProvider;

    }

    public function searchPort($params)
    {


        $query = BorderPortUser::find();

        // add conditions that should always apply here
        $user = User::find()->select('id')->where(['branch' => \Yii::$app->user->identity->branch])->andWhere(['user_type'=>User::PORT_STAFF])->asArray();
        $query->where(['in', 'name', $user]);

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
            'type' => $this->type,
            'assigned_date' => $this->assigned_date,
            'assigned_by' => $this->assigned_by,
        ]);
        $query->andFilterWhere(['like', 'name', $this->name]);
        return $dataProvider;

    }

    public function searchBorder($params)
    {

        $query = BorderPortUser::find();

        // add conditions that should always apply here
        $user = User::find()->select('id')->where(['branch' => \Yii::$app->user->identity->branch])->andWhere(['user_type'=>User::BORDER_STAFF])->asArray();
        $query->where(['in', 'name', $user]);

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
            'type' => $this->type,
            'assigned_date' => $this->assigned_date,
            'assigned_by' => $this->assigned_by,
        ]);
        $query->andFilterWhere(['like', 'name', $this->name]);
        return $dataProvider;

    }

}
