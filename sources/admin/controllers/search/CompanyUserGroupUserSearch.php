<?php

namespace admin\controllers\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\CompanyUserGroupUser;
use common\enums\RecordType;

/**
 * CompanyUserGroupUserSearch represents the model behind the search form about `\common\models\CompanyUserGroupUser`.
 */
class CompanyUserGroupUserSearch extends CompanyUserGroupUser
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'company_user_group_id'], 'integer','message' => Yii::t('error', 'id is invalid')],
            [['record_type', 'create_on', 'last_update', 'company_user_group_name' , 'user_username'], 'safe'],
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
        $query = CompanyUserGroupUser::find()->where("company_user_group_user.record_type <> '". RecordType::Deleted ."'");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'record_type',
                'company_user_group_id' => [
                    'asc' => ['company_user_group_name' => SORT_ASC],
                    'desc' => ['company_user_group_name' => SORT_DESC],
                    'label' => 'Company Name User Group'
                ],
                'user_username' => [
                    'asc' => ['user_username' => SORT_ASC],
                    'desc' => ['user_username' => SORT_DESC],
                    'label' => 'User Name'
                ],
            ],
            'defaultOrder' => ['id' => SORT_ASC]
        ]);
        
        $this->load($params);

        $query->join('INNER JOIN', 'company_user_group', 'company_user_group_user.company_user_group_id = company_user_group.id')->
                join('INNER JOIN', 'user', 'company_user_group_user.user_id = user.id')->
                select(['company_user_group_user.*', 'company_user_group.name AS company_user_group_name', 'user.username AS user_username']);
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'company_user_group_user.id' => $this->id,
        ]);
        
        $query->andFilterWhere(['=', 'company_user_group.id', $this->company_user_group_id])
            ->andFilterWhere(['=', 'user.id', $this->user_username]);

        $query->andFilterWhere(['=', 'company_user_group_user.record_type', $this->record_type]);

        return $dataProvider;
    }
}
