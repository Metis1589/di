<?php

namespace common\models;

use common\enums\RecordType;
use Yii;
use yii\helpers\ArrayHelper;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Report extends \common\models\BaseModel
{
    public $date_from;
    public $date_to;
    public $restaurant_chain_id;
    public $restaurant_group_id;
    public $restaurant_id;

    /**
     * Additional form validation rules.
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['date_from', 'date_to'], 'required'],
            [['restaurant_chain_id', 'restaurant_group_id', 'restaurant_id'], 'safe']
        ];
    }
    
    public function getChains($clientId){
        $chains = RestaurantChain::find()->where(['client_id' => $clientId, 'record_type' => RecordType::Active])->all();
        foreach ($chains as $chain) {
            $chain->name_key = Yii::$app->globalCache->getLabel($chain->name_key);
        }
        return ArrayHelper::map($chains, 'id', 'name_key');
    }
    
    public function getGroups($chain_id){
        $result = [];
        $groups = RestaurantGroup::find()->where(['restaurant_chain_id' => $chain_id, 'record_type' => RecordType::Active])->all();
        foreach ($groups as $group) {
            $group->name_key = Yii::$app->globalCache->getLabel($group->name_key);
            $result[] = ['id' => $group->id, 'name' => $group->name_key];
        }
        return $result;
    }
    
    public function getRestaurants($restaurant_group_id){
        $result = [];
        $restaurants = Restaurant::find()->where(['restaurant_group_id' => $restaurant_group_id, 'record_type' => RecordType::Active])->all();
        foreach ($restaurants as $restaurant) {
            $result[] = ['id' => $restaurant->id, 'name' => $restaurant->name];
        }
        return $result;
    }
    
    public function getReportUrl(){
        $base_url = Yii::$app->params['gateway_url'];
        $data = [];
        if (!empty($this->restaurant_chain_id)){
            $data['restaurant_chain_id'] = $this->restaurant_chain_id;
        }
        if (!empty($this->restaurant_group_id)){
           $data['restaurant_group_id'] = $this->restaurant_group_id;
        }
        if (!empty($this->restaurant_id)){
            $data['restaurant_id'] = $this->restaurant_id;
        }
        
        $data['date_from'] = $this->date_from;
        $data['date_to'] = $this->date_to;
        $data['client_key'] = Yii::$app->globalCache->getClientById(Yii::$app->request->getImpersonatedClientId())['key'];
        $params = http_build_query($data);
        
        return $base_url . 'report-generate-pl?' . $params;
    }
}