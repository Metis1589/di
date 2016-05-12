<?php

namespace admin\controllers\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\CompanyUserGroup;

/**
 * CompanyUserGroupSearch represents the model behind the search form about `\common\models\CompanyUserGroup`.
 */
class CompanyUserGroupSearch extends CompanyUserGroup
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'company_id'], 'integer','message' => Yii::t('error', 'id is invalid')],
            [['name', 'company_name', 'record_type', 'create_on', 'last_update'], 'safe'],
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
        $query = CompanyUserGroup::find()->where("company_user_group.record_type <> 'Deleted'");

        $dataProvider = new ActiveDataProvider([
            'query' => $query, 
       ]);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'name',
                'record_type',
                'company_id' => [
                    'asc' => ['company_name' => SORT_ASC],
                    'desc' => ['company_name' => SORT_DESC],
                    'label' => 'Company Name'
                ],
            ],
            'defaultOrder' => ['id' => SORT_ASC]
        ]);
        
        $this->load($params);

        $query->join('LEFT JOIN', 'company', 'company_user_group.company_id = company.id')->
            select(['company_user_group.*', 'company.name AS company_name']);
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        $query->andFilterWhere([
            'company_user_group.id' => $this->id,
            'company_user_group.company_id' => $this->company_id,
        ]);

        $query->andFilterWhere(['like', 'company_user_group.name', $this->name])
            ->andFilterWhere(['=', 'company.id', $this->company_id])
            ->andFilterWhere(['=', 'company_user_group.record_type', $this->record_type]);

        return $dataProvider;
    }
}
