<?php

namespace admin\controllers\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\CompanyDomain;

/**
 * CompanyDomainSearch represents the model behind the search form about `common\models\CompanyDomain`.
 */
class CompanyDomainSearch extends CompanyDomain
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'company_id'], 'integer'],
            [['domain', 'record_type', 'create_on', 'last_update'], 'safe'],
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
        $query = CompanyDomain::find()->where("company_domain.record_type <> 'Deleted'");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'company_id' => [
                    'asc' => ['company.name' => SORT_ASC],
                    'desc' => ['company.name' => SORT_DESC],
                ],
                'record_type',
                'domain',
            ],
            'defaultOrder' => ['id' => SORT_ASC]
        ]);
        
        $this->load($params);
        
        $query->join('LEFT JOIN', 'company', 'company_domain.company_id = company.id');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'company_domain.id' => $this->id,
            'company_id' => $this->company_id,
        ]);

        $query->andFilterWhere(['like', 'domain', $this->domain])
            ->andFilterWhere(['=', 'company_domain.record_type', $this->record_type]);

        return $dataProvider;
    }
}
