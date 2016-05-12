<?php

namespace common\components;

use Yii;

class IOHelper {

    private static function getPathCreated($path)
    {
        $fullPath = Yii::$app->params['images_upload_path'] . $path;

        if (!file_exists($fullPath)) {
            mkdir($fullPath, 0777, true);
        }

        return $path;
    }

    /**
     * @return mixed
     */
    public static function getRestaurantImagesPath()
    {
        return IOHelper::getPathCreated('restaurant/');
    }
    
    /**
     * @return mixed
     */
    public static function getAllergyImagesPath()
    {
        return IOHelper::getPathCreated('allergy/');
    }
    
        /**
     * @return mixed
     */
    public static function getMenuCategoryImagesPath()
    {
        return IOHelper::getPathCreated('menu-category/');
    }
    
    public static function getMenuItemImagesPath()
    {
        return IOHelper::getPathCreated('menu-item/');
    }

    /**
     * Get path to restaurant logo images
     * @return mixed
     */
    public static function getRestaurantLogoPath()
    {
        return IOHelper::getPathCreated('restaurant/logo/');
    }
}