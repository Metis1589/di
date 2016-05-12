<?php

namespace admin\controllers;

use admin\common\ArrayHelper;
use common\enums\RecordType;
use common\models\CustomField;
use Exception;
use common\models\Client;
use common\models\RestaurantChain;
use common\models\RestaurantDeliveryCharge;
use common\models\RestaurantGroup;
use Yii;
use common\models\RestaurantDelivery;
use admin\controllers\search\RestaurantDeliverySearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Restaurant;
use yii\filters\AccessControl;
use common\components\identity\RbacHelper;
use common\enums\UserType;
use yii\web\Response;

/**
 * RestaurantDeliveryController implements the CRUD actions for RestaurantDelivery model.
 */
class RestaurantDeliveryController extends BaseController
{
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    RbacHelper::allowAllActionsForRestaurantUser(Yii::$app->request->getFirstParamValue(['restaurant_id'])),
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'save-delivery' => ['post']
                ],
            ],
        ];
    }

    public function actionGetDelivery($id, $model) {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $delivery = RestaurantDelivery::find()->where([$model.'_id' => $id])->with(['restaurantDeliveryCharges'])->one();
        if ($delivery == null) {
            $delivery = new RestaurantDelivery();
            $delivery->has_collection = false;
            $delivery->has_dinein = false;
            $delivery->has_own = false;
        }

        $this->renderJson($this->getDeliveryServices($id, $model));
    }

    public function actionSaveDelivery() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = json_decode(Yii::$app->request->rawBody, true);

        $assigment = $data['model']. '_id';
        $restaurantDelivery = RestaurantDelivery::find()->where([$assigment => $data['id']])->with(['restaurantDeliveryCharges'])->one();

        if ($restaurantDelivery == null) {
            $restaurantDelivery = new RestaurantDelivery();
        }

        $restaurantDelivery->load($data['delivery'],'');

        if ($restaurantDelivery->record_type == RecordType::InActive && $restaurantDelivery->isNewRecord) {
            $this->renderJson(ArrayHelper::convertArToArray($restaurantDelivery));
        }

        $restaurantDelivery->$assigment = $data['id'];

        $restaurantDelivery->saveByPost($data['delivery']['restaurantDeliveryCharges']);

        // update cache
        $client_key = null;
        if (isset($restaurantDelivery->client_id)) {
            $client_key = $restaurantDelivery->client->key;
        } else if (isset($restaurantDelivery->restaurant_chain_id)) {
            $client_key = $restaurantDelivery->restaurantChain->client->key;
        } else if (isset($restaurantDelivery->restaurant_group_id)) {
            $client_key = $restaurantDelivery->restaurantGroup->restaurantChain->client->key;
        } else if (isset($restaurantDelivery->restaurant_id)) {
            $client_key = $restaurantDelivery->restaurant->client->key;
        } else {
            throw new Exception('Client was not found');
        }
        $client = Yii::$app->globalCache->getClient($client_key);
        $this->reloadCache($client['key']);

        $this->renderJson($this->getDeliveryServices($data['id'], $data['model']));
    }

    private function reloadCache($client_key) {
        Yii::$app->globalCache->addUpdateCacheAction("loadRestaurantsByClientKey('".$client_key."')");
    }

    public function actionForm($model)
    {
        return $this->renderPartial('_form', [
            'model' => $model
        ]);
    }

    private function getDeliveryServices($id, $model) {
        $delivery = RestaurantDelivery::find()->where([$model.'_id' => $id])->with(['restaurantDeliveryCharges'])->one();
        $parentDelivery = new RestaurantDelivery();

        if (isset($delivery)){
            $parentDelivery = $delivery;
        } else if ($model == 'restaurant') {
            $parentDelivery = Restaurant::getAssignedDeliveryService($id);
        } else if ($model == 'restaurant_group') {
            $parentDelivery = RestaurantGroup::getAssignedDeliveryService($id);
        } else if ($model == 'restaurant_chain') {
            $parentDelivery = RestaurantChain::getAssignedDeliveryService($id);
        } else if ($model == 'client') {
            $parentDelivery = Client::getDeliveryService($id);
        }

        if (!isset($delivery)) {
            $delivery = new RestaurantDelivery();
            $delivery->has_collection = false;
            $delivery->has_dinein = false;
            $delivery->has_own = false;
        }
        if (!isset($parentDelivery)) {
            $parentDelivery = new RestaurantDelivery();
            $parentDelivery->has_collection = false;
            $parentDelivery->has_dinein = false;
            $parentDelivery->has_own = false;
        }

        if (empty($delivery->restaurantDeliveryCharges)) {
            $delivery->populateRelation('restaurantDeliveryCharges', [new RestaurantDeliveryCharge()]);
        }

        if (empty($parentDelivery->restaurantDeliveryCharges)) {
            $parentDelivery->populateRelation('restaurantDeliveryCharges', [new RestaurantDeliveryCharge()]);
        }

        $deliveryArray = ArrayHelper::convertArToArray($delivery);

        if (array_key_exists('restaurantDeliveryCharges', $deliveryArray)) {

            foreach ($deliveryArray['restaurantDeliveryCharges'] as &$charge) {
                $fields = CustomField::getKeyValuesArray(
                    Yii::$app->request->getImpersonatedClientId(),
                    null,
                    null,
                    $charge['id'],
                    true
                );

                $charge['custom_fields'] = $fields;
            }
        }

        $parentDeliveryArray = ArrayHelper::convertArToArray($parentDelivery);

        if (array_key_exists('restaurantDeliveryCharges', $parentDeliveryArray)) {

            foreach ($parentDeliveryArray['restaurantDeliveryCharges'] as &$charge) {
                $fields = CustomField::getKeyValuesArray(
                    Yii::$app->request->getImpersonatedClientId(),
                    null,
                    null,
                    $charge['id'],
                    true
                );

                $charge['custom_fields'] = $fields;
            }
        }

        return[
            'deliveryService' => $deliveryArray,
            'parentDeliveryService' => $parentDeliveryArray
        ];
    }

}
