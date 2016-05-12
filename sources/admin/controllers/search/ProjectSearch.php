<?php

namespace admin\controllers\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Project;

/**
 * ProjectSearch represents the model behind the search form about `common\models\Project`.
 */
class ProjectSearch extends Project
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'company_id', 'user_id'], 'integer'],
            [['code', 'name', 'limit_type', 'record_type', 'create_on', 'last_update'], 'safe'],
            [['daily_limit', 'weekly_limit', 'monthly_limit'], 'number'],
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
        $query = Project::find()->where("project.record_type <> 'Deleted'");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'code',
                'name',
                'limit_type',
                'company_id',
                'user_id' => [
                    'asc' => ['user.username' => SORT_ASC],
                    'desc' => ['user.username' => SORT_DESC],
                ],
                'company_id' => [
                    'asc' => ['company.name' => SORT_ASC],
                    'desc' => ['company.name' => SORT_DESC],
                ],
                'record_type',
            ],
            'defaultOrder' => ['id' => SORT_ASC]
        ]);
        
        $this->load($params);
        
         $query->join('LEFT JOIN', 'user', 'project.user_id = user.id');
         $query->join('LEFT JOIN', 'company', 'project.company_id = company.id');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'project.id' => $this->id,
           // 'daily_limit' => $this->daily_limit,
           // 'weekly_limit' => $this->weekly_limit,
           // 'monthly_limit' => $this->monthly_limit,
            'project.company_id' => $this->company_id,
            'project.user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'project.code', $this->code])
            ->andFilterWhere(['like', 'project.name', $this->name])
            ->andFilterWhere(['like', 'project.limit_type', $this->limit_type])
            ->andFilterWhere(['=', 'project.record_type', $this->record_type]);

        return $dataProvider;
    }
}
