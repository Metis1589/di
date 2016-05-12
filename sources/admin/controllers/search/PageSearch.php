<?php

namespace admin\controllers\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Page;

/**
 * PageSearch represents the model behind the search form about `common\models\Label`.
 */
class PageSearch extends Page
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'language_id', 'client_id'], 'integer'],
            [['title', 'record_type', 'slug', 'content', 'description', 'robots', 'open_from', 'open_to', 'create_on', 'last_update'], 'safe'],
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
        $query = Page::find()->where("record_type<>'Deleted'");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_ASC]]
        ]);
        
        $query->andFilterWhere([
            'client_id' => Yii::$app->request->getImpersonatedClientId(),
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'language_id' => $this->language_id,
            'client_id' => $this->client_id,
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

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'robots', $this->robots]);
        
        

        return $dataProvider;
    }
}
