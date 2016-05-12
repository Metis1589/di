<?php

namespace admin\controllers\search;

use common\enums\RecordType;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SeoArea;

/**
 * SeoAreaController represents the model behind the search form about `\common\models\SeoArea`.
 */
class SeoAreaSearch extends SeoArea
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'seo_name', 'description', 'record_type', 'create_on', 'last_update'], 'safe'],
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
        $query = SeoArea::find()->where("seo_area.record_type <> '".RecordType::Deleted ."'");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'create_on' => $this->create_on,
            'last_update' => $this->last_update,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'seo_name', $this->seo_name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'record_type', $this->record_type]);

        return $dataProvider;
    }
}
