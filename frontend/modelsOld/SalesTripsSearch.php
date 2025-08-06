<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\SalesTrips;

/**
 * SalesTripsSearch represents the model behind the search form of `frontend\models\SalesTrips`.
 */
class SalesTripsSearch extends SalesTrips
{
    public $date_from;
    public $date_to;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'border_id', 'trip_status', 'gate_number', 'sales_person', 'cancelled_by', 'edited_by', 'sale_type', 'sale_id', 'customer_id'], 'integer'],
            [['sale_date', 'tzl', 'date_from', 'date_to', 'start_date_time', 'vehicle_number', 'chasis_number', 'driver_name', 'driver_number', 'currency', 'end_date', 'receipt_number', 'passport', 'container_number', 'vehicle_type_id', 'customer_type_id', 'company_name', 'customer_name', 'agent', 'edited_at', 'date_cancelled', 'serial_no'], 'safe'],
            [['amount'], 'number'],
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
            $devices = Devices::find()
                ->select(['serial_no'])
                ->where(['partiner' => 1])
                ->asArray();

            $query = SalesTrips::find()
                ->where(['in','serial_no',$devices])
                ->andWhere(['branch' => \Yii::$app->user->identity->branch]);

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
                'sort' => [
                    'defaultOrder' => [
                        'sale_date' => SORT_DESC,
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
                'sale_date' => $this->sale_date,
                'start_date_time' => $this->start_date_time,
                'border_id' => $this->border_id,
                'trip_status' => $this->trip_status,
                'serial_no' => $this->serial_no,
                'amount' => $this->amount,
                'gate_number' => $this->gate_number,
                'end_date' => $this->end_date,
                'sales_person' => $this->sales_person,
                'cancelled_by' => $this->cancelled_by,
                'customer_id' => $this->customer_id,
                'edited_by' => $this->edited_by,
                'edited_at' => $this->edited_at,
                'date_cancelled' => $this->date_cancelled,
                'sale_type' => $this->sale_type,
                'sale_id' => $this->sale_id,
            ]);

            $query->andFilterWhere(['like', 'tzl', $this->tzl])
                ->andFilterWhere(['like', 'vehicle_number', $this->vehicle_number])
                ->andFilterWhere(['like', 'chasis_number', $this->chasis_number])
                ->andFilterWhere(['like', 'driver_name', $this->driver_name])
                ->andFilterWhere(['like', 'driver_number', $this->driver_number])
                ->andFilterWhere(['like', 'currency', $this->currency])
                ->andFilterWhere(['=', 'trip_status', $this->trip_status])
                ->andFilterWhere(['like', 'receipt_number', $this->receipt_number])
                ->andFilterWhere(['like', 'passport', $this->passport])
                ->andFilterWhere(['like', 'container_number', $this->container_number])
                ->andFilterWhere(['like', 'vehicle_type_id', $this->vehicle_type_id])
                ->andFilterWhere(['like', 'customer_type_id', $this->customer_type_id])
                ->andFilterWhere(['like', 'company_name', $this->company_name])
                ->andFilterWhere(['=', 'customer_id', $this->customer_id])
                ->andFilterWhere(['like', 'customer_name', $this->customer_name])
                ->andFilterWhere(['like', 'agent', $this->agent])
                //  ->andFilterWhere ( [ '>=' , 'DATE_FORMAT(sale_date,"%Y-%m-%d %H:%i:%s")' , $this->date_from] )
                //  ->andFilterWhere ( [ '<=' , 'DATE_FORMAT(sale_date,"%Y-%m-%d %H:%i:%s")' ,  $this->date_to  ] );

                ->andFilterWhere(['between', 'DATE_FORMAT(sale_date, "%Y-%m-%d %H:%i:%s")', $this->date_from, $this->date_to]);

            $terms = preg_split('/\r\n|\r|\n/', $this->serial_no);
            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }

            return $dataProvider;
        }
        elseif (\Yii::$app->user->identity->user_type == User::BILL_STAFF) {

            $query = SalesTrips::find()
                ->where(['branch' => \Yii::$app->user->identity->branch])
                ->andWhere(['customer_id'=>\Yii::$app->user->identity->getId()])
                ->andWhere(['trip_status'=>SalesTrips::NORMAL])
                ->andWhere(['>','date(sale_date)','2021-10-31']);

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
                'sort' => [
                    'defaultOrder' => [
                        'sale_date' => SORT_DESC,
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
                'sale_date' => $this->sale_date,
                'start_date_time' => $this->start_date_time,
                'border_id' => $this->border_id,
                'trip_status' => $this->trip_status,
                'serial_no' => $this->serial_no,
                'amount' => $this->amount,
                'gate_number' => $this->gate_number,
                'end_date' => $this->end_date,
                'sales_person' => $this->sales_person,
                'cancelled_by' => $this->cancelled_by,
                'customer_id' => $this->customer_id,
                'edited_by' => $this->edited_by,
                'edited_at' => $this->edited_at,
                'date_cancelled' => $this->date_cancelled,
                'sale_type' => $this->sale_type,
                'sale_id' => $this->sale_id,
            ]);

            $query->andFilterWhere(['like', 'tzl', $this->tzl])
                ->andFilterWhere(['like', 'vehicle_number', $this->vehicle_number])
                ->andFilterWhere(['like', 'chasis_number', $this->chasis_number])
                ->andFilterWhere(['like', 'driver_name', $this->driver_name])
                ->andFilterWhere(['like', 'driver_number', $this->driver_number])
                ->andFilterWhere(['like', 'currency', $this->currency])
                ->andFilterWhere(['=', 'trip_status', $this->trip_status])
                ->andFilterWhere(['like', 'receipt_number', $this->receipt_number])
                ->andFilterWhere(['like', 'passport', $this->passport])
                ->andFilterWhere(['like', 'container_number', $this->container_number])
                ->andFilterWhere(['like', 'vehicle_type_id', $this->vehicle_type_id])
                ->andFilterWhere(['like', 'customer_type_id', $this->customer_type_id])
                ->andFilterWhere(['like', 'company_name', $this->company_name])
                ->andFilterWhere(['=', 'customer_id', $this->customer_id])
                ->andFilterWhere(['like', 'customer_name', $this->customer_name])
                ->andFilterWhere(['like', 'agent', $this->agent])
                //  ->andFilterWhere ( [ '>=' , 'DATE_FORMAT(sale_date,"%Y-%m-%d %H:%i:%s")' , $this->date_from] )
                //  ->andFilterWhere ( [ '<=' , 'DATE_FORMAT(sale_date,"%Y-%m-%d %H:%i:%s")' ,  $this->date_to  ] );

                ->andFilterWhere(['between', 'DATE_FORMAT(sale_date, "%Y-%m-%d %H:%i:%s")', $this->date_from, $this->date_to]);

            $terms = preg_split('/\r\n|\r|\n/', $this->serial_no);
            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }

            return $dataProvider;
        }
        else {

            $query = SalesTrips::find()
                ->where(['branch' => \Yii::$app->user->identity->branch]);

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
                'sale_date' => $this->sale_date,
                'start_date_time' => $this->start_date_time,
                'border_id' => $this->border_id,
                'trip_status' => $this->trip_status,
                'serial_no' => $this->serial_no,
                'amount' => $this->amount,
                'gate_number' => $this->gate_number,
                'end_date' => $this->end_date,
                'sales_person' => $this->sales_person,
                'cancelled_by' => $this->cancelled_by,
                'customer_id' => $this->customer_id,
                'edited_by' => $this->edited_by,
                'edited_at' => $this->edited_at,
                'date_cancelled' => $this->date_cancelled,
                'sale_type' => $this->sale_type,
                'sale_id' => $this->sale_id,
            ]);

            $query->andFilterWhere(['=', 'tzl', $this->tzl])
                ->andFilterWhere(['like', 'vehicle_number', $this->vehicle_number])
                ->andFilterWhere(['like', 'chasis_number', $this->chasis_number])
                ->andFilterWhere(['like', 'driver_name', $this->driver_name])
                ->andFilterWhere(['like', 'driver_number', $this->driver_number])
                ->andFilterWhere(['like', 'currency', $this->currency])
                ->andFilterWhere(['=', 'trip_status', $this->trip_status])
                ->andFilterWhere(['like', 'receipt_number', $this->receipt_number])
                ->andFilterWhere(['like', 'passport', $this->passport])
                ->andFilterWhere(['like', 'container_number', $this->container_number])
                ->andFilterWhere(['like', 'vehicle_type_id', $this->vehicle_type_id])
                ->andFilterWhere(['like', 'customer_type_id', $this->customer_type_id])
                ->andFilterWhere(['like', 'company_name', $this->company_name])
                ->andFilterWhere(['=', 'customer_id', $this->customer_id])
                ->andFilterWhere(['like', 'customer_name', $this->customer_name])
                ->andFilterWhere(['like', 'agent', $this->agent])
                //  ->andFilterWhere ( [ '>=' , 'DATE_FORMAT(sale_date,"%Y-%m-%d %H:%i:%s")' , $this->date_from] )
                //  ->andFilterWhere ( [ '<=' , 'DATE_FORMAT(sale_date,"%Y-%m-%d %H:%i:%s")' ,  $this->date_to  ] );

                ->andFilterWhere(['between', 'DATE_FORMAT(sale_date, "%Y-%m-%d %H:%i:%s")', $this->date_from, $this->date_to]);

            $terms = preg_split('/\r\n|\r|\n/', $this->serial_no);
            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }

            return $dataProvider;
        }
    }
    public function searchTagger($params)
    {
    if (\Yii::$app->user->identity->user_type == User::BILL_STAFF) {

        $currentDate = date('Y-m-d 09:00:00');
        $PreviousDate = date('Y-m-d 09:00:00', strtotime($currentDate .' -1 day'));
        $query = SalesTrips::find()
            ->select(['sales_person', 'count(id) as sold_items', 'date(sale_date) as sale_date', 'sum(amount) as amount'])
            ->where(['branch' => \Yii::$app->user->identity->branch])
            ->andWhere(['between', 'date(sale_date)', $PreviousDate, $currentDate])
            ->andWhere(['sale_type' => 1])
            ->andWhere(['customer_id'=>\Yii::$app->user->identity->getId()])
            ->andWhere(['trip_status'=>SalesTrips::NORMAL])
            ->groupBy('sales_person');

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
            'sale_date' => $this->sale_date,
            'start_date_time' => $this->start_date_time,
            'border_id' => $this->border_id,
            'trip_status' => $this->trip_status,
            'serial_no' => $this->serial_no,
            'amount' => $this->amount,
            'gate_number' => $this->gate_number,
            'end_date' => $this->end_date,
            'sales_person' => $this->sales_person,
            'cancelled_by' => $this->cancelled_by,
            'customer_id' => $this->customer_id,
            'edited_by' => $this->edited_by,
            'edited_at' => $this->edited_at,
            'date_cancelled' => $this->date_cancelled,
            'sale_type' => $this->sale_type,
            'sale_id' => $this->sale_id,
        ]);

        $query->andFilterWhere(['=', 'tzl', $this->tzl])
            ->andFilterWhere(['like', 'vehicle_number', $this->vehicle_number])
            ->andFilterWhere(['like', 'chasis_number', $this->chasis_number])
            ->andFilterWhere(['like', 'driver_name', $this->driver_name])
            ->andFilterWhere(['like', 'driver_number', $this->driver_number])
            ->andFilterWhere(['like', 'currency', $this->currency])
            ->andFilterWhere(['=', 'trip_status', $this->trip_status])
            ->andFilterWhere(['like', 'receipt_number', $this->receipt_number])
            ->andFilterWhere(['like', 'passport', $this->passport])
            ->andFilterWhere(['like', 'container_number', $this->container_number])
            ->andFilterWhere(['like', 'vehicle_type_id', $this->vehicle_type_id])
            ->andFilterWhere(['like', 'customer_type_id', $this->customer_type_id])
            ->andFilterWhere(['like', 'company_name', $this->company_name])
            ->andFilterWhere(['=', 'customer_id', $this->customer_id])
            ->andFilterWhere(['like', 'customer_name', $this->customer_name])
            ->andFilterWhere(['like', 'agent', $this->agent])
            //  ->andFilterWhere ( [ '>=' , 'DATE_FORMAT(sale_date,"%Y-%m-%d %H:%i:%s")' , $this->date_from] )
            //  ->andFilterWhere ( [ '<=' , 'DATE_FORMAT(sale_date,"%Y-%m-%d %H:%i:%s")' ,  $this->date_to  ] );

            ->andFilterWhere(['between', 'DATE_FORMAT(sale_date, "%Y-%m-%d %H:%i:%s")', $this->date_from, $this->date_to]);

        $terms = preg_split('/\r\n|\r|\n/', $this->serial_no);
        $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
        foreach ($terms as $key) {
            $query->orFilterWhere([
                'or',
                ['=', 'serial_no', $key],
            ]);
        }

        return $dataProvider;
    }
    else{

        $currentDate = date('Y-m-d 09:00:00');
        $PreviousDate = date('Y-m-d 09:00:00', strtotime($currentDate .' -1 day'));
        $query = SalesTrips::find()
            ->select(['sales_person', 'count(id) as sold_items', 'date(sale_date) as sale_date', 'sum(amount) as amount'])
            ->where(['branch' => \Yii::$app->user->identity->branch])
            ->andWhere(['between', 'date(sale_date)', $PreviousDate, $currentDate])
            ->andWhere(['sale_type' => 1])
            ->groupBy('sales_person');

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
            'sale_date' => $this->sale_date,
            'start_date_time' => $this->start_date_time,
            'border_id' => $this->border_id,
            'trip_status' => $this->trip_status,
            'serial_no' => $this->serial_no,
            'amount' => $this->amount,
            'gate_number' => $this->gate_number,
            'end_date' => $this->end_date,
            'sales_person' => $this->sales_person,
            'cancelled_by' => $this->cancelled_by,
            'customer_id' => $this->customer_id,
            'edited_by' => $this->edited_by,
            'edited_at' => $this->edited_at,
            'date_cancelled' => $this->date_cancelled,
            'sale_type' => $this->sale_type,
            'sale_id' => $this->sale_id,
        ]);

        $query->andFilterWhere(['=', 'tzl', $this->tzl])
            ->andFilterWhere(['like', 'vehicle_number', $this->vehicle_number])
            ->andFilterWhere(['like', 'chasis_number', $this->chasis_number])
            ->andFilterWhere(['like', 'driver_name', $this->driver_name])
            ->andFilterWhere(['like', 'driver_number', $this->driver_number])
            ->andFilterWhere(['like', 'currency', $this->currency])
            ->andFilterWhere(['=', 'trip_status', $this->trip_status])
            ->andFilterWhere(['like', 'receipt_number', $this->receipt_number])
            ->andFilterWhere(['like', 'passport', $this->passport])
            ->andFilterWhere(['like', 'container_number', $this->container_number])
            ->andFilterWhere(['like', 'vehicle_type_id', $this->vehicle_type_id])
            ->andFilterWhere(['like', 'customer_type_id', $this->customer_type_id])
            ->andFilterWhere(['like', 'company_name', $this->company_name])
            ->andFilterWhere(['=', 'customer_id', $this->customer_id])
            ->andFilterWhere(['like', 'customer_name', $this->customer_name])
            ->andFilterWhere(['like', 'agent', $this->agent])
            //  ->andFilterWhere ( [ '>=' , 'DATE_FORMAT(sale_date,"%Y-%m-%d %H:%i:%s")' , $this->date_from] )
            //  ->andFilterWhere ( [ '<=' , 'DATE_FORMAT(sale_date,"%Y-%m-%d %H:%i:%s")' ,  $this->date_to  ] );

            ->andFilterWhere(['between', 'DATE_FORMAT(sale_date, "%Y-%m-%d %H:%i:%s")', $this->date_from, $this->date_to]);

        $terms = preg_split('/\r\n|\r|\n/', $this->serial_no);
        $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
        foreach ($terms as $key) {
            $query->orFilterWhere([
                'or',
                ['=', 'serial_no', $key],
            ]);
        }

        return $dataProvider;

    }

    }

    public function searchTwoDays($params)
    {
        $query = SalesTrips::find()
            ->where(['branch' => \Yii::$app->user->identity->branch]);

        // add conditions that should always apply here
        $two_days_before = date('Y-m-d', strtotime(' -2 day'));
        $query->where(['>', 'date(sale_date)', $two_days_before]);

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
            'sale_date' => $this->sale_date,
            'start_date_time' => $this->start_date_time,
            'border_id' => $this->border_id,
            'trip_status' => $this->trip_status,
            'serial_no' => $this->serial_no,
            'amount' => $this->amount,
            'gate_number' => $this->gate_number,
            'end_date' => $this->end_date,
            'sales_person' => $this->sales_person,
            'cancelled_by' => $this->cancelled_by,
            'customer_id' => $this->customer_id,
            'edited_by' => $this->edited_by,
            'edited_at' => $this->edited_at,
            'date_cancelled' => $this->date_cancelled,
            'sale_type' => $this->sale_type,
            'sale_id' => $this->sale_id,
        ]);

        $query->andFilterWhere(['=', 'tzl', $this->tzl])
            ->andFilterWhere(['like', 'vehicle_number', $this->vehicle_number])
            ->andFilterWhere(['like', 'chasis_number', $this->chasis_number])
            ->andFilterWhere(['like', 'driver_name', $this->driver_name])
            ->andFilterWhere(['like', 'driver_number', $this->driver_number])
            ->andFilterWhere(['like', 'currency', $this->currency])
            ->andFilterWhere(['like', 'receipt_number', $this->receipt_number])
            ->andFilterWhere(['like', 'passport', $this->passport])
            ->andFilterWhere(['=', 'trip_status', $this->trip_status])
            ->andFilterWhere(['like', 'container_number', $this->container_number])
            ->andFilterWhere(['like', 'vehicle_type_id', $this->vehicle_type_id])
            ->andFilterWhere(['like', 'customer_type_id', $this->customer_type_id])
            ->andFilterWhere(['like', 'company_name', $this->company_name])
            ->andFilterWhere(['=', 'customer_id', $this->customer_id])
            ->andFilterWhere(['like', 'customer_name', $this->customer_name])
            ->andFilterWhere(['like', 'agent', $this->agent])
            ->andFilterWhere(['between', 'DATE_FORMAT(sale_date, "%Y-%m-%d")', $this->date_from, $this->date_to]);

        $terms = preg_split('/\r\n|\r|\n/', $this->serial_no);
        $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
        foreach ($terms as $key) {
            $query->orFilterWhere([
                'or',
                ['=', 'serial_no', $key],
            ]);
        }

        return $dataProvider;
    }

    public function searchDevice($params)
    {
        if (\Yii::$app->user->identity->user_type == User::PARTNER) {
            $devices = Devices::find()
                ->select(['serial_no'])
                ->where(['partiner' => 1])
                ->asArray();

            $query = SalesTrips::find()
                ->select(['serial_no', 'count(id) id',])
                ->where(['in','serial_no',$devices])
                ->andWhere(['branch' => \Yii::$app->user->identity->branch])
                ->andWhere(['trip_status'=>1])
                ->groupBy(['serial_no']);

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
            ]);

            $this->load($params);

            if (!$this->validate()) {
                // uncomment the following line if you do not want to return any records when validation fails
                // $query->where('0=1');
                return $dataProvider;
            }

            // grid filtering conditions
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
                'sort' => [
                    'defaultOrder' => [
                        'id' => SORT_DESC,
                    ],
                ],
            ]);

            $terms = preg_split('/\r\n|\r|\n/', $this->serial_no);
            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }
            $query->andFilterWhere(['=', 'serial_no', $this->tzl])
                ->andFilterWhere(['between', 'DATE_FORMAT(sale_date, "%Y-%m-%d")', $this->date_from, $this->date_to]);

            return $dataProvider;
        }
        else {


            $query = SalesTrips::find()
                ->select(['serial_no', 'count(id) id',])
                ->where(['branch' => \Yii::$app->user->identity->branch])
                ->groupBy(['serial_no']);

            // add conditions that should always apply here

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
            ]);

            $this->load($params);

            if (!$this->validate()) {
                // uncomment the following line if you do not want to return any records when validation fails
                // $query->where('0=1');
                return $dataProvider;
            }

            // grid filtering conditions
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 100],
                'sort' => [
                    'defaultOrder' => [
                        'id' => SORT_DESC,
                    ],
                ],
            ]);

            $terms = preg_split('/\r\n|\r|\n/', $this->serial_no);
            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }
            $query->andFilterWhere(['=', 'serial_no', $this->tzl])
                ->andFilterWhere(['between', 'DATE_FORMAT(sale_date, "%Y-%m-%d")', $this->date_from, $this->date_to]);

            return $dataProvider;
        }
    }
}
