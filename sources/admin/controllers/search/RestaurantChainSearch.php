<?php

namespace admin\controllers\search;

use common\enums\RecordType;
use common\enums\UserType;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\RestaurantChain;

/**
 * RestaurantChainSearch represents the model behind the search form about `common\models\RestaurantChain`.
 */
class RestaurantChainSearch extends RestaurantChain
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'client_id'], 'integer','message' => Yii::t('error', 'id is invalid')],
            [['name_key', 'record_type', 'client_name'], 'safe'],
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
        $query = RestaurantChain::find() -> where("restaurant_chain.record_type <> '".RecordType::Deleted ."'");
        $query->andFilterWhere(['=', 'restaurant_chain.client_id', Yii::$app->request->getImpersonatedClientId()]);

        if (Yii::$app->user->identity->user_type == UserType::RestaurantChainAdmin) {
            $query->andWhere(['=', 'restaurant_chain.id', Yii::$app->user->identity->restaurant_chain_id]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
             'attributes' => [
                'id',
                'name_key' => [
                    'asc' => ['label_language.value' => SORT_ASC],
                    'desc' => ['label_language.value' => SORT_DESC],
                ],
                'record_type',
                'client_id' =>[
                    'asc' => ['client_name' => SORT_ASC],
                    'desc' => ['client_name' => SORT_DESC]
                ]
            ],
            'defaultOrder' => ['id' => SORT_ASC]
        ]);
        
        $this->load($params);

        $query->join('LEFT JOIN', 'client', 'restaurant_chain.client_id = client.id');
        $query->joinWith('labels')->joinWith('labels.labelLanguage')->
            select(['restaurant_chain.*', 'client.name AS client_name']);
        
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        

        $query->andFilterWhere([
            'restaurant_chain.id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'label_language.value', $this->name_key])
            ->andFilterWhere(['=', 'restaurant_chain.record_type', $this->record_type])
            ->andFilterWhere(['=', 'client.id', $this->client_id]);

        return $dataProvider;
    }
}
