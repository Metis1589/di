<?php

namespace admin\controllers\search;

use common\enums\RecordType;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MenuCategory;

/**
 * MenuCategorySearch represents the model behind the search form about `common\models\MenuCategory`.
 */
class MenuCategorySearch extends MenuCategory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'menu_id', 'is_optional', 'sort_order'], 'integer'],
            [['name_key', 'reference_name', 'description_key', 'record_type', 'create_on', 'last_update'], 'safe'],
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
        $query = MenuCategory::find()->where("menu_category.record_type <> '".RecordType::Deleted."'");
        $query->joinWith('menu');
        $query->andFilterWhere(['=', 'menu.client_id', Yii::$app->request->getImpersonatedClientId()]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'defaultOrder' => ['sort_order' => SORT_ASC]
        ]);
        
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'menu_category.id' => $this->id,
            'is_optional' => $this->is_optional,
            'sort_order' => $this->sort_order,
            'create_on' => $this->create_on,
            'last_update' => $this->last_update,
        ]);

        $query->andFilterWhere(['like', 'menu_category.reference_name', $this->reference_name])
            ->andFilterWhere(['=', 'menu.id', $this->menu_id])
            ->andFilterWhere(['like', 'description_key', $this->description_key])
            ->andFilterWhere(['=', 'menu_category.record_type', $this->record_type]);

        return $dataProvider;
    }
}
