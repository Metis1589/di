<?php
namespace gateway\modules\v1\actions\common;

use common\enums\RecordType;
use DateInterval;
use DateTime;
use ErrorException;
use Exception;
use gateway\models\SessionUser;
use gateway\modules\v1\components\GetApiAction;
use gateway\modules\v1\forms\common\GetMenusForm;
use Yii;

class GetMenusAction extends GetApiAction
{
	/**
	 * Creates request form used to validate request parameters.
	 *
	 * @return GetMenusForm
	 */
	protected function createRequestForm()
	{
		return new GetMenusForm();
	}

//    /**
//     * @param $menu_item
//     * @param $custom_fields_array
//     * @return bool
//     * @internal param $field_value
//     */
//    private function isMenuItemHasCustomField($menu_item, $custom_fields_array) {
//
//        if (count($custom_fields_array) == 0) {
//            return true;
//        }
//
//        foreach ($custom_fields_array as $field_key) {
//            if (!empty($field_key) && array_key_exists($field_key, $menu_item['customFields']) && $menu_item['customFields'][$field_key] == '1') {
//                return true;
//            }
//        }
//
//        return false;
//    }

    /**
	 * get menus per restaurant.
	 *
	 * @param GetMenusForm $requestForm Request form class instance.
	 *
	 * @return string
	 */
	protected function getResponseData($requestForm)
	{
		try {
            /** @var SessionUser $session_user */
            $session_user = Yii::$app->userCache->getUser();

            $restaurant_id = $requestForm->restaurant_id;

//            $custom_fields_array = [];
//
//            if ($requestForm->custom_fields) {
//                $custom_fields_array = explode(',', $requestForm->custom_fields);
//            }

            $record_type_array = [];

            if ($requestForm->record_types) {
                $record_type_array = explode(',', $requestForm->record_types);
            } else {
                $record_type_array[] = RecordType::Active;
            }

            if (!Yii::$app->user->isGuest && isset(Yii::$app->user->identity->restaurant_id)) {
                $restaurant_id = Yii::$app->user->identity->restaurant_id;
            }

            if ($restaurant_id == null) {
                throw new ErrorException('Restaurant ID is missing');
            }

            $menus = Yii::$app->globalCache->getMenus($restaurant_id);

            $result = [];
            if (!empty($menus)) {
                $now = new DateTime();

                $languageIsoCode = substr(Yii::$app->language, 0, 2);

                foreach ($menus as $menu) {
                    $from = new DateTime('today ' . $menu['from']);
                    $to = new DateTime('today ' . $menu['to']);

                    if ($to < $from) {
                        $to = $to->add(new DateInterval('P1D'));
                    }

                    foreach ($menu['menuCategories'] as &$category) {
                        $category['name_key'] = $category['name'][$languageIsoCode];
                        $category['description_key'] = $category['description'][$languageIsoCode];

                        $menu_items_filtered = [];

                        foreach ($category['menuItems'] as &$menuItem) {
                            $menuItem['name_key'] = $menuItem['name'][$languageIsoCode];
                            $menuItem['description_key'] = $menuItem['description'][$languageIsoCode];

                            if (in_array($menuItem['record_type'], $record_type_array)) {
                                $menu_items_filtered[] = $menuItem;
                            }
                        }

                        $category['menuItems'] = $menu_items_filtered;
                    }

                    if (/*$now >= $from && */$now <= $to) {
                        $menu['is_available_now'] = $now >= $from;
                        $result[] = $menu;
                    }
                }
            }

            return [
                'menus' => $result,
                'currency_symbol' => Yii::$app->globalCache->getRestaurant($requestForm->client_key ,$requestForm->restaurant_id)['currency']['symbol']
            ];
		}
		catch (Exception $ex) {
			return $ex;
		}
	}
}