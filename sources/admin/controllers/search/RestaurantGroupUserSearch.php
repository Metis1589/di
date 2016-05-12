<?php

namespace admin\controllers\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\RestaurantGroupUser;

/**
 * RestaurantGroupUserSearch represents the model behind the search form about `common\models\RestaurantGroupUser`.
 */
class RestaurantGroupUserSearch extends RestaurantGroupUser
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'restaurant_group_id'], 'integer'],
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
        $query = RestaurantGroupUser::find()->where("restaurant_group_user.record_type <> 'Deleted'");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
         'attributes' => [
                'user_id' => [
                    'asc' => ['user_name' => SORT_ASC],
                    'desc' => ['user_name' => SORT_DESC],
                ],
                'record_type',
				'id',
                'restaurant_group_id' => [
                    'asc' => ['rg_name' => SORT_ASC],
                    'desc' => ['rg_name' => SORT_DESC],
                ],
            ],
		 'defaultOrder' => ['id' => SORT_ASC]
        ]);
        
        $this->load($params);
        
        $query->join('LEFT JOIN', 'restaurant_group', 'restaurant_group_user.restaurant_group_id = restaurant_group.id');
        $query->join('LEFT JOIN', 'user', 'restaurant_group_user.user_id = user.id');
        
        $query->join('LEFT JOIN', 'label', 'restaurant_group.name_key = label.code');
        $query->join('LEFT JOIN', 'label_language', 'label_language.label_id = label.id AND label_language.language_id = ' . Yii::$app->globalCache->getLanguageId(Yii::$app->language))  
              ->select(['restaurant_group_user.*', 'user.username AS user_name', 'label_language.value AS rg_name']);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'restaurant_group_user.id' => $this->id,
            'user_id' => $this->user_id,
            'restaurant_group_id' => $this->restaurant_group_id,
            'create_on' => $this->create_on,
            'last_update' => $this->last_update,
        ]);

        $query->andFilterWhere(['like', 'role', $this->role])
            ->andFilterWhere(['=', 'restaurant_group_user.record_type', $this->record_type]);

        return $dataProvider;
    }
}
