<?php

namespace admin\controllers\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ExpenseType;

/**
 * ExpenseTypeSearch represents the model behind the search form about `common\models\ExpenseType`.
 */
class ExpenseTypeSearch extends ExpenseType
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'client_id', 'is_default'], 'integer', 'message' => Yii::t('error', 'id is invalid')],
            [['name', 'record_type', 'create_on', 'last_update'], 'safe'],
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
        $query = ExpenseType::find()->where("expense_type.record_type <> 'Deleted'");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'client_id' => [
                    'asc' => ['client.name' => SORT_ASC],
                    'desc' => ['client.name' => SORT_DESC],
                ],
                'record_type',
                'name',
            ],
            'defaultOrder' => ['id' => SORT_ASC]
        ]);
        
        $this->load($params);
        
        $query->join('LEFT JOIN', 'client', 'expense_type.client_id = client.id');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'expense_type.id' => $this->id,
            'client_id' => $this->client_id,
          // 'is_default' => $this->is_default,
        ]);

        $query->andFilterWhere(['like', 'expense_type.name', $this->name])
            ->andFilterWhere(['=', 'expense_type.record_type', $this->record_type]);

        return $dataProvider;
    }
}
