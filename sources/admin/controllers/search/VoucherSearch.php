<?php

namespace admin\controllers\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Voucher;

/**
 * VoucherSearch represents the model behind the search form about `common\models\Voucher`.
 */
class VoucherSearch extends Voucher
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'client_id', 'restaurant_id', 'restaurant_chain_id', 'restaurant_group_id', 'item_quantity', 'max_times_per_user'], 'integer'],
            [['code', 'category', 'start_date', 'end_date', 'value_type', 'description', 'generate_by', 'record_type', 'create_on', 'last_update'], 'safe'],
            [['discount_value', 'price_value'], 'number'],
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
        $query = Voucher::find()->where("record_type <> 'Deleted'");
        $query->andFilterWhere(['=', 'client_id', Yii::$app->request->getImpersonatedClientId()]);

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
            'user_id' => $this->user_id,
            'client_id' => $this->client_id,
            'restaurant_id' => $this->restaurant_id,
            'restaurant_chain_id' => $this->restaurant_chain_id,
            'restaurant_group_id' => $this->restaurant_group_id,
            'discount_value' => $this->discount_value,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'price_value' => $this->price_value,
            'item_quantity' => $this->item_quantity,
            'max_times_per_user' => $this->max_times_per_user,
            'create_on' => $this->create_on,
            'last_update' => $this->last_update,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'value_type', $this->value_type])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'generate_by', $this->generate_by])
            ->andFilterWhere(['=', 'record_type', $this->record_type]);

        return $dataProvider;
    }
}
