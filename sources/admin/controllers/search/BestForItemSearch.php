<?php

namespace admin\controllers\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\BestForItem;

/**
 * BestForItemSearch represents the model behind the search form about `common\models\BestForItem`.
 */
class BestForItemSearch extends BestForItem
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer', 'message' => Yii::t('error', 'id is invalid')],
            [['name_key', 'record_type', 'create_on', 'last_update'], 'safe'],
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
        $query = BestForItem::find()->where("best_for_item.record_type <> 'Deleted'");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'name_key' => [
                    'asc' => ['best_for_name' => SORT_ASC],
                    'desc' => ['best_for_name' => SORT_DESC],
                ],
                'record_type'
            ],
            'defaultOrder' => ['id' => SORT_ASC]
        ]);
        
        $this->load($params);

        $query->joinWith('labels')->joinWith('labels.labelLanguage')
              ->select(['best_for_item.*', 'label_language.value AS best_for_name']);
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        

        $query->andFilterWhere([
            'best_for_item.id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'label_language.value', $this->name_key])
            ->andFilterWhere(['=', 'best_for_item.record_type', $this->record_type]);

        return $dataProvider;
    }
}
