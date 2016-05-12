<?php

namespace admin\controllers\search;

use common\enums\RecordType;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Company;

/**
 * CompanySearch represents the model behind the search form about `common\models\Company`.
 */
class CompanySearch extends Company
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'client_id'], 'integer', 'message' => Yii::t('error', 'id is invalid')],
            [['name', 'code', 'payment_frequency', 'limit_type', 'vat_number', 'record_type', 'create_on', 'last_update'], 'safe'],
            [['payment_frequency_amount', 'sales_fee', 'daily_limit', 'weekly_limit', 'monthly_limit'], 'number'],
            [['is_vat_exclusive'], 'boolean'],
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
        $query = Company::find()->where("company.record_type <> '".RecordType::Deleted."'");
        $query->andFilterWhere(['=', 'company.client_id', Yii::$app->request->getImpersonatedClientId()]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'name',
                'code',
                'payment_frequency',
                'payment_frequency_amount',
                'sales_fee',
                'is_vat_exclusive',
                'limit_type',
                'daily_limit',
                'weekly_limit',
                'monthly_limit',
                'vat_number',
                'client_id' => [
                    'asc' => ['client.name' => SORT_ASC],
                    'desc' => ['client.name' => SORT_DESC],
                ],
                'record_type',
                
            ],
            'defaultOrder' => ['id' => SORT_ASC]
        ]);
        
        $this->load($params);
        $query->join('LEFT JOIN', 'client', 'client.id = company.client_id');
      
        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'company.id' => $this->id,
            'payment_frequency_amount' => $this->payment_frequency_amount,
            'sales_fee' => $this->sales_fee,
            'is_vat_exclusive' => $this->is_vat_exclusive,
            'daily_limit' => $this->daily_limit,
            'weekly_limit' => $this->weekly_limit,
            'monthly_limit' => $this->monthly_limit,
            'client_id' => $this->client_id,
         ]);

        $query->andFilterWhere(['like', 'company.name', $this->name])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'payment_frequency', $this->payment_frequency])
            ->andFilterWhere(['like', 'limit_type', $this->limit_type])
            ->andFilterWhere(['like', 'vat_number', $this->vat_number])
            ->andFilterWhere(['=', 'company.record_type', $this->record_type]);

        return $dataProvider;
    }
}
