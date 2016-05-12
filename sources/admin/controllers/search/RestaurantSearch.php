<?php

namespace admin\controllers\search;

use common\enums\UserType;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Restaurant;

/**
 * RestaurantSearch represents the model behind the search form about `common\models\Restaurant`.
 */
class RestaurantSearch extends Restaurant
{
    public $city;
    public $postcode;
    public $phone;
    public $restaurant_chain_id;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'have_app', 'address_base_id','restaurant_chain_id'], 'integer'],
            [['restaurant_group_id','name', 'slug', 'opening_day', 'seo_title', 'meta_text', 'meta_description','city','postcode','phone', 'record_type', 'create_on', 'last_update'], 'safe'],
            [['is_newest', 'is_featured', 'is_from_signup'], 'boolean'],
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
        $query = Restaurant::find()->where("restaurant.record_type <> 'Deleted'");
        $query->joinWith(['physicalAddress', 'contact', 'restaurantChain']);
        $query->andFilterWhere(['=', 'restaurant.client_id', Yii::$app->request->getImpersonatedClientId()]);

        $user = Yii::$app->user->identity;
        if ($user->user_type == UserType::RestaurantTeam || $user->user_type == UserType::RestaurantAdmin) {
            $query->andWhere(['=', 'restaurant.id', $user->restaurant_id]);
        }

        if ($user->user_type == UserType::RestaurantGroupAdmin ) {
            $query->andWhere(['=', 'restaurant.restaurant_group_id', $user->restaurant_group_id]);
        }

        if ($user->user_type == UserType::RestaurantChainAdmin ) {
            $query->andWhere(['=', 'restaurant_chain.id', $user->restaurant_chain_id]);
        }

        if ($user->user_type == UserType::ClientAdmin ) {
            $query->andWhere(['=', 'restaurant.client_id', $user->client_id]);
        }

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
            'opening_day' => $this->opening_day,
            'is_newest' => $this->is_newest,
            'have_app' => $this->have_app,
            'is_featured' => $this->is_featured,
            'is_from_signup' => $this->is_from_signup,
            'address_base_id' => $this->address_base_id,
            'create_on' => $this->create_on,
            'last_update' => $this->last_update,
        ]);

        if ($this->restaurant_group_id === '0') {
            $query->andWhere('restaurant.restaurant_group_id IS NULL');
        } else {
            $query->andWhere('restaurant.restaurant_group_id IS NOT NULL');
            $query->andFilterWhere([
                'restaurant_group_id' => $this->restaurant_group_id,
            ]);
        }



        $dataProvider->setSort([
            'attributes' => [
                'id',
                'name',
                'address_base_id',
                'city' => [
                    'asc' => ['address.city' => SORT_ASC],
                    'desc' => ['address.city' => SORT_DESC],
                ],
                'postcode' => [
                    'asc' => ['address.postcode' => SORT_ASC],
                    'desc' => ['address.postcode' => SORT_DESC],
                ],
                'phone' => [
                    'asc' => ['restaurant_contact.number' => SORT_ASC],
                    'desc' => ['restaurant_contact.number' => SORT_DESC],
                ],

            ],
            'defaultOrder' => ['id' => SORT_ASC],

        ]);

        $query->andFilterWhere(['like', 'restaurant.name', $this->name])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'seo_title', $this->seo_title])
            ->andFilterWhere(['like', 'meta_text', $this->meta_text])
            ->andFilterWhere(['like', 'meta_description', $this->meta_description])
            ->andFilterWhere(['like', 'address_base_id', $this->address_base_id])
            ->andFilterWhere(['like', 'address.city', $this->city])
            ->andFilterWhere(['like', 'address.postcode', $this->postcode])
            ->andFilterWhere(['like', 'restaurant_contact.number', $this->phone])
            ->andFilterWhere(['=', 'restaurant_group.restaurant_chain_id', $this->restaurant_chain_id])
            ->andFilterWhere(['=', 'restaurant.record_type', $this->record_type]);

        return $dataProvider;
    }
}
