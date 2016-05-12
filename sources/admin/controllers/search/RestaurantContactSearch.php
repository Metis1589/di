<?php

namespace admin\controllers\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\RestaurantContact;
use common\enums\RecordType;

/**
 * RestaurantContactSearch represents the model behind the search form about `\common\models\RestaurantContact`.
 */
class RestaurantContactSearch extends RestaurantContact
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['restaurant_id', 'contact_id'], 'integer','message' => Yii::t('error', 'id is invalid')],
            [['role', 'record_type', 'create_on', 'last_update'], 'safe'],
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
        $query = RestaurantContact::find()->where("record_type <> '".RecordType::Deleted."'");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'restaurant_id' => $this->restaurant_id,
            'contact_id' => $this->contact_id,
            'create_on' => $this->create_on,
            'last_update' => $this->last_update,
        ]);

        $query->andFilterWhere(['like', 'role', $this->role])
            ->andFilterWhere(['=', 'record_type', $this->record_type]);

        return $dataProvider;
    }
}
