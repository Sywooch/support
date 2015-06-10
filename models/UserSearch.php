<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * UserSearch represents the model behind the search form about `app\models\User`.
 */
class UserSearch extends User
{
    public $group;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group'], 'safe'],
            [['id', 'group_id'], 'integer'],
            [['login', 'password', 'access_token', 'auth_key', 'name', 'second_name', 'last_name', 'position', 'workplace'], 'safe'],
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
        $query = User::find();
        $query->joinWith(['group']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['group'] = [
            'asc' => [Group::tableName().'.name' => SORT_ASC],
            'desc' => [Group::tableName().'.name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'login', $this->login])
              ->andFilterWhere(['like', 'password', $this->password])
              ->andFilterWhere(['like', 'access_token', $this->access_token])
              ->andFilterWhere(['like', 'auth_key', $this->auth_key])
              ->andFilterWhere(['like', 'name', $this->name])
              ->andFilterWhere(['like', 'second_name', $this->second_name])
              ->andFilterWhere(['like', 'last_name', $this->last_name])
              ->andFilterWhere(['like', 'position', $this->position])
              ->andFilterWhere(['like', 'workplace', $this->workplace])
              ->andFilterWhere(['like', Group::tableName().'.name', $this->group]);

        return $dataProvider;
    }
}
