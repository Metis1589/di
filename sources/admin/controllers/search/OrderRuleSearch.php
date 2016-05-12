<?php

namespace admin\controllers\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\OrderRule;

/**
 * OrderRuleSearch represents the model behind the search form about `common\models\OrderRule`.
 */
class OrderRuleSearch extends OrderRule
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'client_id', 'custom_field_id'], 'integer'],
            [['delivery_type', 'value', 'message_key', 'record_type', 'create_on', 'last_update'], 'safe'],
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
        $query = OrderRule::find()
            //->joinWith('client')
            ->where("order_rule.record_type <> 'Deleted'")->andWhere(['client_id' => Yii::$app->request->getImpersonatedClientId()]);

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
            'custom_field_id' => $this->custom_field_id,
            'create_on' => $this->create_on,
            'last_update' => $this->last_update,
        ]);

        $query->andFilterWhere(['like', 'delivery_type', $this->delivery_type])
            ->andFilterWhere(['like', 'value', $this->value])
            ->andFilterWhere(['like', 'message_key', $this->message_key])
            ->andFilterWhere(['=', 'record_type', $this->record_type]);

        return $dataProvider;
    }
}
