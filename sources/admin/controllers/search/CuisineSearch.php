<?php

namespace admin\controllers\search;

use common\enums\RecordType;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Cuisine;

/**
 * CuisineSearch represents the model behind the search form about `common\models\Cuisine`.
 */
class CuisineSearch extends Cuisine
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer','message' => Yii::t('error', 'id is invalid')],
            [['name_key', 'seo_name', 'description_key', 'record_type',], 'safe'],
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
        $query = Cuisine::find()->where("cuisine.record_type <> '".RecordType::Deleted."'");
        //$query->andFilterWhere(['=', 'cuisine.client_id', Yii::$app->request->getImpersonatedClientId()]);

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
                'seo_name',
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
            'cuisine.id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'label_language.value', $this->name_key])
            ->andFilterWhere(['like', 'cuisine.seo_name', $this->seo_name])
           // ->andFilterWhere(['like', 'cuisine_description', $this->description_key])
            ->andFilterWhere(['=', 'cuisine.record_type', $this->record_type]);

        return $dataProvider;
    }
}
