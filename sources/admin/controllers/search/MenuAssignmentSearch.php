<?php

namespace admin\Controllers\Search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MenuAssignment;

/**
 * MenuAssignmentSearch represents the model behind the search form about `common\models\MenuAssignment`.
 */
class MenuAssignmentSearch extends MenuAssignment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'client_id', 'restaurant_chain_id', 'restaurant_group_id', 'restaurant_id', 'menu_id'], 'integer'],
            [['record_type', 'create_on', 'last_update'], 'safe'],
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
        $query = MenuAssignment::find()->where("record_type <> 'Deleted'");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'defaultOrder' => ['id' => SORT_ASC]
        ]);
        
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'client_id' => $this->client_id,
            'restaurant_chain_id' => $this->restaurant_chain_id,
            'restaurant_group_id' => $this->restaurant_group_id,
            'restaurant_id' => $this->restaurant_id,
            'menu_id' => $this->menu_id,
            'create_on' => $this->create_on,
            'last_update' => $this->last_update,
        ]);

        $query->andFilterWhere(['=', 'record_type', $this->record_type]);

        return $dataProvider;
    }
}
