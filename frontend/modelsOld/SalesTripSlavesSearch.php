<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\SalesTripSlaves;

/**
 * SalesTripSlavesSearch represents the model behind the search form of `frontend\models\SalesTripSlaves`.
 */
class SalesTripSlavesSearch extends SalesTripSlaves
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'sale_id', 'serial_no'], 'integer'],
            [['created_at'], 'safe'],
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
        $query = SalesTripSlaves::find();
      //  ->where(['branch'=>\Yii::$app->user->identity->branch]);

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
            'sale_id' => $this->sale_id,
            'serial_no' => $this->serial_no,
            'created_at' => $this->created_at,
        ]);

        return $dataProvider;
    }
    public function searchSlave($params)
    {
        $query = SalesTripSlaves::find();
        $query->where(['sale_id'=>$params]);
      //  ->where(['branch'=>\Yii::$app->user->identity->branch]);

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
          //  'sale_id' => $this->sale_id,
            'serial_no' => $this->serial_no,
            'created_at' => $this->created_at,
        ]);

        return $dataProvider;
    }
}
