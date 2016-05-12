<?php
namespace common\components\cache;

use admin\common\ArrayHelper;
use common\enums\RecordType;
use common\enums\RestaurantAddressType;
use common\enums\VoucherValidationService;
use common\models\CacheQueue;
use common\models\Client;
use common\models\Company;
use common\models\Cuisine;
use common\models\Currency;
use common\models\CustomField;
use common\models\Feedback;
use common\models\Label;
use common\models\LabelLanguage;
use common\models\Language;
use common\models\Menu;
use common\models\MenuOption;
use common\models\OrderRule;
use common\models\Page;
use common\models\Postcode;
use common\models\PostcodeBlacklist;
use common\models\Restaurant;
use common\models\SeoArea;
use common\models\Voucher;
use Exception;
use Yii;
use yii\db\ActiveQuery;

class GlobalCache extends Cache
{
    const DATA_LOADED_KEY            = 'INTERNAL:DATA_LOADED';
    const DATA_LOADING_KEY           = 'INTERNAL:DATA_LOADING';
    const DATA_LOADED_TIME           = 'INTERNAL:DATA_LOADED_TIME';
    const LOCALIZATION_PREFIX        = 'LABEL:';
    const CLIENT_LOCALIZATION_PREFIX = 'CLIENT_LABEL:';
    const LANGUAGES_PREFIX           = 'LANGUAGES:';
    const LANGUAGES_PUBLISHED_PREFIX = 'LANGUAGES_PUBLISHED:';
    const CUISINES_PREFIX            = 'CUISINES:';
    const CLIENTS_PREFIX             = 'CLIENTS:';
    const RESTAURANTS_PREFIX         = 'RESTAURANTS:';
    const RATING_PREFIX              = 'RATING:';
    const CURRENCIES_PREFIX          = 'CURRENCIES:';
    const MENUS_PREFIX               = 'MENUS:';
    const POSTCODE_PREFIX            = 'POSTCODE:';
    const VOUCHERS_PREFIX            = 'VOUCHER:';
    const PAGES_PREFIX               = 'PAGES:';
    const COMPANY_PREFIX             = 'COMPANY:';
    const PAYMENT_PREFIX             = 'PAYMENT:';
    const SEO_AREA_PREFIX            = 'SEO_AREA:';
    const ALLERGIES_PREFIX           = 'ALLERGIES:';
    const POSTCODE_BLACKLIST_PREFIX  = 'POSTCODE_BLACKLIST:';
    const CACHE_CRON_RUNNING_PREFIX  = 'CACHE_CRON_RUNNING:';
    const CACHE_IVR_SESSION          = 'TWILIO_IVR_SESSION:';
    const ORDER_RULES_PREFIX          = 'ORDER_RULES:';

    public function initialize() {
        if ($this->getValue(self::DATA_LOADED_KEY) == null)
        {
            if ($this->getValue(self::DATA_LOADING_KEY) === true) {
                throw new \Exception('Cache is loading');
            }
            $this->loadCache();
        }
    }
    
    public function isInitializing() {
        $isLoading = $this->getValue(self::DATA_LOADING_KEY);
        if (isset($isLoading)) {
            return $isLoading;
        }
        return false;
    }

    public function loadCache() {
        $this->setValue(self::DATA_LOADING_KEY, true);
        try {
            ini_set('max_execution_time', 0);
            ini_set('memory_limit', '-1');

            $this->loadLanguages();
            $this->loadClients();
            $this->loadCompanies();
            $this->loadLabels();
            $this->loadCuisines();
            $this->loadRestaurants();
            $this->loadRatings();
            $this->loadMenus();
            $this->loadVouchers();
            $this->loadCurrencies();
            $this->loadPostcodes();
            $this->loadPages();
            $this->loadSeoAreas();
            $this->loadAllergies();
            $this->loadPostcodeBlacklist();
            $this->loadOrderRules();

            // indicate that data has been loaded
            $this->setValue(self::DATA_LOADED_KEY, true);
            $this->updateDataLoadedTime();
            $this->setValue(self::DATA_LOADING_KEY, false);
        } catch (Exception $ex) {
           // Yii::error($ex->getMessage());
            $this->setValue(self::DATA_LOADING_KEY, false);
            $this->setValue(self::DATA_LOADED_KEY, false);
            echo 'Initilize cache issue';
            die($ex->getMessage() . ' ' . $ex->getTraceAsString());
        }
    }

    /**
     * update data loaded time
     */
    private function updateDataLoadedTime() {
        $this->setValue(self::DATA_LOADED_TIME, time());
    }

    /**
     * get time when data loaded
     * @return mixed
     */
    public function getDataLoadTime() {
        return $this->getValue(self::DATA_LOADED_TIME);
    }

    /**
     * @param $code
     * @return mixed|string
     */
    public function getLabel($code)
    {
        $languageIsoCode = substr(Yii::$app->language, 0, 2);
        return $this->getLabelByLanguage($languageIsoCode, $code);
    }

    public function deleteLabel($code) {
        $languageIsoCode = substr(Yii::$app->language, 0, 2);
        $this->deleteLabelByLanguage($code, $languageIsoCode);
    }

    public function deleteLabelByLanguage($code, $languageIsoCode) {
        $this->deleteValue(GlobalCache::LOCALIZATION_PREFIX . $languageIsoCode . '|' .$code);
    }

    public function setIvrSession($CallSid, $params) {
        $sessionData = $this->getIvrSession($CallSid) ?: [];

        if (!empty($params)) {
            foreach($params as $key => $value) {
                if (!isset($sessionData[$key]) || in_array($key, ['action', 'press_button', 'Digits'])) {
                    $sessionData[$key] = $value;
                }
            }
        }

        // 1 day cache lifetime
        $this->setValue(self::CACHE_IVR_SESSION . $CallSid, $sessionData, 86400);
    }

    public function getIvrSession($CallSid) {
        return $this->getValue(self::CACHE_IVR_SESSION . $CallSid);
    }

    public function loadAllergies() {
        Yii::warning('CACHE: Load Allergies');

        $allergies = \common\models\Allergy::find()->where(['record_type' => RecordType::Active])
            ->indexBy('id')
            ->orderBy('name_key')
            ->asArray()
            ->all();
        foreach ($allergies as &$allergy) {
            foreach ($this->getLanguageList(true) as $language) {
                $allergy['name'][$language['iso_code']] = $this->getLabelByLanguage($language['iso_code'], $allergy['name_key']);
                $allergy['description'][$language['iso_code']] = $this->getLabelByLanguage($language['iso_code'], $allergy['description_key']);
            }
        }

        $this->setValue(GlobalCache::ALLERGIES_PREFIX, $allergies);

        $this->updateDataLoadedTime();
    }

    public function getAllergies() {
        return $this->getValue(GlobalCache::ALLERGIES_PREFIX);
    }

    /*
     * Get message by $language and $code
     */
    public function getLabelByLanguage($languageIsoCode, $code, $message = null)
    {
        if (empty($code)) {
            return $code;
        }
        $key = GlobalCache::LOCALIZATION_PREFIX . $languageIsoCode . '|' . $code;

        $value = $this->getValue($key);
        if ($value)
        {
            return $value;
        }
        else
        {
            $languageId = $this->getLanguageId($languageIsoCode);
            // try to reload from database
            $result = LabelLanguage::find()->joinWith('label')->where(['label_language.language_id' => $languageId, 'label.code' => $code])
                ->andWhere('client_id IS NULL')->all();

            $key = GlobalCache::LOCALIZATION_PREFIX . $languageIsoCode . "|" . $code;

            if (count($result) == 0)
            {
                if (!Label::findOne(['code' => $code])) {
                    $label = new Label();

                    $label->code = $code;
                    $label->description = $code;
                    $label->record_type = 'Active';

                    $label->save();

                    if (!is_null($message)){
                        $labelLanguage = new LabelLanguage();
                        $labelLanguage->label_id = $label->id;
                        $labelLanguage->language_id = $languageId;
                        $labelLanguage->value = $message;
                        $labelLanguage->save();

                        $this->setValue($key, $message);
                        return $message;
                    }
                }

                $this->setValue($key, $code);
                return $code;
            }

            // cache loaded label

            $this->setValue($key, $result[0]->value);

            return $result[0]->value;
        }
    }

    public function getLabelsClient($client_key) {
        return $this->getValue(static::CLIENT_LOCALIZATION_PREFIX . $client_key);
    }

    /**
     * get language id by iso code
     * @param $languageIsoCode
     * @param bool $published
     * @return mixed
     */
    public function getLanguageId($languageIsoCode, $published = false)
    {
        foreach ($this->getLanguageList($published) as $language) {
            if ($language['iso_code'] == $languageIsoCode) {
                return $language['id'];
            }
        }
        return null;
    }

    /**
     * get default language model
     * @return integer defalut language id.
     */
    public function getDefaultLanguageId(){
        return $this->getLanguageId(Yii::$app->params['default_language']);
    }

    /**
     * Returns array of languages
     * @param bool $published
     * @return mixed
     */
    public function getLanguageList($published = false) {
        return $this->getValue(($published ? GlobalCache::LANGUAGES_PUBLISHED_PREFIX : GlobalCache::LANGUAGES_PREFIX));
    }

    public function getLanguage($language_id) {
        $languages = $this->getLanguageList();
        foreach($languages as $language) {
            if ($language['id'] == $language_id) {
                return $language;
            }
        }
        return null;
    }

    /**
     * @param $languageIsoCode
     * @param $code
     * @return int
     */
    public function invalidateLabel($languageIsoCode, $code)
    {
        $key = GlobalCache::LOCALIZATION_PREFIX . $languageIsoCode . '|' . $code;

        $this->deleteValue($key);
    }

        /**
     * cache languages
     */
    public function loadLanguages()
    {
        Yii::warning('CACHE: Load Languages');
        $languages = Language::find()->where(['<>', 'record_type', RecordType::Deleted])->indexBy('id')->orderBy('name')->asArray()->all();
        $languagesPublished = Language::find()->where(['record_type' => RecordType::Active])->indexBy('id')->orderBy('name')->asArray()->all();

        $this->setValue(GlobalCache::LANGUAGES_PREFIX, $languages);
        $this->setValue(GlobalCache::LANGUAGES_PUBLISHED_PREFIX, $languagesPublished);

        $this->updateDataLoadedTime();
    }

    public function loadCuisines() {
        Yii::warning('CACHE: Load Cuisines');

        $cuisines = Cuisine::find()->indexBy('id')->where(['record_type' => RecordType::Active])->all();

        $languageCuisines = [];

        foreach ($this->getLanguageList(true) as $language) {

            $cuisinesTranslated = [];

            /** @var Cuisine $cuisine */
            foreach ($cuisines as $cuisine) {
                $cuisinesTranslated[$cuisine->id] = [
                    'id' => $cuisine->id,
                    'name' => $this->getLabelByLanguage($language['iso_code'], $cuisine->name_key),
                    'seo_name' => $cuisine->seo_name,
                    'description' => $this->getLabelByLanguage($language['iso_code'], $cuisine->description_key),
                ];
            }

            $languageCuisines[$language['iso_code']] = $cuisinesTranslated;
        }

        $this->setValue(GlobalCache::CUISINES_PREFIX, $languageCuisines);

        $this->updateDataLoadedTime();
    }

    /**
     * Get cuisines by language
     * @return mixed
     */
    public function getCuisines() {
        return $this->getValue(GlobalCache::CUISINES_PREFIX);
    }

    /**
     * Get cuisines by language
     * @return mixed
     */
    public function getCuisine($id,$language) {
        $cuisines = $this->getCuisinesByLanguage($language);
        foreach($cuisines as $cuisine){
            if($cuisine['id']==$id){
                return $cuisine;
            }
        }
        return null;
    }

    /**
     * Get cuisines by language
     * @param $client_key
     * @param $languageCode
     * @return mixed
     */
    public function getCuisinesByLanguage($languageCode) {
        $languageIsoCode = substr($languageCode, 0, 2);
        return $this->getValue(GlobalCache::CUISINES_PREFIX)[$languageIsoCode];
    }

    /**
     * load clients
     */
    public function loadClients()
    {
        Yii::warning('CACHE: Load Clients');

        $clients = Client::find()->indexBy('key')->where(['record_type' => RecordType::Active])->asArray()->all();

        $this->setValue(GlobalCache::CLIENTS_PREFIX, $clients);

//        $this->loadRestaurants();

        $this->updateDataLoadedTime();
    }

    /**
     * get clients
     */
    public function getClients()
    {
        return $this->getValue(GlobalCache::CLIENTS_PREFIX);
    }

    /**
     * get client
     */
    public function getClient($key)
    {
        $clients = $this->getValue(GlobalCache::CLIENTS_PREFIX);
        if (array_key_exists($key, $clients)) {
            return $clients[$key];
        }
        return null;
    }

    /**
     * get client
     */
    public function getClientById($id)
    {
        $clients = $this->getValue(GlobalCache::CLIENTS_PREFIX);
        foreach ($clients as $client) {
            if ($client['id'] == $id){
                return $client;
            }
        }
         return null;
    }

    public function loadCompanies()
    {
        Yii::warning('CACHE: Load Companies');

        $clients = $this->getClients();

        foreach ($clients as $client) {
            $this->loadCompaniesByClient($client['id']);
        }

        $this->updateDataLoadedTime();
    }

    public function loadCompaniesByClient($client_id) {
        $companies = Company::find()->where(['client_id' => $client_id, 'record_type' => RecordType::Active])->all();
        foreach($companies as $company) {
            $this->loadCompany($company->id);
        }

    }

    public function loadCompany($company_id) {
        $company = Company::find()->where(['company.id' => $company_id, 'company.record_type' => RecordType::Active])->joinWith(
            ['companyDomains', 'companyUserGroups.activeCodes', 'companyUserGroups.activeExpenseTypes', 'companyUserGroups.activeExpenseTypes.activeExpenseTypeSchedules']
        )->asArray()->one();

        $this->setValue(GlobalCache::COMPANY_PREFIX . $company_id, $company);
    }

    /**
     *load restaurants
     */
    public function loadRestaurants()
    {
        Yii::warning('CACHE: Load Restaurants');

        $clients = $this->getClients();

        foreach ($clients as $client) {
            $this->loadRestaurantsByClient($client);
        }

        $this->updateDataLoadedTime();
    }

    public function loadRestaurantsByClientKey($key) {
        $this->loadRestaurantsByClient($this->getClient($key));
    }

    public function loadRestaurantsByClient($client) {
        $restaurants = Restaurant::find()->indexBy('id')->joinWith(
            [
                'restaurantPhotos' => function (ActiveQuery $q) {
                    $q->onCondition(['restaurant_photo.record_type' => RecordType::Active]);
                },
                'restaurantCuisines' => function (ActiveQuery $q) {
                    $q->onCondition(['restaurant_cuisine.record_type' => RecordType::Active]);
                },
                'restaurantCuisines.cuisine' => function (ActiveQuery $q) {
                    $q->onCondition(['cuisine.record_type' => RecordType::Active]);
                },
            ]
        )->joinWith(
            [
                'addressBase' => function (ActiveQuery $q) {
                    $q->onCondition(['address_base.record_type' => RecordType::Active]);
                },
                'currency' => function (ActiveQuery $q) {
                    $q->onCondition(['currency.record_type' => RecordType::Active]);
                },
                'restaurantAddresses.address' => function (ActiveQuery $q) {
//                    $q->onCondition(['restaurant_address.record_type' => RecordType::Active]);
                },
                'restaurantPayments',
            ], true, 'INNER JOIN'
        )->where(
            [
                'restaurant.record_type' => RecordType::Active,
                'restaurant.client_id' => $client['id']
            ]
        )->all();

        $result = [];

        /** @var Restaurant $restaurant */
        foreach($restaurants as $id => $restaurant) {
            $delivery = Restaurant::getAssignedDeliveryService($restaurant->id);
            $schedule = $restaurant->getAssignedSchedules();
            $properties = $restaurant->getAssignedProperties();

            if (!empty($delivery)) {
                $restaurant->populateRelation('restaurantDelivery', $delivery);
                $restaurant->populateRelation('restaurantSchedules', $schedule);
                $result[$id] = ArrayHelper::convertArToArray($restaurant);
                $result[$id]['restaurantProperties'] = $properties;
                $result[$id]['customFields'] = CustomField::getKeyValuesArray($client['key'], $id);
                $result[$id]['parents'] = Restaurant::getParentsByRestaurantId($id);

                foreach ($result[$id]['restaurantAddresses'] as $restaurant_address) {
                    if ($restaurant_address['address_type'] == RestaurantAddressType::Physical) {
                        $result[$id]['physicalAddress'] = $restaurant_address['address'];
                    }
                    else {
                        $result[$id]['pickupAddress'] = $restaurant_address['address'];
                    }
                }

                unset($result[$id]['restaurantAddresses']);
            }
        }

        $this->setValue(GlobalCache::RESTAURANTS_PREFIX . $client['key'], $result);

        $this->updateDataLoadedTime();
    }

    /**
     * load ratings by restaurant
     * @param null $restaurant_id
     */
    public function loadRatings($restaurant_id = null) {
        Yii::warning('CACHE: Load Ratings');

        $query = Feedback::find()
            ->indexBy('restaurant_id')
            ->select(['restaurant_id, COUNT(*) as count, ROUND(AVG(rating)) as rating'])
            ->groupBy('restaurant_id');

        if ($restaurant_id != null) {
            $query->where(['restaurant_id' => $restaurant_id]);
        }

        $ratings = $query->asArray()->all();

        foreach ($ratings as $restaurant_id => $rating) {
            $this->setValue(GlobalCache::RATING_PREFIX . $restaurant_id, $rating);
        }
    }

    /**
     * get restaurant rating
     * @param $restaurant_id
     * @return array|mixed
     */
    public function getRating($restaurant_id) {
        $rating = $this->getValue(GlobalCache::RATING_PREFIX . $restaurant_id);

        if ($rating == null) {
            $rating = [
                'count' => 0,
                'rating' => 0,
            ];
        }

        return $rating;
    }

    public function loadLabels($client_key = null) {
        Yii::warning('CACHE: Load Labels');

        $q = Label::find()->joinWith(['labelLanguages.language', 'client'])->where(['label.record_type' => RecordType::Active]);

        if ($client_key != null) {
            $q->andWhere(['client.key' => $client_key]);
        }

        $labels = $q->asArray()->all();

        $clientTranslations = [];

        foreach($labels as $label) {
            foreach($label['labelLanguages'] as $labelLanguage) {
                if ($label['client_id'] != null) {
                    $clientTranslations[$label['client']['key']][$labelLanguage['language_id']][$label['code']] = $labelLanguage['value'];
                }
                else {
                    $key = GlobalCache::LOCALIZATION_PREFIX . $labelLanguage['language']['iso_code'] . "|" . $label['code'];
                    $this->setValue($key, $labelLanguage['value']);
                }
            }
        }

        foreach ($clientTranslations as $key => $clientTranslation) {
            $this->setValue(static::CLIENT_LOCALIZATION_PREFIX . $key, $clientTranslation);
        }
    }

    /**
     *load content pages
     */
    public function loadPages()
    {
        Yii::warning('CACHE: Load Pages');

        $clients = $this->getClients();

        foreach ($clients as $client) {
            $this->loadPagesByClient($client['key']);
        }

        $this->updateDataLoadedTime();
    }

    public function loadPagesByClient($client_key) {
        $client = $this->getClient($client_key);

        $pages = Page::find()->indexBy('id')->where(
            [
                'record_type' => RecordType::Active,
                'client_id' => $client['id']
            ]
        )->asArray()->all();

        $this->setValue(GlobalCache::PAGES_PREFIX . $client['key'], $pages);
        $this->updateDataLoadedTime();
    }

    /**
     * get pages
     * @param $client_key
     * @return mixed
     */
    public function getPages($client_key) {
        return $this->getValue(GlobalCache::PAGES_PREFIX . $client_key);
    }

    /**
     * get restaurants
     * @param $client_key
     * @return mixed
     */
    public function getRestaurants($client_key) {
        return $this->getValue(GlobalCache::RESTAURANTS_PREFIX . $client_key);
    }

    /**
     * get restaurant
     * @param $client_key
     * @param $id
     * @return mixed
     */
    public function getRestaurant($client_key, $id) {
        $restaurants = $this->getRestaurants($client_key);
        if ($restaurants == null || !array_key_exists($id, $restaurants))
        {
            return null;
        }
        return $restaurants[$id];
    }

    /**
     *load currencies
     */
    public function loadCurrencies()
    {
        Yii::warning('CACHE: Load Currencies');

        $currencies = Currency::find()->where(['record_type' => 'Active'])->indexBy('id')->asArray()->all();
        $this->setValue(GlobalCache::CURRENCIES_PREFIX, $currencies);
        $this->updateDataLoadedTime();
    }

    /**
     * get currencies
     * @return mixed
     */
    public function getCurrencies() {
        return $this->getValue(GlobalCache::CURRENCIES_PREFIX);
    }

    /**
     * get currency
     * @return mixed
     */
    public function getCurrency($id) {
        return $this->getValue(GlobalCache::CURRENCIES_PREFIX)[$id];
    }

    /**
     * ETA Ranges
     */
    public function getETAFilters() {
        return [
            [
                'from' => 10,
                'to' => 20
            ],
            [
                'from' => 20,
                'to' => 30
            ],
            [
                'from' => 30,
                'to' => 40
            ],
            [
                'from' => 40,
                'to' => 50
            ],
            [
                'from' => 50,
                'to' => 60
            ],
            [
                'from' => 60,
                'to' => 70
            ],
        ];
    }

    /**
     * Values for Price Ranges
     * @return array
     */
    public function getPriceRangeFilters() {
        return [
            0 => [
                'value' => 0,
                'name' => '$',
            ],
            1 => [
                'value' => 1,
                'name' => '$$',
            ],
            2 => [
                'value' => 2,
                'name' => '$$$',
            ],
            3 => [
                'value' => 3,
                'name' => '$$$$',
            ],
            4 => [
                'value' => 4,
                'name' => '$$$$$',
            ],
        ];
    }

    /**
     * Values for Ratings
     * @return array
     */
    public function getRatingFilters() {
        return [
            1 => [
                'value' => 1,
                'name' => '*',
            ],
            2 => [
                'value' => 2,
                'name' => '**',
            ],
            3 => [
                'value' => 3,
                'name' => '***',
            ],
            4 => [
                'value' => 4,
                'name' => '****',
            ],
            5 => [
                'value' => 5,
                'name' => '*****',
            ],
        ];
    }

    /**
     * Charge filters
     * @return array
     */
    public function getChargeFilters() {

        return [
            [
                'from' => 0,
                'to' => 2.5
            ],
            [
                'from' => 2.5,
                'to' => 3
            ],
            [
                'from' => 3,
                'to' => 3.5
            ],
            [
                'from' => 3.5,
                'to' => 4
            ],
            [
                'from' => 4,
                'to' => 4.5
            ],
            [
                'from' => 4.5,
                'to' => 5
            ],
            [
                'from' => 5,
                'to' => 5.5
            ],
        ];
    }

    /**
     * Load menu items
     */
    public function loadMenus() {
        Yii::warning('CACHE: Load Menus');

        $clients = $this->getClients();
        foreach ($clients as $client) {
            // get menus by client
            $this->loadMenusByClient($client['key']);
        }
        $this->updateDataLoadedTime();
    }

    public function loadMenusByClient($client_key) {
        $restaurants = $this->getRestaurants($client_key);
        foreach($restaurants as $restaurant) {
            $this->loadMenuByRestaurant($restaurant['id']);
        }
    }

    public function loadMenuByRestaurant($restaurant_id) {

        /** @var Restaurant $restaurant */
        $restaurant = Restaurant::find()->where(['id' => $restaurant_id, 'record_type' => RecordType::Active])->one();
        if (!isset($restaurant)) {
            $this->deleteValue(GlobalCache::MENUS_PREFIX . $restaurant_id);
            $this->updateDataLoadedTime();
            return;
        }
        $assignedMenus = $restaurant->getAssignedMenus();
        $resultMenus = [];


        /** @var Menu $menu */
        foreach ($assignedMenus as $assignedMenu) {
            $menu = Menu::find()
                ->joinWith(
                    [
                        'menuCategories' => function (ActiveQuery $q) {
                            $q->onCondition(['menu_category.record_type' => RecordType::Active]);
                            $q->orderBy('menu_category.sort_order');
                        },
                        'menuCategories.menuItems' => function (ActiveQuery $q) {
                            $q->onCondition(['!=', 'menu_item.record_type', RecordType::Deleted]);
                            $q->orderBy('menu_item.sort_order');
                        },
                        'menuCategories.menuItems.allergies' => function (ActiveQuery $q) {
                            $q->onCondition(['allergy.record_type' => RecordType::Active]);
                        },
                    ]
                    , true, 'LEFT JOIN')
                ->where(
                    [
                        'menu.id' => $assignedMenu->id,
                        'menu.record_type' => RecordType::Active
                    ])
                ->asArray()->one();

            if ($menu != null) {
                $resultMenus[] = $menu;
            }

            // load menu options
            foreach ($resultMenus as &$resultMenu) {
                $resultMenu['name'] = [];
                
                foreach ($this->getLanguageList(true) as $language) {
                        $resultMenu['name'][$language['iso_code']] = $this->getLabelByLanguage($language['iso_code'], $resultMenu['name_key']);
                }
                
                foreach ($resultMenu['menuCategories'] as $key => &$category) {

                    if (count($category['menuItems']) == 0) {
                        unset($resultMenu['menuCategories'][$key]);
                        continue;
                    }

                    $category['name'] = [];
                    $category['description'] = [];
                    foreach ($this->getLanguageList(true) as $language) {
                        $category['name'][$language['iso_code']] = $this->getLabelByLanguage($language['iso_code'], $category['name_key']);
                        $category['description'][$language['iso_code']] = $this->getLabelByLanguage($language['iso_code'], $category['description_key']);
                    }

                    foreach ($category['menuItems'] as &$menuItem) {
                        $menuItem['name'] = [];
                        $menuItem['description'] = [];
                        foreach ($this->getLanguageList(true) as $language) {
                            $menuItem['name'][$language['iso_code']] = $this->getLabelByLanguage($language['iso_code'], $menuItem['name_key']);
                            $menuItem['description'][$language['iso_code']] = $this->getLabelByLanguage($language['iso_code'], $menuItem['description_key']);
                        }
                        $menuItem['options'] = MenuOption::getTree($menuItem['id']);

                        $menuItem['customFields'] = CustomField::getKeyValuesArray($restaurant->client_id, null, $menuItem['id']);
                    }
                }
                $resultMenu['menuCategories'] = array_values($resultMenu['menuCategories']);
            }
        }

        $this->setValue(GlobalCache::MENUS_PREFIX . $restaurant->id, $resultMenus);

        $this->updateDataLoadedTime();
    }

    /**
     * get menu items by restaurant
     * @param $restaurant_id
     * @return mixed
     */
    public function getMenus($restaurant_id) {
        return $this->getValue(GlobalCache::MENUS_PREFIX . $restaurant_id);
    }

    public function getMenuItem($restaurant_id, $menu_item_id) {
        $menus = $this->getValue(GlobalCache::MENUS_PREFIX . $restaurant_id);

        foreach ($menus as $menu) {
            foreach ($menu['menuCategories'] as $category) {
                foreach ($category['menuItems'] as $item) {
                    if ($item['id'] == $menu_item_id) {
                        return $item;
                    }
                }
            }
        }

        return null;
    }

    /**
     * @param $restaurant_id
     * @param $menu_item_id
     * @param $record_type
     * @return bool
     */
    public function updateMenuItemRecordType($restaurant_id, $menu_item_id, $record_type) {
        $menus = $this->getValue(GlobalCache::MENUS_PREFIX . $restaurant_id);

        foreach ($menus as $menu_key => $menu) {
            foreach ($menu['menuCategories'] as $category_key => $category) {
                foreach ($category['menuItems'] as $item_key => $item) {
                    if ($item['id'] == $menu_item_id) {
                        $menus[$menu_key]['menuCategories'][$category_key]['menuItems'][$item_key]['record_type'] = $record_type;

                        $this->setValue(GlobalCache::MENUS_PREFIX . $restaurant_id, $menus);
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * load postcodes to cache
     */
    public function loadPostcodes() {
        Yii::warning('CACHE: Load Postcodes');

        //$this->setValue(GlobalCache::POSTCODE_PREFIX . '1', ['aaa']);
        $postcodes = Postcode::find()->where(['record_type' => RecordType::Active])->asArray()->all();
        foreach($postcodes as $postcode) {
            $this->setPostcode($postcode);
        }
    }

    public function setPostcode($postcode) {
        $this->setValue(GlobalCache::POSTCODE_PREFIX .strtolower( $postcode['postcode']), $postcode);
    }

    public function getPostcode($postcode) {
        return $this->getValue(GlobalCache::POSTCODE_PREFIX . strtolower($postcode));
    }

    /**
     * Load vouchers
     */
    public function loadVouchers() {
        Yii::warning('CACHE: Load Vouchers');

        $clients = $this->getClients();

        foreach ($clients as $client) {
            $this->loadVouchersByClient($client['key']);
        }
    }

    /**
     * Load vouchers
     * @param $client_key
     * @internal param $client
     */
    public function loadVouchersByClient($client_key) {
        $client = $this->getClient($client_key);
        $vouchers = Voucher::find()->joinWith(
            [
                'voucherMenuItems', 'voucherMenuCategories', 'voucherSchedules'
            ])
            ->where(
                [
                    'client_id' => $client['id']
                ])
            ->andWhere('end_date >= CURDATE()')->asArray()->all();

        foreach ($vouchers as $voucher) {
            if ($voucher['record_type'] == RecordType::Active) {
                $this->setValue(GlobalCache::VOUCHERS_PREFIX . $client['key'] . '-' . $voucher['code'], $voucher);
            }
            else {
                $this->deleteValue(GlobalCache::VOUCHERS_PREFIX . $client['key'] . '-' . $voucher['code']);
            }
        }
    }

    /**
     * get voucher
     * @param $client_key
     * @param $code
     * @return mixed
     */
    public function getVoucher($client_key, $code) {
        $voucher = $this->getValue(GlobalCache::VOUCHERS_PREFIX . $client_key . '-' . $code);

        if (isset($voucher)) {
            return $voucher;
        }
        while (count($code) > 1) {

            $code = substr($code, 0, count($code) - 1);

            $voucher = $this->getValue(GlobalCache::VOUCHERS_PREFIX . $client_key . '-' . $code);

            if (isset($voucher) && $voucher['validation_service'] == VoucherValidationService::EagleEye) {
                return $voucher;
            }
        }

        return null;
    }

    public function getCompany($company_id) {
        return $this->getValue(GlobalCache::COMPANY_PREFIX . $company_id);
    }

    public function getPaymentInfo($psp_reference){
        return $this->getValue(GlobalCache::PAYMENT_PREFIX . $psp_reference);
    }

    public function setPaymentInfo($psp_reference, $payment){
        $this->setValue(GlobalCache::PAYMENT_PREFIX . $psp_reference, $payment, 3600);
    }

    /**
     * Load seo areas
     */
    public function loadSeoAreas() {
        Yii::warning('CACHE: Load Seo Areas');

        $seo_areas = SeoArea::find()->where(['record_type' => RecordType::Active])->indexBy('id')->asArray()->all();

        $this->setValue(GlobalCache::SEO_AREA_PREFIX, $seo_areas);

        $this->updateDataLoadedTime();
    }

    /**
     * get seo areas
     * @return mixed
     */
    public function getSeoAreas() {
        return $this->getValue(GlobalCache::SEO_AREA_PREFIX);
    }

    public function getSeoArea($id) {
        $seoAreas = $this->getValue(GlobalCache::SEO_AREA_PREFIX);
        if(array_key_exists($id,$seoAreas)){
            return $seoAreas[$id];
        }
        return null;
    }

    public function cacheUpdatingStarted() {
        $this->setValue(GlobalCache::CACHE_CRON_RUNNING_PREFIX, true);
    }

    public function cacheUpdatingFinished() {
        $this->setValue(GlobalCache::CACHE_CRON_RUNNING_PREFIX, false);
    }

    public function cacheIsUpdating() {
        return $this->getValue(GlobalCache::CACHE_CRON_RUNNING_PREFIX);
    }

    public function addUpdateCacheAction($action) {
        $existedQueue = CacheQueue::find()->where(['action' => $action])->one();
        if (!isset($existedQueue)) {
            $cacheQueue = new CacheQueue();
            $cacheQueue->action = $action;
            $cacheQueue->save();
        }
    }

    public function loadPostcodeBlacklist() {
        $clients = $this->getClients();

        foreach ($clients as $client) {
            $this->loadPostcodeBlacklistByClient($client['key']);
        }
    }

    public function loadPostcodeBlacklistByClient($client_key) {
        $client = $this->getClient($client_key);

        $postcodes = Postcode::find()->indexBy('postcode')
            ->joinWith(
                [
                    'postcodeBlacklists' => function (ActiveQuery $q) use ($client) {
                        $q->onCondition(
                            [
                                'postcode_blacklist.record_type' => RecordType::Active,
                                'client_id' => $client['id']
                            ]
                        );

                    }
                ], false, 'INNER JOIN')
            ->asArray()
            ->all();

        $this->setValue(GlobalCache::POSTCODE_BLACKLIST_PREFIX . $client['key'], $postcodes);
    }

    public function getBlacklist($client_key) {
        return $this->getValue(GlobalCache::POSTCODE_BLACKLIST_PREFIX . $client_key);
    }

    public function loadOrderRules() {
        $clients = $this->getClients();

        foreach ($clients as $client) {
            $this->loadOrderRulesByClient($client['key']);
        }
    }

    public function loadOrderRulesByClient($client_key) {
        $client = $this->getClient($client_key);

        $rules = OrderRule::find()
            ->joinWith('customField')
            ->joinWith('client', false)
            ->where(['client.key' => $client_key, 'order_rule.record_type' => RecordType::Active])
            ->asArray()
            ->all();

        $this->setValue(GlobalCache::ORDER_RULES_PREFIX . $client['key'], $rules);
    }

    public function getOrderRules($client_key) {
        return $this->getValue(GlobalCache::ORDER_RULES_PREFIX . $client_key);
    }
}
