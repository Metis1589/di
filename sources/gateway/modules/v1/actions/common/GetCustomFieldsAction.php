<?php
namespace gateway\modules\v1\actions\common;

use Exception;
use gateway\modules\v1\components\GetApiAction;
use gateway\modules\v1\forms\common\GetCustomFieldsForm;
use Yii;

class GetCustomFieldsAction extends GetApiAction
{
	/**
	 * Creates request form used to validate request parameters.
	 *
	 * @return GetMenusForm
	 */
	protected function createRequestForm()
	{
            return new GetCustomFieldsForm();
	}

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
                    $client_id = Yii::$app->user->identity->client->id;
                    
                    $client = [];
                    $restaurant = [];
                    $menu_items = [];
                    
                    $custom_fields = \common\models\CustomField::find()
                            ->where(['client_id' => $client_id, 'record_type' => \common\enums\RecordType::Active])->with('customFieldValues')
                            ->all();
                    
                    foreach ($custom_fields as $field) {
                        if ($field->type == \common\enums\CustomFieldType::Client){
                            $client[$field->key] = count($field->customFieldValues) > 0 ? $field->customFieldValues[0]->value : $field->default_value;
                        }
                        else if ($field->type == \common\enums\CustomFieldType::Restaurant){
                            $restaurant[$field->key] = count($field->customFieldValues) > 0 ? $field->customFieldValues[0]->value : $field->default_value;
                        }
                        else {
                            $menu_items[] = $field->key;
                        }
                    }
                    
                    return [
                            'client' => $client, 
                            'restaurant' => $restaurant, 
                            'menu_items' => $menu_items
                           ];
                    
            }
            catch (Exception $ex) {
                    return $ex;
            }
	}
}