<?php

namespace admin\controllers\search;

use common\enums\RecordType;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Allergy;

/**
 * AllergySearch represents the model behind the search form about `common\models\Allergy`.
 */
class AllergySearch extends Allergy
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name_key', 'description_key', 'symbol_key', 'record_type', 'create_on', 'last_update'], 'safe'],
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
        $query = Allergy::find()->where("allergy.record_type <> '".RecordType::Deleted."'");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'name_key'=> [
                    'asc' => ['label_language.value' => SORT_ASC],
                    'desc' => ['label_language.value' => SORT_DESC],
                ],
                'symbol_key',
                'record_type'
            ],
            'defaultOrder' => ['id' => SORT_ASC]
        ]);
        
        $this->load($params);

        $query->joinWith('labels')->joinWith('labels.labelLanguage');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'allergy.id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'label_language.value', $this->name_key])
            ->andFilterWhere(['like', 'symbol_key', $this->symbol_key])
            ->andFilterWhere(['=', 'allergy.record_type', $this->record_type]);

        return $dataProvider;
    }
}
