<?php

namespace common\components\mailchimp;

use Yii;
use yii\base\Component;
use yii\base\Exception;

class MailChimp extends Component
{
    const GET_LIST_URL = '/2.0/lists/list.json';
    const ADD_SUBSCRIBER = '/2.0/lists/subscribe.json';

    public function addUserToCityList($client_key, $user_email, $city_name = null)
    {
        try {
            $client = Yii::$app->globalCache->getClient($client_key);

            if (empty($client['mc_default_city_list_name']) || empty($client['mc_api_key']) || empty($client['mc_host'])) {
                return;
            }

            if (empty($city_name)) {
                $city_name = $client['mc_default_city_list_name'];
            }

            $list =  $this->getListOrDefaultByName($client_key, $city_name, $client['mc_default_city_list_name']);

            if (isset($list)) {
                $this->addSubscriberToList($client_key, $client['mc_api_key'], $list->id, $user_email);
            }
        } catch (Exception $e) {
            //TODO: log error
        }

    }

    public function addUserToRestaurantList($client_key, $user_email, $restaurant_name)
    {
        try {
            $client = Yii::$app->globalCache->getClient($client_key);

            if (empty($client['mc_default_restaurant_list_name']) || empty($client['mc_api_key']) || empty($client['mc_host'])) {
                return;
            }

            $list = $this->getListOrDefaultByName($client_key, $restaurant_name, $client['mc_default_restaurant_list_name']);

            if (isset($list)) {
                $this->addSubscriberToList($client_key, $client['mc_api_key'], $list->id, $user_email);
            }
        } catch (Exception $e) {
            //TODO: log error
        }
    }

    private function addSubscriberToList($client_key, $mc_api_key, $list_id, $user_email) {
        $response = $this->sendRequest($client_key, self::ADD_SUBSCRIBER, [
            'apikey' => $mc_api_key,
            'id' => $list_id,
            'email' => [
                'email' => $user_email
            ],
            'update_existing' => true,
            'double_optin' => false,
            'send_welcome' => false
        ]);

        if (isset($response->error)) {
            //TODO: log error
        }
    }

    private function getListOrDefaultByName($client_key, $name, $default_name) {
        $lists = $this->getLists($client_key);
        $list = $this->getListByName($lists, $name);
        if (isset($list)) {
            return $list;
        }
        return $this->getListByName($lists, $default_name);
    }

    private function getListByName($lists, $name)
    {
        foreach($lists as $list) {
            if (strtolower(trim($list->name)) == strtolower(trim($name))) {
                return $list;
            }
        }
        return null;
    }

    private function getLists($client_key)
    {
        $client = Yii::$app->globalCache->getClient($client_key);

        $response = $this->sendRequest($client_key, self::GET_LIST_URL, [
            'apikey' => $client['mc_api_key']
        ]);

        return $response->data;
    }

    private function sendRequest($client_key, $url, array $data) {

        $client = Yii::$app->globalCache->getClient($client_key);

        $data_string = json_encode($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $client['mc_host'] . $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        ]);
        $r = curl_exec($ch);
        $e = curl_error($ch);
        if (!empty($e)) {
            //TODO: log error
        }
        return json_decode($r);
    }


}