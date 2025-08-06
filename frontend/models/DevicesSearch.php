<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Devices;
use yii\db\Query;

/**
 * DevicesSearch represents the model behind the search form of `backend\models\Devices`.
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
            [['id', 'received_from', 'border_port', 'received_from_staff', 'created_by', 'status', 'branch', 'type', 'view_status', 'released_to'], 'integer'],
            [['serial_no', 'sim_card', 'remark', 'created_at'], 'safe'],
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
        if (Yii::$app->user->identity->branch == 1) {

            $query = Devices::find();

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

            if ($this->view_status==0){

                // print_r($this->status);die;
                $query->andFilterWhere([
                    '!=', 'view_status' ,1,
                ]);
            }
            else{
                $query->andFilterWhere([
                    'view_status' => $this->view_status,
                ]);
            }


            // grid filtering conditions
            $query->andFilterWhere([
                'id' => $this->id,
               // 'view_status' => $this->view_status,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                // 'branch' => \Yii::$app->user->identity->branch,
            ]);
            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }


            $queryBranches = Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0]);

// Query to retrieve the main branch with id = 1
            $queryMainBranch = Branches::find()
                ->select(['id'])
                ->where(['id' => 1]);

// Combine the two queries using the union() method
            $query = (new Query())
                ->select(['id'])
                ->from(['u' => $queryBranches->union($queryMainBranch)]);

// Execute the combined query and fetch the results
            $branches = $query->all();

            $query->andFilterWhere([
                'in', 'branch', $branches
            ]);


            return $dataProvider;
        } else {

            //   if (\Yii::$app->user->identity->user_type == User::PARTNER){
            $query = Devices::find();
            //    ->where(['branch' => \Yii::$app->user->identity->branch]);
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

            if ($this->view_status==0){

                // print_r($this->status);die;
                $query->andFilterWhere([
                    '!=', 'view_status' ,1,
                ]);
            }
            else{
                $query->andFilterWhere([
                    'view_status' => $this->view_status,
                ]);
            }

            // grid filtering conditions
            $query->andFilterWhere([
                'id' => $this->id,
               //  'view_status' => $this->view_status,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                // 'branch' => \Yii::$app->user->identity->branch,
            ]);
            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }

            $query->andFilterWhere([
                'branch' => \Yii::$app->user->identity->branch
            ]);


            return $dataProvider;
        }


    }


    public function search($params)
    {
        if (Yii::$app->user->identity->branch == 1) {


            $branch = \frontend\models\Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0])
                ->asArray();

            $query = Devices::find()
                ->where(['in', 'branch', $branch])
                ->orWhere(['branch' => 1]);
            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
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
        } else {


            $query = Devices::find()
                ->where(['branch' => Yii::$app->user->identity->branch]);

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider(['query' => $query,
                'pagination' => ['pageSize' => 100],
                'sort' => ['defaultOrder' => ['id' => SORT_DESC,],],]);

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

    public
    function searchAccount($params)
    {

        if (Yii::$app->user->identity->branch == 1) {

            $queryBranches = Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0]);

// Query to retrieve the main branch with id = 1
            $queryMainBranch = Branches::find()
                ->select(['id'])
                ->where(['id' => 1]);

// Combine the two queries using the union() method
            $branches = (new Query())
                ->select(['id'])
                ->from(['u' => $queryBranches->union($queryMainBranch)]);

// Execute the combined query and fetch the results
            // $branches = $query->all();

            $query = Devices::find()
                ->where(['view_status' => Devices::accounts])
                ->andWhere(['in', 'branch', $branches]);
            //  ->orWhere(['branch'=>1]);


            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
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

            // $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
            $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark]);

            return $dataProvider;

        } else {

            $query = Devices::find()
                ->where(['view_status' => Devices::accounts])
                ->andWhere(['branch' => Yii::$app->user->identity->branch]);

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
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

            // $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
            $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark]);

            return $dataProvider;

        }

    }


    public
    function searchActive($params)
    {

        if (Yii::$app->user->identity->branch == 1) {

            $queryBranches = Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0]);

// Query to retrieve the main branch with id = 1
            $queryMainBranch = Branches::find()
                ->select(['id'])
                ->where(['id' => 1]);

// Combine the two queries using the union() method
            $branches = (new Query())
                ->select(['id'])
                ->from(['u' => $queryBranches->union($queryMainBranch)]);

// Execute the combined query and fetch the results
            // $branches = $query->all();

            $query = Devices::find()
                ->andWhere(['not in', 'view_status', [Devices::fault_devices, Devices::damaged,Devices::registration]])
                ->andWhere(['in', 'branch', $branches]);
            //  ->orWhere(['branch'=>1]);


            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
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

            // $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
            $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark]);

            return $dataProvider;

        } else {

            $query = Devices::find()
                ->andWhere(['not in', 'view_status', [Devices::fault_devices, Devices::damaged,Devices::registration]])
                ->andWhere(['branch' => Yii::$app->user->identity->branch]);

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
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

            // $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
            $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark]);

            return $dataProvider;

        }

    }

    public
    function searchAccountResults($params)
    {

        if (Yii::$app->user->identity->branch == 1) {

// Execute the combined query and fetch the results
            // $branches = $query->all();

            $query = Devices::find();

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
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
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                //  'branch' => $this->branch,
                'type' => $this->type,
                //  'view_status' => $this->view_status,
            ]);

            //¬  $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
            $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark]);


            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }

            $query->andFilterWhere([
                'view_status' => Devices::accounts
            ]);

            $queryBranches = Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0]);

// Query to retrieve the main branch with id = 1
            $queryMainBranch = Branches::find()
                ->select(['id'])
                ->where(['id' => 1]);

// Combine the two queries using the union() method
            $branches = (new Query())
                ->select(['id'])
                ->from(['u' => $queryBranches->union($queryMainBranch)]);

            $query->andFilterWhere([
                'in', 'branch', $branches
            ]);


            return $dataProvider;


        } else {


            $query = Devices::find();

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
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
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                //  'branch' => $this->branch,
                'type' => $this->type,
                //  'view_status' => $this->view_status,
            ]);

            //¬  $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
            $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark]);


            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }

            $query->andFilterWhere([
                'view_status' => Devices::accounts
            ]);


            $query->andFilterWhere([
                'branch' => Yii::$app->user->identity->branch
            ]);

            return $dataProvider;
        }

    }


    public
    function searchAwaitingStorage($params)
    {

        if (Yii::$app->user->identity->branch == 1) {

            $queryBranches = Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0]);

// Query to retrieve the main branch with id = 1
            $queryMainBranch = Branches::find()
                ->select(['id'])
                ->where(['id' => 1]);

// Combine the two queries using the union() method
            $branches = (new Query())
                ->select(['id'])
                ->from(['u' => $queryBranches->union($queryMainBranch)]);

// Execute the combined query and fetch the results
            // $branches = $query->all();

            $query = Devices::find()
                ->where(['view_status' => Devices::awaiting_store])
                ->andWhere(['in', 'branch', $branches]);

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
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

        } else {


            $query = Devices::find()
                ->where(['view_status' => Devices::awaiting_store])
                ->andWhere(['branch' => Yii::$app->user->identity->branch]);

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
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

    public
    function searchAwaitingStorageResults($params)
    {

        if (Yii::$app->user->identity->branch == 1) {


            $query = Devices::find();

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
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
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                //  'view_status' => $this->view_status,
            ]);

            //¬  $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
            $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark]);


            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }

            $query->andFilterWhere([
                'view_status' => Devices::awaiting_store
            ]);


            $queryBranches = Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0]);

// Query to retrieve the main branch with id = 1
            $queryMainBranch = Branches::find()
                ->select(['id'])
                ->where(['id' => 1]);

// Combine the two queries using the union() method
            $branches = (new Query())
                ->select(['id'])
                ->from(['u' => $queryBranches->union($queryMainBranch)]);

            $query->andFilterWhere([
                'in', 'branch', $branches
            ]);

            return $dataProvider;


        } else {


            $query = Devices::find();

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
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
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                //  'view_status' => $this->view_status,
            ]);

            //¬  $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
            $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark]);


            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }

            $query->andFilterWhere([
                'view_status' => Devices::awaiting_store
            ]);

            $query->andFilterWhere([
                'branch' => Yii::$app->user->identity->branch
            ]);

            return $dataProvider;

        }

    }

    public
    function searchStorageResults($params)
    {

        if (Yii::$app->user->identity->branch==1){

            $query = Devices::find();

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
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
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                //  'view_status' => $this->view_status,
            ]);

            //¬  $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
            $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark]);


            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }

            $query->andFilterWhere([
                'view_status' => Devices::store
            ]);

            $queryBranches = Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0]);

// Query to retrieve the main branch with id = 1
            $queryMainBranch = Branches::find()
                ->select(['id'])
                ->where(['id' => 1]);

// Combine the two queries using the union() method
            $branches = (new Query())
                ->select(['id'])
                ->from(['u' => $queryBranches->union($queryMainBranch)]);

            $query->andFilterWhere([
                'in','branch', $branches
            ]);

            return $dataProvider;


        }
        else {


            $query = Devices::find();

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
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
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                //  'view_status' => $this->view_status,
            ]);

            //¬  $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
            $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark]);


            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }

            $query->andFilterWhere([
                'view_status' => Devices::store
            ]);


            $query->andFilterWhere([
                'branch'=>Yii::$app->user->identity->branch
            ]);

            return $dataProvider;
        }

    }

    public
    function searchStorage($params)
    {

        if (Yii::$app->user->identity->branch == 1) {

            $queryBranches = Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0]);

// Query to retrieve the main branch with id = 1
            $queryMainBranch = Branches::find()
                ->select(['id'])
                ->where(['id' => 1]);

// Combine the two queries using the union() method
            $branches = (new Query())
                ->select(['id'])
                ->from(['u' => $queryBranches->union($queryMainBranch)]);


            $query = Devices::find()
                ->where(['view_status' => Devices::store])
                ->andWhere(['in', 'branch', $branches]);

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
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

        } else {


            $query = Devices::find()
                ->where(['view_status' => Devices::store])
                ->andWhere(['branch' => Yii::$app->user->identity->branch]);

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
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

    public
    function searchAwaitingAllocation($params)
    {

        if (Yii::$app->user->identity->branch ==1){


            $queryBranches = Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0]);

// Query to retrieve the main branch with id = 1
            $queryMainBranch = Branches::find()
                ->select(['id'])
                ->where(['id' => 1]);

// Combine the two queries using the union() method
            $branches = (new Query())
                ->select(['id'])
                ->from(['u' => $queryBranches->union($queryMainBranch)]);


            $query = Devices::find()
                ->where(['view_status' => Devices::awaiting_allocation])
                ->andWhere(['in','branch',$branches]);

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
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
        else {

            $query = Devices::find()
                ->where(['view_status' => Devices::awaiting_allocation])
                ->andWhere(['branch' => Yii::$app->user->identity->branch]);

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
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




    public
    function searchReceive($params)
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
                ->andFilterWhere(['view_status' => self::received_devices])
                ->andFilterWhere(['branch' => \Yii::$app->user->identity->branch]);
            return $dataProvider;

        } else {
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
                ->andFilterWhere(['view_status' => self::received_devices])
                ->andFilterWhere(['branch' => \Yii::$app->user->identity->branch]);
            return $dataProvider;
        }
    }


    public
    function searchIntransit($params)
    {


        if (Yii::$app->user->identity->branch ==1){


            $queryBranches = Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0]);

// Query to retrieve the main branch with id = 1
            $queryMainBranch = Branches::find()
                ->select(['id'])
                ->where(['id' => 1]);

// Combine the two queries using the union() method
            $branches = (new Query())
                ->select(['id'])
                ->from(['u' => $queryBranches->union($queryMainBranch)]);

            $query = Devices::find()
                ->where(['view_status' => Devices::in_transit])
                ->andWhere(['in','branch' , $branches]);

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
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
        else {

            $query = Devices::find()
                ->where(['view_status' => Devices::in_transit])
                ->andWhere(['branch' => Yii::$app->user->identity->branch]);

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
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


    function searchDamaged($params)
    {


        if (Yii::$app->user->identity->branch ==1){


            $queryBranches = Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0]);

// Query to retrieve the main branch with id = 1
            $queryMainBranch = Branches::find()
                ->select(['id'])
                ->where(['id' => 1]);

// Combine the two queries using the union() method
            $branches = (new Query())
                ->select(['id'])
                ->from(['u' => $queryBranches->union($queryMainBranch)]);

            $query = Devices::find()
                ->where(['view_status' => Devices::damaged])
                ->andWhere(['in','branch' , $branches]);

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
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
        else {

            $query = Devices::find()
                ->where(['view_status' => Devices::damaged])
                ->andWhere(['branch' => Yii::$app->user->identity->branch]);

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
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

    function DamagedSearchresults($params)
    {
        if (\Yii::$app->user->identity->branch == 1) {


            $query = Devices::find();

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
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
                ->andFilterWhere(['like', 'remark', $this->remark]);

            $query->andFilterWhere([
                'view_status' => Devices::damaged
            ]);

            $queryBranches = Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0]);

// Query to retrieve the main branch with id = 1
            $queryMainBranch = Branches::find()
                ->select(['id'])
                ->where(['id' => 1]);

// Combine the two queries using the union() method
            $branches = (new Query())
                ->select(['id'])
                ->from(['u' => $queryBranches->union($queryMainBranch)]);

            $query->andFilterWhere([
                'in', 'branch', $branches
            ]);

            return $dataProvider;

        } else {
            $query = Devices::find();


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
                ->andFilterWhere(['like', 'remark', $this->remark]);

            $query->andFilterWhere([
                'view_status' => Devices::damaged
            ]);

            $query->andFilterWhere([
                'branch' => Yii::$app->user->identity->branch
            ]);

            return $dataProvider;
        }
    }



    public
    function searchIntransitDevice($params)
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
                ->andFilterWhere(['view_status' => self::stock_devices])
                ->andFilterWhere(['status' => StockDevices::not_deactivated])
                ->andFilterWhere(['branch' => \Yii::$app->user->identity->branch]);
            return $dataProvider;

        } else {
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
                ->andFilterWhere(['view_status' => self::stock_devices])
                ->andFilterWhere(['status' => StockDevices::not_deactivated])
                ->andFilterWhere(['branch' => \Yii::$app->user->identity->branch]);
            return $dataProvider;
        }
    }

    public
    function searchAvailable($params)
    {
        if (\Yii::$app->user->identity->user_type == User::PARTNER) {
            $query = Devices::find()
                ->where(['view_status' => self::stock_devices])
                ->andWhere(['status' => StockDevices::available]);


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
        } else {
            $query = Devices::find()
                ->where(['branch' => \Yii::$app->user->identity->branch])
                ->andWhere(['view_status' => self::stock_devices])
                ->andWhere(['status' => StockDevices::available]);
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

    public
    function available($params)
    {

        if (Yii::$app->user->identity->branch == 1) {

            $query = Devices::find();

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
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
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                //  'view_status' => $this->view_status,
            ]);

            //¬  $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
            $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark]);


            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }

            $query->andFilterWhere([
                'view_status' => Devices::awaiting_allocation
            ]);


            $queryBranches = Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0]);

// Query to retrieve the main branch with id = 1
            $queryMainBranch = Branches::find()
                ->select(['id'])
                ->where(['id' => 1]);

// Combine the two queries using the union() method
            $branches = (new Query())
                ->select(['id'])
                ->from(['u' => $queryBranches->union($queryMainBranch)]);

            $query->andFilterWhere([
                'in', 'branch', $branches
            ]);

            return $dataProvider;


        } else {


            $query = Devices::find();

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
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
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                //  'view_status' => $this->view_status,
            ]);

            //¬  $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
            $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark]);


            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }

            $query->andFilterWhere([
                'view_status' => Devices::awaiting_allocation
            ]);

            $query->andFilterWhere([
                'branch' => Yii::$app->user->identity->branch
            ]);

            return $dataProvider;

        }

    }


    public
    function released($params)
    {

        if (Yii::$app->user->identity->branch ==1){


            $queryBranches = Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0]);

// Query to retrieve the main branch with id = 1
            $queryMainBranch = Branches::find()
                ->select(['id'])
                ->where(['id' => 1]);

// Combine the two queries using the union() method
            $branches = (new Query())
                ->select(['id'])
                ->from(['u' => $queryBranches->union($queryMainBranch)]);


            $query = Devices::find()
                ->where(['view_status' => Devices::released])
                ->andWhere(['in','branch',$branches]);

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
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
        else {

            $query = Devices::find()
                ->where(['view_status' => Devices::released])
                ->andWhere(['branch' => Yii::$app->user->identity->branch]);

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
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


    public
    function releasedSearch($params)
    {

        if (Yii::$app->user->identity->branch == 1) {


            $query = Devices::find();

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
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
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                //  'view_status' => $this->view_status,
            ]);

            //¬  $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
            $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark]);


            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }

            $query->andFilterWhere([
                'view_status' => Devices::released
            ]);


            $queryBranches = Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0]);

// Query to retrieve the main branch with id = 1
            $queryMainBranch = Branches::find()
                ->select(['id'])
                ->where(['id' => 1]);

// Combine the two queries using the union() method
            $branches = (new Query())
                ->select(['id'])
                ->from(['u' => $queryBranches->union($queryMainBranch)]);

            $query->andFilterWhere([
                'in', 'branch', $branches
            ]);

            return $dataProvider;


        } else {


            $query = Devices::find();

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
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
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                //  'view_status' => $this->view_status,
            ]);

            //¬  $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
            $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark]);


            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }

            $query->andFilterWhere([
                'view_status' => Devices::released
            ]);

            $query->andFilterWhere([
                'branch' => Yii::$app->user->identity->branch
            ]);

            return $dataProvider;

        }

    }


    public
    function searchRoad($params)
    {
        if (\Yii::$app->user->identity->branch == 1) {


            $query = Devices::find();

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
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
                ->andFilterWhere(['like', 'remark', $this->remark]);

            $query->andFilterWhere([
                'view_status' => Devices::on_road
            ]);

            $queryBranches = Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0]);

// Query to retrieve the main branch with id = 1
            $queryMainBranch = Branches::find()
                ->select(['id'])
                ->where(['id' => 1]);

// Combine the two queries using the union() method
            $branches = (new Query())
                ->select(['id'])
                ->from(['u' => $queryBranches->union($queryMainBranch)]);

            $query->andFilterWhere([
                'in', 'branch', $branches
            ]);

            return $dataProvider;

        } else {
            $query = Devices::find();


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
                ->andFilterWhere(['like', 'remark', $this->remark]);

            $query->andFilterWhere([
                'view_status' => Devices::on_road
            ]);

            $query->andFilterWhere([
                'branch' => Yii::$app->user->identity->branch
            ]);

            return $dataProvider;
        }
    }
    function searchBorderReturnResults($params)
    {
        if (\Yii::$app->user->identity->branch == 1) {


            $query = Devices::find();

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
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
                ->andFilterWhere(['like', 'remark', $this->remark]);

            $query->andFilterWhere([
                'view_status' => Devices::return_to_office
            ]);

            $queryBranches = Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0]);

// Query to retrieve the main branch with id = 1
            $queryMainBranch = Branches::find()
                ->select(['id'])
                ->where(['id' => 1]);

// Combine the two queries using the union() method
            $branches = (new Query())
                ->select(['id'])
                ->from(['u' => $queryBranches->union($queryMainBranch)]);

            $query->andFilterWhere([
                'in', 'branch', $branches
            ]);

            return $dataProvider;

        } else {
            $query = Devices::find();


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
                ->andFilterWhere(['like', 'remark', $this->remark]);

            $query->andFilterWhere([
                'view_status' => Devices::return_to_office
            ]);

            $query->andFilterWhere([
                'branch' => Yii::$app->user->identity->branch
            ]);

            return $dataProvider;
        }
    }
    function searchBorderReceivedResults($params)
    {
        if (\Yii::$app->user->identity->branch == 1) {


            $query = Devices::find();

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
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
                ->andFilterWhere(['like', 'remark', $this->remark]);

            $query->andFilterWhere([
                'view_status' => Devices::border_received
            ]);

            $queryBranches = Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0]);

// Query to retrieve the main branch with id = 1
            $queryMainBranch = Branches::find()
                ->select(['id'])
                ->where(['id' => 1]);

// Combine the two queries using the union() method
            $branches = (new Query())
                ->select(['id'])
                ->from(['u' => $queryBranches->union($queryMainBranch)]);

            $query->andFilterWhere([
                'in', 'branch', $branches
            ]);

            return $dataProvider;

        } else {
            $query = Devices::find();


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
                ->andFilterWhere(['like', 'remark', $this->remark]);

            $query->andFilterWhere([
                'view_status' => Devices::border_received
            ]);

            $query->andFilterWhere([
                'branch' => Yii::$app->user->identity->branch
            ]);

            return $dataProvider;
        }
    }

    function searchIntransitResults($params)
    {
        if (\Yii::$app->user->identity->branch == 1) {

            $query = Devices::find();

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
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
                ->andFilterWhere(['like', 'remark', $this->remark]);

            $query->andFilterWhere([
                'view_status' => Devices::in_transit
            ]);

            $queryBranches = Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0]);

// Query to retrieve the main branch with id = 1
            $queryMainBranch = Branches::find()
                ->select(['id'])
                ->where(['id' => 1]);

// Combine the two queries using the union() method
            $branches = (new Query())
                ->select(['id'])
                ->from(['u' => $queryBranches->union($queryMainBranch)]);

            $query->andFilterWhere([
                'in', 'branch', $branches
            ]);

            return $dataProvider;

        } else {
            $query = Devices::find();


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
                ->andFilterWhere(['like', 'remark', $this->remark]);

            $query->andFilterWhere([
                'view_status' => Devices::in_transit
            ]);

            $query->andFilterWhere([
                'branch' => Yii::$app->user->identity->branch
            ]);

            return $dataProvider;
        }
    }

    public
    function onRoad($params)
    {

        if (Yii::$app->user->identity->branch ==1){


            $queryBranches = Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0]);

// Query to retrieve the main branch with id = 1
            $queryMainBranch = Branches::find()
                ->select(['id'])
                ->where(['id' => 1]);

// Combine the two queries using the union() method
            $branches = (new Query())
                ->select(['id'])
                ->from(['u' => $queryBranches->union($queryMainBranch)]);


            $query = Devices::find()
                ->where(['view_status' => self::on_road])
                ->andWhere(['in','branch', $branches]);

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 300],
                'sort' => [
                    'defaultOrder' => [
                        'created_at' => SORT_ASC,
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
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
              //  'branch' => $this->branch,
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
            ->where(['view_status' => self::on_road])
            ->andWhere(['branch' => Yii::$app->user->identity->branch]);


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 300],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_ASC,
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
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'status' => $this->status,
          //  'branch' => $this->branch,
            'type' => $this->type,
            'view_status' => $this->view_status,
        ]);

        $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
            ->andFilterWhere(['like', 'sim_card', $this->sim_card])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;

    }}

    public
    function onRoad1to7($params)
    {

        if (Yii::$app->user->identity->branch ==1){

            $queryBranches = Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0]);

// Query to retrieve the main branch with id = 1
            $queryMainBranch = Branches::find()
                ->select(['id'])
                ->where(['id' => 1]);

// Combine the two queries using the union() method
            $branches = (new Query())
                ->select(['id'])
                ->from(['u' => $queryBranches->union($queryMainBranch)]);


            $date = date('Y-m-d');
            $endDate = date("Y-m-d", strtotime("$date -7 day"));


            $query = Devices::find()
                ->where(['view_status' => self::on_road])
                ->andWhere(['>', 'date(created_at)', $endDate])
                ->andWhere(['in','branch',$branches]);


            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
                'sort' => [
                    'defaultOrder' => [
                        'created_at' => SORT_ASC,
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
        else {


            $date = date('Y-m-d');
            $endDate = date("Y-m-d", strtotime("$date -7 day"));

            $query = Devices::find()
                ->where(['view_status' => self::on_road])
                ->andWhere(['>', 'date(created_at)', $endDate])
                ->andWhere(['branch' => Yii::$app->user->identity->branch]);


            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
                'sort' => [
                    'defaultOrder' => [
                        'created_at' => SORT_ASC,
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

    public
    function onRoad8to14($params)
    {


        if (Yii::$app->user->identity->branch ==1){
            $queryBranches = Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0]);

// Query to retrieve the main branch with id = 1
            $queryMainBranch = Branches::find()
                ->select(['id'])
                ->where(['id' => 1]);

// Combine the two queries using the union() method
            $branches = (new Query())
                ->select(['id'])
                ->from(['u' => $queryBranches->union($queryMainBranch)]);


            $date = date('Y-m-d');
            $endDate = date("Y-m-d", strtotime("$date -8 day"));
            $startDate = date("Y-m-d", strtotime("$date -14 day"));


            $query = Devices::find()
                ->where(['view_status' => self::on_road])
                ->andWhere(['between', 'date(created_at)', $startDate, $endDate])
                ->andWhere(['in','branch' ,$branches]);


            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
                'sort' => [
                    'defaultOrder' => [
                        'created_at' => SORT_ASC,
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
        else {


            $date = date('Y-m-d');
            $endDate = date("Y-m-d", strtotime("$date -8 day"));
            $startDate = date("Y-m-d", strtotime("$date -14 day"));


            $query = Devices::find()
                ->where(['view_status' => self::on_road])
                ->andWhere(['between', 'date(created_at)', $startDate, $endDate])
                ->andWhere(['branch' => Yii::$app->user->identity->branch]);


            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
                'sort' => [
                    'defaultOrder' => [
                        'created_at' => SORT_ASC,
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

    public
    function onRoadAbove14($params)
    {

        if (Yii::$app->user->identity->branch ==1){

            $queryBranches = Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0]);

// Query to retrieve the main branch with id = 1
            $queryMainBranch = Branches::find()
                ->select(['id'])
                ->where(['id' => 1]);

// Combine the two queries using the union() method
            $branches = (new Query())
                ->select(['id'])
                ->from(['u' => $queryBranches->union($queryMainBranch)]);


            $date = date('Y-m-d');
            $endDate = date("Y-m-d", strtotime("$date -14 day"));


            $query = Devices::find()
                ->where(['view_status' => self::on_road])
                ->andWhere(['<', 'date(created_at)', $endDate])
                ->andWhere(['in','branch',$branches]);

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
                'sort' => [
                    'defaultOrder' => [
                        'created_at' => SORT_ASC,
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
        else {


            $date = date('Y-m-d');
            $endDate = date("Y-m-d", strtotime("$date -14 day"));

            $query = Devices::find()
                ->where(['view_status' => self::on_road])
                ->andWhere(['<', 'date(created_at)', $endDate])
                ->andWhere(['branch' => Yii::$app->user->identity->branch]);


            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
                'sort' => [
                    'defaultOrder' => [
                        'created_at' => SORT_ASC,
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

    }     public
    function intransitAbove3($params)
    {

        if (Yii::$app->user->identity->branch ==1){

            $queryBranches = Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0]);

// Query to retrieve the main branch with id = 1
            $queryMainBranch = Branches::find()
                ->select(['id'])
                ->where(['id' => 1]);

// Combine the two queries using the union() method
            $branches = (new Query())
                ->select(['id'])
                ->from(['u' => $queryBranches->union($queryMainBranch)]);


            $date = date('Y-m-d');
            $endDate = date("Y-m-d", strtotime("$date -3 day"));


            $query = Devices::find()
                ->where(['view_status' => self::in_transit])
                ->andWhere(['<', 'date(created_at)', $endDate])
                ->andWhere(['in','branch',$branches]);

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
                'sort' => [
                    'defaultOrder' => [
                        'created_at' => SORT_ASC,
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
        else {


            $date = date('Y-m-d');
            $endDate = date("Y-m-d", strtotime("$date -3 day"));

            $query = Devices::find()
                ->where(['view_status' => self::in_transit])
                ->andWhere(['<', 'date(created_at)', $endDate])
                ->andWhere(['branch' => Yii::$app->user->identity->branch]);


            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
                'sort' => [
                    'defaultOrder' => [
                        'created_at' => SORT_ASC,
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

    public
    function onRoadAbove7days($params)
    {

        if (Yii::$app->user->identity->branch ==1){

            $queryBranches = Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0]);

// Query to retrieve the main branch with id = 1
            $queryMainBranch = Branches::find()
                ->select(['id'])
                ->where(['id' => 1]);

// Combine the two queries using the union() method
            $branches = (new Query())
                ->select(['id'])
                ->from(['u' => $queryBranches->union($queryMainBranch)]);


            $date = date('Y-m-d');
            $endDate = date("Y-m-d", strtotime("$date -7 day"));


            $query = Devices::find()
                ->where(['view_status' => self::on_road])
                ->andWhere(['<', 'date(created_at)', $endDate])
                ->andWhere(['in','branch',$branches]);

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
                'sort' => [
                    'defaultOrder' => [
                        'created_at' => SORT_ASC,
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
        else {


            $date = date('Y-m-d');
            $endDate = date("Y-m-d", strtotime("$date -7 day"));

            $query = Devices::find()
                ->where(['view_status' => self::on_road])
                ->andWhere(['<', 'date(created_at)', $endDate])
                ->andWhere(['branch' => Yii::$app->user->identity->branch]);


            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
                'sort' => [
                    'defaultOrder' => [
                        'created_at' => SORT_ASC,
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

    public
    function searchBorderReturn($params)
    {


        if (Yii::$app->user->identity->branch ==1){


            $queryBranches = Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0]);

// Query to retrieve the main branch with id = 1
            $queryMainBranch = Branches::find()
                ->select(['id'])
                ->where(['id' => 1]);

// Combine the two queries using the union() method
            $branches = (new Query())
                ->select(['id'])
                ->from(['u' => $queryBranches->union($queryMainBranch)]);

            $query = Devices::find()
                ->where(['view_status' => Devices::return_to_office])
                ->andWhere(['in','branch' , $branches]);

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
                'sort' => [
                    'defaultOrder' => [
                        'created_at' => SORT_ASC,
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
        else {

            $query = Devices::find()
                ->where(['view_status' => Devices::return_to_office])
                ->andWhere(['branch' => Yii::$app->user->identity->branch]);

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
                'sort' => [
                    'defaultOrder' => [
                        'created_at' => SORT_ASC,
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

    public
    function searchBorderReceived($params)
    {


        if (Yii::$app->user->identity->branch ==1){


            $queryBranches = Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0]);

// Query to retrieve the main branch with id = 1
            $queryMainBranch = Branches::find()
                ->select(['id'])
                ->where(['id' => 1]);

// Combine the two queries using the union() method
            $branches = (new Query())
                ->select(['id'])
                ->from(['u' => $queryBranches->union($queryMainBranch)]);

            $query = Devices::find()
                ->where(['view_status' => Devices::border_received])
                ->andWhere(['in','branch' , $branches]);

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
                'sort' => [
                    'defaultOrder' => [
                        'created_at' => SORT_ASC,
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
        else {

            $query = Devices::find()
                ->where(['view_status' => Devices::border_received])
                ->andWhere(['branch' => Yii::$app->user->identity->branch]);

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
                'sort' => [
                    'defaultOrder' => [
                        'created_at' => SORT_ASC,
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

    public
    function Fault($params)
    {


        if (Yii::$app->user->identity->branch ==1){


            $queryBranches = Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0]);

// Query to retrieve the main branch with id = 1
            $queryMainBranch = Branches::find()
                ->select(['id'])
                ->where(['id' => 1]);

// Combine the two queries using the union() method
            $branches = (new Query())
                ->select(['id'])
                ->from(['u' => $queryBranches->union($queryMainBranch)]);

            $query = Devices::find()
                ->where(['view_status' => Devices::fault_devices])
                ->andWhere(['in','branch' , $branches]);

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
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
        else {

            $query = Devices::find()
                ->where(['view_status' => Devices::fault_devices])
                ->andWhere(['branch' => Yii::$app->user->identity->branch]);

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
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

    function FaultSearch($params)
    {
        if (\Yii::$app->user->identity->branch == 1) {


            $query = Devices::find();

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
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
                ->andFilterWhere(['like', 'remark', $this->remark]);

            $query->andFilterWhere([
                'view_status' => Devices::fault_devices
            ]);

            $queryBranches = Branches::find()
                ->select(['id'])
                ->where(['branch_type' => 0]);

// Query to retrieve the main branch with id = 1
            $queryMainBranch = Branches::find()
                ->select(['id'])
                ->where(['id' => 1]);

// Combine the two queries using the union() method
            $branches = (new Query())
                ->select(['id'])
                ->from(['u' => $queryBranches->union($queryMainBranch)]);

            $query->andFilterWhere([
                'in', 'branch', $branches
            ]);

            return $dataProvider;

        } else {
            $query = Devices::find();


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
                ->andFilterWhere(['like', 'remark', $this->remark]);

            $query->andFilterWhere([
                'view_status' => Devices::fault_devices
            ]);

            $query->andFilterWhere([
                'branch' => Yii::$app->user->identity->branch
            ]);

            return $dataProvider;
        }
    }



}
