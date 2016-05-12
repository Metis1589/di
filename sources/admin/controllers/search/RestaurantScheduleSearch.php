<?php

namespace admin\controllers\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\RestaurantSchedule;

/**
 * RestaurantScheduleSearch represents the model behind the search form about `\common\models\RestaurantSchedule`.
 */
class RestaurantScheduleSearch extends RestaurantSchedule
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['restaurant_id', 'schedule_id'], 'integer','message' => Yii::t('error', 'id is invalid')],
            [['type', 'day', 'record_type', 'create_on', 'last_update', 'schedule_time', 'restaurant_name'], 'safe'],
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
        $query = RestaurantSchedule::find()->where("restaurant_schedule.record_type <> '".\common\enums\RecordType::Deleted ."'");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

         $dataProvider->setSort([
            'defaultOrder' => ['restaurant_id' => SORT_ASC]
        ]);
        
        $this->load($params);

        $query->join('INNER JOIN', 'schedule', 'restaurant_schedule.schedule_id = schedule.id')->
            join('INNER JOIN', 'restaurant', 'restaurant_schedule.restaurant_id = restaurant.id')->
            select(['restaurant_schedule.*', 'restaurant.name as restaurant_name' ,"CONCAT(DATE_FORMAT(schedule.from, '%h:%i%p'),'-',DATE_FORMAT(schedule.to, '%h:%i%p')) AS schedule_time"]);

                
        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        $query->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'day', $this->day])
            ->andFilterWhere(['=', 'restaurant.id', $this->restaurant_id])
            ->andFilterWhere(['=', 'restaurant_schedule.record_type', $this->record_type]);

        return $dataProvider;
    }
    
}
