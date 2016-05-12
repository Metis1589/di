<?php

namespace admin\controllers\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\RestaurantGroup;

/**
 * RestaurantGroupSearch represents the model behind the search form about `common\models\RestaurantGroup`.
 */
class RestaurantGroupSearch extends RestaurantGroup
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'restaurant_chain_id', 'currency_id'], 'integer', 'message' => Yii::t('error', 'id is invalid')],
            [['name_key', 'record_type', 'create_on', 'last_update'], 'safe'],
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
        $query = RestaurantGroup::find()->where("restaurant_group.record_type <> 'Deleted'");

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
                'currency_id' => [
                    'asc' => ['cur_code' => SORT_ASC],
                    'desc' => ['cur_code' => SORT_DESC],
                ],
                'restaurant_chain_id' =>[
                    'asc' => ['chain_name' => SORT_ASC],
                    'desc' => ['chain_name' => SORT_DESC]
                ]
            ],
            'defaultOrder' => ['id' => SORT_ASC]
        ]);
        
        $this->load($params);
        
        $query->join('LEFT JOIN', 'restaurant_chain', 'restaurant_group.restaurant_chain_id = restaurant_chain.id');
        $query->join('LEFT JOIN', 'currency', 'restaurant_group.currency_id = currency.id');
        $query->joinWith('labels')->joinWith('labels.labelLanguage');
             // , 'currency.code AS currency.code'
      
        $query->join('LEFT JOIN', 'label AS lb_chain', 'restaurant_chain.name_key = lb_chain.code');
        $query->join('LEFT JOIN', 'label_language AS lb_lang', 'lb_lang.label_id = lb_chain.id AND lb_lang.language_id = ' . Yii::$app->globalCache->getLanguageId(Yii::$app->language))
              ->select(['restaurant_group.*', 'lb_lang.value AS chain_name', 'currency.code AS cur_code']);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'restaurant_group.id' => $this->id,
            'restaurant_group.currency_id' => $this->currency_id,
        ]);

        $query->andFilterWhere(['like', 'label_language.value', $this->name_key])
              ->andFilterWhere(['=', 'restaurant_group.record_type', $this->record_type])
              ->andFilterWhere(['=', 'restaurant_chain.id', $this->restaurant_chain_id]);

        return $dataProvider;
    }
}
