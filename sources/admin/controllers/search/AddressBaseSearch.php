<?php

namespace admin\controllers\search;

use common\enums\RecordType;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\AddressBase;

/**
 * AddressBaseSearch represents the model behind the search form about `common\models\AddressBase`.
 */
class AddressBaseSearch extends AddressBase
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer','message' => Yii::t('error', 'id is invalid')],
            [['name', 'delivery_delay_time','postcode', 'record_type', 'max_delivery_distance', 'last_update'], 'safe'],
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
        $query = AddressBase::find()->where("address_base.record_type <> '".RecordType::Deleted."'");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'name',
                'record_type',
                'delivery_delay_time',
                'postcode',
                'max_delivery_distance'
            ],
            'defaultOrder' => ['id' => SORT_ASC]
        ]);
        
        $this->load($params);
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'address_base.id' => $this->id,
            'delivery_delay_time' => $this->delivery_delay_time,
        ]);

        $query->andFilterWhere(['like', 'address_base.name', $this->name])
            ->andFilterWhere(['like', 'address_base.postcode', $this->postcode])
            ->andFilterWhere(['like', 'address_base.max_delivery_distance', $this->max_delivery_distance])
            ->andFilterWhere(['=', 'address_base.record_type', $this->record_type]);

        return $dataProvider;
    }
}
