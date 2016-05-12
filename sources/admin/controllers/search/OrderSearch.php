<?php

namespace admin\Controllers\Search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Order;

/**
 * OrderSearch represents the model behind the search form about `common\models\Order`.
 */
class OrderSearch extends Order
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'restaurant_id', 'user_id', 'is_utensils', 'is_amend', 'is_term_cond', 'is_term_cond_acc_pol', 'is_subsribe_own', 'is_subsribe_other', 'is_in_dispatch'], 'integer'],
            [['order_number', 'postcode', 'delivery_type', 'later_date', 'member_comment', 'reataurant_comment', 'delivery_address_data', 'billing_address_data', 'status', 'voucher_data', 'currency_code', 'estimated_time', 'record_type', 'create_on', 'last_update'], 'safe'],
            [['delivery_charge', 'driver_charge', 'subtotal', 'discount_value', 'total', 'refund_amount', 'restaurant_subtotal', 'restaurant_discount_value', 'restaurant_total', 'restaurant_refund_amount', 'payment_charge'], 'number'],
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
        $query = Order::find()->where("record_type <> 'Deleted'");

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
            'later_date' => $this->later_date,
            'is_utensils' => $this->is_utensils,
            'is_amend' => $this->is_amend,
            'is_term_cond' => $this->is_term_cond,
            'is_term_cond_acc_pol' => $this->is_term_cond_acc_pol,
            'is_subsribe_own' => $this->is_subsribe_own,
            'is_subsribe_other' => $this->is_subsribe_other,
            'is_in_dispatch' => $this->is_in_dispatch,
            'delivery_charge' => $this->delivery_charge,
            'driver_charge' => $this->driver_charge,
            'subtotal' => $this->subtotal,
            'discount_value' => $this->discount_value,
            'total' => $this->total,
            'refund_amount' => $this->refund_amount,
            'restaurant_subtotal' => $this->restaurant_subtotal,
            'restaurant_discount_value' => $this->restaurant_discount_value,
            'restaurant_total' => $this->restaurant_total,
            'restaurant_refund_amount' => $this->restaurant_refund_amount,
            'payment_charge' => $this->payment_charge,
            'estimated_time' => $this->estimated_time,
            'create_on' => $this->create_on,
            'last_update' => $this->last_update,
        ]);

        $query->andFilterWhere(['like', 'order_number', $this->order_number])
            ->andFilterWhere(['like', 'postcode', $this->postcode])
            ->andFilterWhere(['like', 'delivery_type', $this->delivery_type])
            ->andFilterWhere(['like', 'member_comment', $this->member_comment])
            ->andFilterWhere(['like', 'reataurant_comment', $this->reataurant_comment])
            ->andFilterWhere(['like', 'delivery_address_data', $this->delivery_address_data])
            ->andFilterWhere(['like', 'billing_address_data', $this->billing_address_data])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'voucher_data', $this->voucher_data])
            ->andFilterWhere(['like', 'currency_code', $this->currency_code])
            ->andFilterWhere(['=', 'record_type', $this->record_type]);

        return $dataProvider;
    }
}
