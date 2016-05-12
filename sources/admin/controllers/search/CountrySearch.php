<?php

namespace admin\Controllers\Search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Country;

/**
 * CountrySearch represents the model behind the search form about `common\models\Country`.
 */
class CountrySearch extends Country
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer','message' => Yii::t('error', 'id is invalid')],
            [['name_key', 'native_name', 'iso_code', 'record_type', 'create_on', 'last_update'], 'safe'],
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
        $query = Country::find()->where("country.record_type <> 'Deleted'");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'defaultOrder' => ['id' => SORT_ASC],
            'attributes' => [
                'id',
                'native_name',
                'record_type',
                'iso_code',
                'name_key' => [
                    'asc' => ['country_name' => SORT_ASC],
                    'desc' => ['country_name' => SORT_DESC],
                ]
            ]
        ]);
        
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith('labels')->joinWith('labels.labelLanguage')
              ->select(['country.*', 'label_language.value AS country_name']); 
        
        $query->andFilterWhere([
            'country.id' => $this->id,
            'country.create_on' => $this->create_on,
            'country.last_update' => $this->last_update,
        ]);

        $query->andFilterWhere(['like', 'native_name', $this->native_name])
            ->andFilterWhere(['like', 'iso_code', $this->iso_code])
            ->andFilterWhere(['like', 'label_language.value', $this->name_key])
            ->andFilterWhere(['=', 'country.record_type', $this->record_type]);

        return $dataProvider;
    }
}
