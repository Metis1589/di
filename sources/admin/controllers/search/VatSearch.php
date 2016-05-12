<?php

namespace admin\controllers\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Vat;

/**
 * VatSearch represents the model behind the search form about `common\models\Vat`.
 */
class VatSearch extends Vat
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['is_default', 'integer', 'message' => Yii::t('label', 'invalid ID')],
            ['id',  'integer', 'message' => Yii::t('label', 'invalid is default')],
            [['type', 'record_type', 'create_on', 'last_update'], 'safe'],
            [['value'], 'double', 'message' => Yii::t('label', 'invalid value')],
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
        $query = Vat::find()->where("record_type <> 'Deleted'");

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
            'is_default' => $this->is_default,
            'create_on' => $this->create_on,
            'last_update' => $this->last_update,
        ]);

        $query->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['=', 'record_type', $this->record_type])
            ->andFilterWhere(['like', 'value', strval($this->value)]);

        return $dataProvider;
    }
}
