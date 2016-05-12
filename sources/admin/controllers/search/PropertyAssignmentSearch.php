<?php

namespace admin\Controllers\Search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PropertyAssignment;

/**
 * PropertyAssignmentSearch represents the model behind the search form about `common\models\PropertyAssignment`.
 */
class PropertyAssignmentSearch extends PropertyAssignment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'client_id', 'restaurant_chain_id', 'restaurant_group_id', 'restaurant_id', 'max_delivery_order_value', 'min_delivery_order_value', 'max_delivery_order_amount', 'min_delivery_order_amount', 'max_collection_order_value', 'min_collection_order_value', 'max_collection_order_amount', 'min_collection_order_amount'], 'integer'],
            [['record_type', 'create_on', 'last_update'], 'safe'],
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
        $query = PropertyAssignment::find()->where("record_type <> 'Deleted'");

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
            'client_id' => $this->client_id,
            'restaurant_chain_id' => $this->restaurant_chain_id,
            'restaurant_group_id' => $this->restaurant_group_id,
            'restaurant_id' => $this->restaurant_id,
            'max_delivery_order_value' => $this->max_delivery_order_value,
            'min_delivery_order_value' => $this->min_delivery_order_value,
            'max_delivery_order_amount' => $this->max_delivery_order_amount,
            'min_delivery_order_amount' => $this->min_delivery_order_amount,
            'max_collection_order_value' => $this->max_collection_order_value,
            'min_collection_order_value' => $this->min_collection_order_value,
            'max_collection_order_amount' => $this->max_collection_order_amount,
            'min_collection_order_amount' => $this->min_collection_order_amount,
            'create_on' => $this->create_on,
            'last_update' => $this->last_update,
        ]);

        $query->andFilterWhere(['=', 'record_type', $this->record_type]);

        return $dataProvider;
    }
}
