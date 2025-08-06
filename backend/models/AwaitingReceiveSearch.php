<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\AwaitingReceive;

/**
 * AwaitingReceiveSearch represents the model behind the search form of `backend\models\AwaitingReceive`.
 */
class AwaitingReceiveSearch extends AwaitingReceive
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'received_from', 'border_port', 'received_from_staff', 'received_status', 'received_by', 'view_status'], 'integer'],
            [['received_at', 'remark','serial_no'], 'safe'],
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


            $query = AwaitingReceive::find();
            $query->where(['view_status' => Devices::awaiting_receive])
            ->andWhere(['branch'=>\Yii::$app->user->identity->branch]);
            // add conditions that should always apply here
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 300],
                'sort' => [
                    'defaultOrder' => [
                        'id' => SORT_DESC,
                    ],
                ],
            ]);
            $this->load($params);
            if (!$this->validate()) {
                // uncomment the following line if you do not want to return any records when validation fails
                // $query->where('0=1');
                return $dataProvider;
            }// grid filtering conditions
            $query->andFilterWhere([
                'id' => $this->id,
                //  'serial_no' => $this->serial_no,
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'received_at' => $this->received_at,
                'received_status' => $this->received_status,
                'received_by' => $this->received_by,
                'view_status' => $this->view_status,
            ]);
            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }
            $query->andFilterWhere(['like', 'remark', $this->remark]);
            return $dataProvider;

    }


    public function searchActive($params)
    {

            $query = AwaitingReceive::find();
            $query->where(['branch'=>\Yii::$app->user->identity->branch]);
            // add conditions that should always apply here
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 300],
                'sort' => [
                    'defaultOrder' => [
                        'id' => SORT_DESC,
                    ],
                ],
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
                //  'serial_no' => $this->serial_no,
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'received_at' => $this->received_at,
                'received_status' => $this->received_status,
                'received_by' => $this->received_by,
                'view_status' => Devices::awaiting_receive,
            ]);
            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            $query->andFilterWhere(['in', 'serial_no', $terms]);

            $query->andFilterWhere(['like', 'remark', $this->remark]);

            return $dataProvider;

    }
}
