<?php

namespace admin\controllers\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\RestaurantUser;

/**
 * RestaurantUserSearch represents the model behind the search form about `common\models\RestaurantUser`.
 */
class RestaurantUserSearch extends RestaurantUser
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'restaurant_id', 'user_id'], 'integer'],
            [['role', 'record_type', 'create_on', 'last_update'], 'safe'],
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
        $query = RestaurantUser::find()->with('restaurant','user')->where("record_type <> 'Deleted'");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'defaultOrder' => ['id' => SORT_ASC]
        ]);
        
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'restaurant_id' => $this->restaurant_id,
            'user_id' => $this->user_id,
            'create_on' => $this->create_on,
            'last_update' => $this->last_update,
        ]);

        $query->andFilterWhere(['like', 'role', $this->role])
            ->andFilterWhere(['=', 'record_type', $this->record_type]);

        return $dataProvider;
    }
}
