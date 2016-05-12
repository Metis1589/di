<?php

namespace admin\controllers\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\EmailTemplate;

/**
 * PageSearch represents the model behind the search form about `common\models\Label`.
 */
class EmailtemplateSearch extends EmailTemplate
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'language_id', 'client_id'], 'integer'],
            [['title', 'record_type', 'email_type', 'content', 'create_on', 'last_update'], 'safe'],
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
        $query = Emailtemplate::find()->where("record_type<>'Deleted'");

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
            'email_type' => $this->email_type,
            'record_type' => $this->record_type,
            'create_on' => $this->create_on,
            'last_update' => $this->last_update,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'content', $this->content]);
        
        return $dataProvider;
    }
    
    /**
     * 
     * Loads to dataProvider default emails in case if clients version of email wasn't specified.
     */
    public function prepareDefaultTemplates($dataProvider){
        $resultModels = [];
        $models = $dataProvider->getModels();
        $tempaltes = array_keys(\common\enums\EmailType::getLabels());
        foreach($tempaltes as $template){
            if(!array_key_exists($template,$models)){
                $model = new \common\models\EmailTemplate;
                $model->email_type = $template;
                $model->title = \common\enums\EmailType::getLabels()[$template];
                $model->language_id = Yii::$app->globalCache->getDefaultLanguageId();
                $model->record_type = \common\enums\RecordType::Active;
            }
            else{
                $model = $models[$template];
            }
            $resultModels[] = $model;
        }
        return new \yii\data\ArrayDataProvider(['models'=>$resultModels]);
    }
}
