<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\AwaitingReceiveReport;

/**
 * AwaitingReceiveReportControllers represents the model behind the search form of `frontend\models\AwaitingReceiveReport`.
 */
class AwaitingReceiveReportSearch extends AwaitingReceiveReport
{

    public $date_from;
    public $date_to;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'received_from', 'border_port', 'received_from_staff', 'received_status', 'received_by'], 'integer'],
            [['received_at','serial_no', 'remark','date_from','date_to'], 'safe'],
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
        if (\Yii::$app->user->identity->user_type == User::PARTNER) {

            $query = AwaitingReceiveReport::find()
             //   ->where(['in','serial_no',$devices])
                ->where(['partiner' => 1])
                ->andWhere(['branch'=>\Yii::$app->user->identity->branch]);

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
                //'serial_no' => $this->serial_no,
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'received_at' => $this->received_at,
                'received_status' => $this->received_status,
                'received_by' => $this->received_by,
            ]);

            $terms = preg_split('/\r\n|\r|\n/', $this->serial_no);
            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere( [
                    'or',
                    [ '=', 'serial_no', $key ],
                ] );
            }

            $query->andFilterWhere(['like', 'remark', $this->remark]);
            $query ->andFilterWhere(['between', 'DATE_FORMAT(received_at, "%Y-%m-%d")', $this->date_from, $this->date_to]);

            return $dataProvider;
        }
        else{
            $query = AwaitingReceiveReport::find()
                ->where(['branch'=>\Yii::$app->user->identity->branch]);

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
                //'serial_no' => $this->serial_no,
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'received_at' => $this->received_at,
                'received_status' => $this->received_status,
                'received_by' => $this->received_by,
            ]);

            $terms = preg_split('/\r\n|\r|\n/', $this->serial_no);
            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere( [
                    'or',
                    [ '=', 'serial_no', $key ],
                ] );
            }

            $query->andFilterWhere(['like', 'remark', $this->remark]);
            $query ->andFilterWhere(['between', 'DATE_FORMAT(received_at, "%Y-%m-%d")', $this->date_from, $this->date_to]);

            return $dataProvider;
        }

    }


    public function searchDevice($params)
    {

            $query = AwaitingReceiveReport::find();


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
                //'serial_no' => $this->serial_no,
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'received_at' => $this->received_at,
                'received_status' => $this->received_status,
                'received_by' => $this->received_by,
            ]);

            $terms = preg_split('/\r\n|\r|\n/', $this->serial_no);
            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere( [
                    'or',
                    [ '=', 'serial_no', $key ],
                ] );
            }

            $query->andFilterWhere(['like', 'remark', $this->remark]);
            $query->andFilterWhere(['branch'=>\Yii::$app->user->identity->branch]);
            $query ->andFilterWhere(['between', 'DATE_FORMAT(received_at, "%Y-%m-%d")', $this->date_from, $this->date_to]);

            return $dataProvider;


    }
}
