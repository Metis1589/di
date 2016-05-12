<?php
namespace gateway\modules\v1\actions\common;

use gateway\modules\v1\forms\common\GetClientDataForm;
use gateway\modules\v1\components\GetApiAction;
use Exception;
use Yii;

class GetClientDataAction extends GetApiAction
{
    /**
     * Creates request form used to validate request parameters.
     *
     * @return GetClientDataForm
     */
    protected function createRequestForm()
    {
        return new GetClientDataForm();
    }

    /**
     * Get white-label site related information.
     *
     * @param GetClientDataForm $requestForm Request form class instance.
     *
     * @return string|boolean
     */
    protected function getResponseData($requestForm)
    {
        try {
            return [
                'restaurants' => Yii::$app->globalCache->getRestaurants($requestForm->client_key),
                'pages'       => Yii::$app->globalCache->getPages($requestForm->client_key),
                'currencies'  => Yii::$app->globalCache->getCurrencies(),
                'languages'   => Yii::$app->globalCache->getLanguageList(true),
                'labels'      => Yii::$app->globalCache->getLabelsClient($requestForm->client_key),
                'seo_areas'   => Yii::$app->globalCache->getSeoAreas(),
                'allergies'   => Yii::$app->globalCache->getAllergies(),
                'filters'     => [
                    'cuisines'     => Yii::$app->globalCache->getCuisines(),
                    'etas'         => Yii::$app->globalCache->getETAFilters(),
                    'price_ranges' => Yii::$app->globalCache->getPriceRangeFilters(),
                    'ratings'      => Yii::$app->globalCache->getRatingFilters(),
                    'charges'      => Yii::$app->globalCache->getChargeFilters(),
                ]
            ];
        } catch (Exception $ex) {
            return $ex;
        }
    }
}