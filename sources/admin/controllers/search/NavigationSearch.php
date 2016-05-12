<?php

namespace admin\controllers\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Navigation;

/**
 * PageSearch represents the model behind the search form about `common\models\Label`.
 */
class NavigationSearch extends Navigation
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'page_id', 'language_id', 'parent_id', 'order', 'client_id'], 'integer'],
            [['value', 'position', 'record_type', 'open_from', 'open_to', 'create_on', 'last_update'], 'safe'],
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
        $query = Navigation::find()->where("record_type<>'Deleted'");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_ASC]]
        ]);
        
        $query->andWhere([
            'client_id' => Yii::$app->request->getImpersonatedClientId(),
        ]);
        
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'page_id' => $this->page_id,
            'language_id' => $this->language_id,
            'client_id' => $this->client_id,
            'parent_id' => $this->parent_id,
            'position' => $this->position,
            'order' => $this->order,
            'record_type' => $this->record_type,
            'create_on' => $this->create_on,
            'last_update' => $this->last_update,
        ]);
        
        if($this->open_from){
            $query->andFilterWhere(['like', 'open_from', Yii::$app->formatter->asDate($this->open_from,'php:Y-m-d')]);
        }
        if($this->open_to){
            $query->andFilterWhere(['like', 'open_to', Yii::$app->formatter->asDate($this->open_to,'php:Y-m-d')]);
        }

        $query->andFilterWhere(['like', 'value', $this->value]);

        return $dataProvider;
    }
}
