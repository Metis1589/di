<?php

namespace admin\controllers\search;

use common\enums\RecordType;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MenuItem;

/**
 * MenuItemSearch represents the model behind the search form about `common\models\MenuItem`.
 */
class MenuItemSearch extends MenuItem
{
    public $menu_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'cook_time', 'is_imported', 'sort_order'], 'integer'],
            [['name_key', 'vat_id', 'description_key', 'nutritional', 'record_type', 'create_on', 'last_update', 'menu_id'], 'safe'],
            [['restaurant_price', 'web_price', 'menu_category_id'], 'number'],
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
        $query = MenuItem::find()->where("menu_item.record_type <> '".RecordType::Deleted."'");
        $query->joinWith(['menu']);
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
            'menu.id' => Yii::$app->request->get('menu_id'),
            'menu_item.id' => $this->id,
            'vat_id' => $this->vat_id,
            'restaurant_price' => $this->restaurant_price,
            'web_price' => $this->web_price,
            'cook_time' => $this->cook_time,
            'is_imported' => $this->is_imported,
            'sort_order' => $this->sort_order,
            'menu_category_id' => $this->menu_category_id,
            'create_on' => $this->create_on,
            'last_update' => $this->last_update,
        ]);
        
        $query->joinWith('vat');

        $query->andFilterWhere(['like', 'menu_item.name_key', $this->name_key])
            ->andFilterWhere(['like', 'description_key', $this->description_key])
            ->andFilterWhere(['like', 'vat.id', $this->vat_id])
            ->andFilterWhere(['like', 'image_file_name', $this->image_file_name])
            ->andFilterWhere(['like', 'nutritional', $this->nutritional])
            ->andFilterWhere(['=', 'menu_item.record_type', $this->record_type]);

        $query->andFilterWhere(['menu.id' => $this->menu_id]);

        return $dataProvider;
    }
}
