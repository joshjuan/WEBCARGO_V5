<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Devices;
use yii\db\Query;

/**
 * DevicesSearch represents the model behind the search form of `frontend\models\Devices`.
 */
class DevicesSearch extends Devices
{
    public $date_from;
    public $date_to;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'received_from', 'border_port', 'received_from_staff', 'created_by', 'status', 'branch', 'type', 'view_status','received_by','released_to'], 'integer'],
            [['serial_no', 'sim_card', 'received_at', 'remark', 'created_at'], 'safe'],
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
    public function searchIndex($params)
    {
     //   if (\Yii::$app->user->identity->user_type == User::PARTNER){
            $query = Devices::find();
              //  ->where(['branch' => \Yii::$app->user->identity->branch]);
                //->andWhere(['partiner'=>1]);

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
              //   $query->where('0=1');
                return $dataProvider;
            }

            // grid filtering conditions
            $query->andFilterWhere([
                'id' => $this->id,
                //  'serial_no' => $this->serial_no,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'type' => $this->type,
                'device_category' => $this->device_category,
            ]);
            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }
        $query->andFilterWhere([
            'branch' => \Yii::$app->user->identity->branch,
        ]);
            return $dataProvider;

    }



    public function search($params)
    {
        if (\Yii::$app->user->identity->user_type == User::PARTNER) {
            $query = Devices::find()
                ->where(['branch' => \Yii::$app->user->identity->branch])
                ->andWhere(['partiner' => 1]);

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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'received_at' => $this->received_at,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);

            $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
                ->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark]);

            return $dataProvider;
        }
        else{
            $query = Devices::find()
                ->where(['branch' => \Yii::$app->user->identity->branch]);

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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'received_at' => $this->received_at,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);

            $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
                ->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark]);

            return $dataProvider;
        }
    }


    public function searchAwaitReceive($params)
    {
        if (\Yii::$app->user->identity->user_type == User::PARTNER) {
            $query = Devices::find()
                ->where(['branch' => \Yii::$app->user->identity->branch])
                ->andWhere(['view_status'=>self::awaiting_receive])
                ->andWhere(['partiner' => 1]);

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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'received_at' => $this->received_at,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);

            $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
                ->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark]);

            return $dataProvider;
        }
        else{
            $query = Devices::find()
                ->where(['branch' => \Yii::$app->user->identity->branch])
              ->andWhere(['view_status'=>self::awaiting_receive]);
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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'received_at' => $this->received_at,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);

            $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
                ->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark]);

            return $dataProvider;
        }
    }

    public function searchAwait($params)
    {
        if (\Yii::$app->user->identity->user_type == User::PARTNER) {
            $query = Devices::find();
            // ->where(['branch' => \Yii::$app->user->identity->branch])
            //  ->andWhere(['view_status'=>self::awaiting_receive])
            //  ->andWhere(['partiner' => 1]);

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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'received_at' => $this->received_at,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);

            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }
            $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark])
                ->andFilterWhere(['view_status'=>self::awaiting_receive])
                -> andFilterWhere   (['branch' => \Yii::$app->user->identity->branch]);
            return $dataProvider;

        }
        else{
            $query = Devices::find();
            //   ->where(['branch' => \Yii::$app->user->identity->branch]);
            //    ->andWhere(['view_status'=>self::awaiting_receive]);
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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'received_at' => $this->received_at,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
                'received_by' => $this->received_by,
            ]);


            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }
            $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark])
                ->andFilterWhere(['view_status'=>self::awaiting_receive])
                -> andFilterWhere   (['branch' => \Yii::$app->user->identity->branch]);
            return $dataProvider;
        }
    }

    public function searchReceived($params)
    {
        if (\Yii::$app->user->identity->user_type == User::PARTNER) {
            $query = Devices::find()
                ->where(['branch' => \Yii::$app->user->identity->branch])
                ->andWhere(['view_status'=>self::received_devices])
                ->andWhere(['partiner' => 1]);

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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'received_at' => $this->received_at,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);

            $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
                ->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark]);

            return $dataProvider;
        }
        else{
            $query = Devices::find()
                ->where(['branch' => \Yii::$app->user->identity->branch])
                ->andWhere(['view_status'=>self::received_devices]);
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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'received_at' => $this->received_at,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);

            $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
                ->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark]);

            return $dataProvider;
        }
    }

    public function searchReceive($params)
    {
        if (\Yii::$app->user->identity->user_type == User::PARTNER) {
            $query = Devices::find();
            // ->where(['branch' => \Yii::$app->user->identity->branch])
            //  ->andWhere(['view_status'=>self::awaiting_receive])
            //  ->andWhere(['partiner' => 1]);

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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'received_at' => $this->received_at,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);

            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }
            $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark])
                ->andFilterWhere(['view_status'=>self::received_devices])
                -> andFilterWhere   (['branch' => \Yii::$app->user->identity->branch]);
            return $dataProvider;

        }
        else{
            $query = Devices::find();
            //   ->where(['branch' => \Yii::$app->user->identity->branch]);
            //    ->andWhere(['view_status'=>self::awaiting_receive]);
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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'received_at' => $this->received_at,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);


            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }
            $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark])
                ->andFilterWhere(['view_status'=>self::received_devices])
                -> andFilterWhere   (['branch' => \Yii::$app->user->identity->branch]);
            return $dataProvider;
        }
    }
    public function searchIntransit($params)
    {
        if (\Yii::$app->user->identity->user_type == User::PARTNER) {
            $query = Devices::find()
                ->where(['branch' => \Yii::$app->user->identity->branch])
                ->andWhere(['view_status'=>self::stock_devices])
                ->andWhere(['status'=>StockDevices::not_deactivated])
                ->andWhere(['partiner' => 1]);

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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'received_at' => $this->received_at,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);

            $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
                ->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark]);

            return $dataProvider;
        }
        else{
            $query = Devices::find()
                ->where(['branch' => \Yii::$app->user->identity->branch])
                ->andWhere(['view_status'=>self::stock_devices])
                ->andWhere(['status'=>StockDevices::not_deactivated]);
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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'received_at' => $this->received_at,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);

            $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
                ->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark]);

            return $dataProvider;
        }
    }





    public function released($params)
    {
        if (\Yii::$app->user->identity->user_type == User::PARTNER) {
            $query = Devices::find()
                ->where(['branch' => \Yii::$app->user->identity->branch])
                ->andWhere(['view_status'=>self::released_devices])
                ->andWhere(['partiner' => 1]);

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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'received_at' => $this->received_at,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);

            $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
                ->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark]);

            return $dataProvider;
        }
        else{
            $query = Devices::find()
                ->where(['branch' => \Yii::$app->user->identity->branch])
                ->andWhere(['view_status'=>self::released_devices]);
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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'received_at' => $this->received_at,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);

            $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
                ->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark]);

            return $dataProvider;
        }
    }

    public function releasedSearch($params)
    {
        if (\Yii::$app->user->identity->user_type == User::PARTNER) {
            $query = Devices::find();
            // ->where(['branch' => \Yii::$app->user->identity->branch])
            //  ->andWhere(['view_status'=>self::awaiting_receive])
            //  ->andWhere(['partiner' => 1]);

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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'received_at' => $this->received_at,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);

            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }
            $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark])
                ->andFilterWhere(['view_status'=>self::released_devices])
                -> andFilterWhere   (['branch' => \Yii::$app->user->identity->branch]);
            return $dataProvider;

        }
        else{
            $query = Devices::find();
            //   ->where(['branch' => \Yii::$app->user->identity->branch]);
            //    ->andWhere(['view_status'=>self::awaiting_receive]);
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
                'received_from' => $this->received_from,
                'released_to' => $this->released_to,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'received_at' => $this->received_at,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);


            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }
            $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark])
                ->andFilterWhere(['view_status'=>self::released_devices])
                -> andFilterWhere   (['branch' => \Yii::$app->user->identity->branch]);
            return $dataProvider;
        }
    }


    public function searchRoad($params)
    {
        if (\Yii::$app->user->identity->user_type == User::PARTNER) {
            $query = Devices::find();
            // ->where(['branch' => \Yii::$app->user->identity->branch])
            //  ->andWhere(['view_status'=>self::awaiting_receive])
            //  ->andWhere(['partiner' => 1]);

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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'received_at' => $this->received_at,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);

            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }
            $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark])
                ->andFilterWhere(['view_status'=>self::in_transit])
                -> andFilterWhere   (['branch' => \Yii::$app->user->identity->branch]);
            return $dataProvider;

        }
        else{
            $query = Devices::find();
            //   ->where(['branch' => \Yii::$app->user->identity->branch]);
            //    ->andWhere(['view_status'=>self::awaiting_receive]);
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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'received_at' => $this->received_at,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);


            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }
            $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark])
                ->andFilterWhere(['view_status'=>self::in_transit])
                ->andFilterWhere(['view_status'=>self::in_transit])
                -> andFilterWhere   (['branch' => \Yii::$app->user->identity->branch]);
            return $dataProvider;
        }
    }

    public function onRoad($params)
    {
        if (\Yii::$app->user->identity->user_type == User::PARTNER) {
            $query = Devices::find()
                ->where(['branch' => \Yii::$app->user->identity->branch])
                ->andWhere(['view_status'=>self::in_transit])
                ->andWhere(['partiner' => 1]);

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 300],
                'sort' => [
                    'defaultOrder' => [
                        'received_at' => SORT_DESC,
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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'received_at' => $this->received_at,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);

            $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
                ->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark]);

            return $dataProvider;
        }
        else{
            $query = Devices::find()
                ->where(['branch' => \Yii::$app->user->identity->branch])
                ->andWhere(['view_status'=>self::in_transit]);
            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 300],
                'sort' => [
                    'defaultOrder' => [
                        'received_at' => SORT_DESC,
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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'received_at' => $this->received_at,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);

            $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
                ->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark]);

            return $dataProvider;
        }
    }
    public function Fault($params)
    {
        if (\Yii::$app->user->identity->user_type == User::PARTNER) {
            $query = Devices::find()
                ->where(['branch' => \Yii::$app->user->identity->branch])
                ->andWhere(['view_status'=>self::fault_devices])
                ->andWhere(['partiner' => 1]);

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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'received_at' => $this->received_at,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);

            $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
                ->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark]);

            return $dataProvider;
        }
        else{
            $query = Devices::find()
                ->where(['branch' => \Yii::$app->user->identity->branch])
                ->andWhere(['view_status'=>self::fault_devices]);
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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'received_at' => $this->received_at,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);

            $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
                ->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark]);

            return $dataProvider;
        }
    }
    public function FaultSearch($params)
    {
        if (\Yii::$app->user->identity->user_type == User::PARTNER) {
            $query = Devices::find();
            // ->where(['branch' => \Yii::$app->user->identity->branch])
            //  ->andWhere(['view_status'=>self::awaiting_receive])
            //  ->andWhere(['partiner' => 1]);

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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'received_at' => $this->received_at,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);

            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }
            $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark])
                ->andFilterWhere(['view_status'=>self::fault_devices])
                -> andFilterWhere   (['branch' => \Yii::$app->user->identity->branch]);
            return $dataProvider;

        }
        else{
            $query = Devices::find();
            //   ->where(['branch' => \Yii::$app->user->identity->branch]);
            //    ->andWhere(['view_status'=>self::awaiting_receive]);
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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'received_at' => $this->received_at,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);


            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }
            $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark])
                ->andFilterWhere(['view_status'=>self::fault_devices])
                -> andFilterWhere   (['branch' => \Yii::$app->user->identity->branch]);
            return $dataProvider;
        }
    }

}
