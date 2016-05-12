<?php
namespace frontend\components\cache;

use common\enums\RecordType;
use common\models\Cuisine;
use common\models\Currency;
use common\models\Label;
use common\models\LabelLanguage;
use common\models\Language;
use common\models\Menu;
use common\models\MenuOption;
use common\models\Restaurant;
use frontend\components\ApiHelper;
use Yii;
use yii\db\ActiveQuery;

class FrontendCache extends Cache
{
    const DATA_LOADED_KEY    = 'FRONTEND:DATA_LOADED';
    const DATA_LOADED_TIME   = 'FRONTEND:DATA_LOADED_TIME';
    const FILTERS_PREFIX     = 'FRONTEND:FILTERS';
    const LANGUAGES_PREFIX   = 'FRONTEND:LANGUAGES';
    const RESTAURANTS_PREFIX = 'FRONTEND:RESTAURANTS';
    const PAGES_PREFIX       = 'FRONTEND:PAGES';
    const LABELS_PREFIX      = 'FRONTEND:LABELS';
    const SEO_AREAS_PREFIX   = 'FRONTEND:SEO_AREAS';
    const ALLERGIES_PREFIX   = 'FRONTEND:ALLERGIES';

    function initialize()
    {
        $serverDataLoadTime = ApiHelper::getClientDataLoadTime();

        if ($this->getValue(self::DATA_LOADED_KEY) == null || $serverDataLoadTime != $this->getDataLoadTime())
        {
            $this->loadClientData();

            // indicate that data has been loaded
            $this->setValue(self::DATA_LOADED_KEY, true);
            $this->setValue(self::DATA_LOADED_TIME, $serverDataLoadTime);
        }
    }

    /**
     * get time when data loaded
     * @return mixed
     */
    public function getDataLoadTime() {
        return $this->getValue(self::DATA_LOADED_TIME);
    }

    /**
     * load client data
     */
    public function loadClientData() {
        $data = ApiHelper::getClientData();
        $this->setValue(self::FILTERS_PREFIX,     $data['filters']);
        $this->setValue(self::RESTAURANTS_PREFIX, $data['restaurants']);
        $this->setValue(self::PAGES_PREFIX,       $data['pages']);
        $this->setValue(self::LANGUAGES_PREFIX,   $data['languages']);
        $this->setValue(self::LABELS_PREFIX,      $data['labels']);
        $this->setValue(self::SEO_AREAS_PREFIX,   $data['seo_areas']);
        $this->setValue(self::ALLERGIES_PREFIX,   $data['allergies']);
    }

    /**
     * get client data
     * @return mixed
     */
//    public function getData() {
//        return $this->getValue(self::DATA_PREFIX);
//    }

    public function getCuisines($language_code) {
        $languageIsoCode = substr($language_code, 0, 2);
        $cuisines = $this->getFilters()['cuisines'][$languageIsoCode];

        $names = array();
        foreach ($cuisines as $key => $row)
        {
            $names[$key] = $row['name'];
        }
        array_multisort($names, SORT_ASC, $cuisines);

        return $cuisines;
    }

    public function getFilters() {
        return $this->getValue(self::FILTERS_PREFIX);
    }

    public function getAllergies() {
        return $this->getValue(self::ALLERGIES_PREFIX);
    }
//
//    public function getRatings() {
//        return $this->getData()['ratings'];
//    }

    public function getRestaurant($id) {
        $restaurants = $this->getValue(self::RESTAURANTS_PREFIX);
        $restaurant  = $restaurants[$id];
        return $restaurant;
    }

    /**
     * get restaurants
     * @return mixed
     */
    public function getRestaurants() {
        $restaurants = $this->getValue(self::RESTAURANTS_PREFIX);

        return $restaurants;
    }

    public function getRestaurantFeatured() {
        $restaurants = $this->getValue(self::RESTAURANTS_PREFIX);
        $result      = [];

        foreach ($restaurants as $restaurant) {
            if ($restaurant['is_featured']) {
                $result[] = $restaurant;
            }
        }

        return $result;
    }

    public function getPages() {
        return $this->getValue(self::PAGES_PREFIX);
    }

    public function getLanguages() {
        return $this->getValue(self::LANGUAGES_PREFIX);
    }

    /**
     * get language id by iso code
     * @param $languageIsoCode
     * @return mixed
     */
    public function getLanguageId($languageIsoCode)
    {
        $languages = $this->getLanguages();
        foreach ($languages as $language) {
            if ($language['iso_code'] == $languageIsoCode) {
                return $language['id'];
            }
        }
        return null;
    }

    public function getLabelByLanguage($languageIsoCode, $code, $message = null)
    {
        $languageId = $this->getLanguageId($languageIsoCode);
        $labels     = $this->getValue(self::LABELS_PREFIX) ?: [];

        if (!array_key_exists($languageId, $labels)) {
            return $message;
        }

        if (!array_key_exists($code, $labels[$languageId])) {
            return $message;
        }

        return $labels[$languageId][$code];
    }

    public function getSeoAreas() {
        $areas = $this->getValue(self::SEO_AREAS_PREFIX);

        $names = array();
        foreach ($areas as $key => $row)
        {
            $names[$key] = $row['name'];
        }
        array_multisort($names, SORT_ASC, $areas);

        return $areas;
    }

}
