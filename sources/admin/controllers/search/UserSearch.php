<?php

namespace admin\controllers\search;

use common\enums\RecordType;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * UserSearch represents the model behind the search form about `common\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'affiliate_id'], 'integer'],
            [['username', 'password', 'user_type', 'last_visit', 'activation_hash', 'photo', 'dob', 'know_about', 'api_token', 'reset_password_hash', 'record_type', 'create_on', 'last_update'], 'safe'],
            [['term_and_cond', 'term_and_cond_web', 'term_and_cond_acc_pol'], 'boolean'],
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
        $query = User::find()->where("record_type <> '".RecordType::Deleted."'");
        $query->andFilterWhere(['=', 'user.client_id', Yii::$app->request->getImpersonatedClientId()]);

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
            'last_visit' => $this->last_visit,
            'dob' => $this->dob,
            'term_and_cond' => $this->term_and_cond,
            'term_and_cond_web' => $this->term_and_cond_web,
            'term_and_cond_acc_pol' => $this->term_and_cond_acc_pol,
            'affiliate_id' => $this->affiliate_id,
            'last_update' => $this->last_update,
        ]);

        if($this->create_on){
            $query->andFilterWhere(['like', 'user.create_on', Yii::$app->formatter->asDate($this->create_on,'php:Y-m-d')]);
        }
        
        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['=', 'user_type', $this->user_type])
            ->andFilterWhere(['like', 'activation_hash', $this->activation_hash])
            ->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'know_about', $this->know_about])
            ->andFilterWhere(['like', 'api_token', $this->api_token])
            ->andFilterWhere(['like', 'reset_password_hash', $this->reset_password_hash])
            ->andFilterWhere(['=', 'record_type', $this->record_type]);

        return $dataProvider;
    }
}
