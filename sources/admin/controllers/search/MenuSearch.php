<?php

namespace admin\controllers\search;

use common\enums\RecordType;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Menu;

/**
 * MenuSearch represents the model behind the search form about `common\models\Menu`.
 */
class MenuSearch extends Menu
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'client_id'], 'integer'],
            [['name_key', 'from', 'to', 'record_type', 'create_on', 'last_update', 'reference_name'], 'safe'],
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
        $query = Menu::find()->where("menu.record_type <> '".RecordType::Deleted."'");
        $query->andFilterWhere(['=', 'menu.client_id', Yii::$app->request->getImpersonatedClientId()]);

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
            'menu.id' => $this->id,
            'from' => $this->from,
            'to' => $this->to,
            'client_id' => $this->client_id,
            'create_on' => $this->create_on,
            'last_update' => $this->last_update,
        ]);
        
        $query->joinWith('client');

        $query->andFilterWhere(['like', 'reference_name', $this->reference_name])
            ->andFilterWhere(['like', 'client.id', $this->client_id])
            ->andFilterWhere(['=', 'menu.record_type', $this->record_type]);

        return $dataProvider;
    }
}
