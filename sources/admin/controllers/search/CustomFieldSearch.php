<?php

namespace admin\controllers\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\CustomField;

/**
 * CustomFieldSearch represents the model behind the search form about `common\models\CustomField`.
 */
class CustomFieldSearch extends CustomField
{
    private $_type;
    private $_client_id;

    function __construct($client_id, $type)
    {
        $this->_type = $type;
        $this->_client_id = $client_id;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['key', 'default_value', 'value_type', 'type', 'record_type', 'create_on', 'last_update'], 'safe'],
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
        $query = CustomField::find()->where("record_type <> 'Deleted'")->andWhere(['type' => $this->_type, 'client_id' => $this->_client_id]);

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
            'create_on' => $this->create_on,
            'last_update' => $this->last_update,
        ]);

        $query->andFilterWhere(['like', 'key', $this->key])
            ->andFilterWhere(['like', 'default_value', $this->default_value])
            ->andFilterWhere(['like', 'value_type', $this->value_type])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['=', 'record_type', $this->record_type]);

        return $dataProvider;
    }
}
