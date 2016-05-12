<?php

namespace admin\controllers\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ExpenseTypeSchedule;

/**
 * ExpenseTypeScheduleSearch represents the model behind the search form about `common\models\ExpenseTypeSchedule`.
 */
class ExpenseTypeScheduleSearch extends ExpenseTypeSchedule
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'schedule_id', 'expense_type_id'], 'integer'],
            [['day', 'record_type', 'create_on', 'last_update'], 'safe'],
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
        $query = ExpenseTypeSchedule::find()->where("expense_type_schedule.record_type <> 'Deleted'");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'day',
                'expense_type_id' => [
                    'asc' => ['expense_type.name' => SORT_ASC],
                    'desc' => ['expense_type.name' => SORT_DESC],
                ],
                'record_type',
            ],
            'defaultOrder' => ['id' => SORT_ASC]
        ]);
        
        $this->load($params);
        
        $query->join('LEFT JOIN', 'expense_type', 'expense_type_schedule.expense_type_id = expense_type.id');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'expense_type_schedule.id' => $this->id,
            'schedule_id' => $this->schedule_id,
            'expense_type_id' => $this->expense_type_id,
        ]);

        $query->andFilterWhere(['like', 'day', $this->day])
            ->andFilterWhere(['=', 'expense_type_schedule.record_type', $this->record_type]);

        return $dataProvider;
    }
}
