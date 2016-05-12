<?php

namespace admin\controllers\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PostcodeBlacklist;

/**
 * PostcodeBlacklistSearch represents the model behind the search form about `common\models\PostcodeBlacklist`.
 */
class PostcodeBlacklistSearch extends PostcodeBlacklist
{
    private $_client_id;

    function __construct($client_id)
    {
        $this->_client_id = $client_id;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'client_id', 'postcode_id'], 'integer'],
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
        $query = PostcodeBlacklist::find()->where("postcode_blacklist.record_type <> 'Deleted'")->andWhere(['client_id' => $this->_client_id]);
        $query->joinWith('postcode');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
//            'defaultOrder' => ['postcode' => SORT_ASC]
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
            'postcode_id' => $this->postcode_id,
            'create_on' => $this->create_on,
            'last_update' => $this->last_update,
        ]);

        $query->andFilterWhere(['=', 'record_type', $this->record_type]);

        return $dataProvider;
    }
}
