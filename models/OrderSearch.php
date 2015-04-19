<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Order;

/**
 * OrderSearch represents the model behind the search form about `app\models\Order`.
 */
class OrderSearch extends Order
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_sender', 'user_answer', 'priority_id', 'status_id', 'category_id', 'time_hours', 'complexity'], 'integer'],
            [['date_create', 'date_finish', 'date_update', 'date_deadline', 'date_start', 'name', 'description'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = Order::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_sender' => $this->user_sender,
            'user_answer' => $this->user_answer,
            'priority_id' => $this->priority_id,
            'date_create' => $this->date_create,
            'date_finish' => $this->date_finish,
            'date_update' => $this->date_update,
            'date_deadline' => $this->date_deadline,
            'date_start' => $this->date_start,
            'status_id' => $this->status_id,
            'category_id' => $this->category_id,
            'time_hours' => $this->time_hours,
            'complexity' => $this->complexity,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
