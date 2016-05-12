<?php
namespace common\components\identity;
 
use common\enums\UserType;
use common\models\Company;
use common\models\Cuisine;
use common\models\Restaurant;
use common\models\RestaurantChain;
use common\models\RestaurantGroup;
use common\models\User;
use common\models\Voucher;
use Yii;

class RbacHelper
{
    public static function isRestaurantAllowed($user, $restaurant_id) {
        switch ($user->user_type) {
            case (UserType::Admin):
                return true;
            case (UserType::RestaurantAdmin):
            case (UserType::RestaurantApp):
                return $user->restaurant_id == $restaurant_id;
            case (UserType::RestaurantTeam):
                return $user->restaurant_id == $restaurant_id;
            case (UserType::RestaurantGroupAdmin):
                if (!$restaurant_id) {
                    return false;
                }
                $restaurant = Restaurant::getById($restaurant_id);
                if (!$restaurant) {
                    return false;
                }
                return $restaurant->isInGroup($user->restaurant_group_id);
            case (UserType::RestaurantChainAdmin):
                if (!$restaurant_id) {
                    return false;
                }
                $restaurant = Restaurant::getById($restaurant_id);
                if (!$restaurant) {
                    return false;
                }
                return $restaurant->isInChain($user->restaurant_chain_id);
            case (UserType::ClientAdmin):
                if (!$restaurant_id) {
                    return false;
                }
                $restaurant = Restaurant::getById($restaurant_id);
                if (!$restaurant) {
                    return false;
                }
                return $restaurant->isInClient($user->client_id);
            default:
                return false;
        }
    }

    public static function isRestaurantChainAllowed($user, $restaurant_chain_id) {
        switch ($user->user_type) {
            case (UserType::Admin):
                return true;
            case (UserType::RestaurantChainAdmin):
                return $user->restaurant_chain_id == $restaurant_chain_id;
            case (UserType::ClientAdmin):
                $restaurantChain = RestaurantChain::findOne($restaurant_chain_id);
                return $restaurantChain->client_id == $user->client_id;
            default:
                return false;
        }
    }

    public static function isRestaurantGroupAllowed($user, $restaurant_group_id) {
        switch ($user->user_type) {
            case (UserType::Admin):
                return true;
            case (UserType::RestaurantChainAdmin):
                $restaurantGroup = RestaurantGroup::findOne($restaurant_group_id);
                return $user->restaurant_chain_id == $restaurantGroup->restaurant_chain_id;
            case (UserType::RestaurantGroupAdmin):
                return $user->restaurant_group_id == $restaurant_group_id;
            case (UserType::ClientAdmin):
                $restaurantGroup = RestaurantGroup::findOne($restaurant_group_id);
                return $restaurantGroup->restaurantChain->client_id == $user->client_id;
            default:
                return false;
        }
    }

    public static function isClientAllowed($user, $client_id) {
        switch ($user->user_type) {
            case (UserType::Admin):
            case (UserType::Finance):
                return Yii::$app->request->isImpersonated() ? Yii::$app->request->getImpersonatedClientId() == $client_id : false;
            case (UserType::RestaurantAdmin):
            case (UserType::RestaurantTeam):
                return false;
            default:
                return false;
        }
    }

    public static function isCompanyAllowed($user, $company_id) {
        switch ($user->user_type) {
            case (UserType::Admin):
            case (UserType::ClientAdmin):
                $company = Company::findOne($company_id);
                return Yii::$app->request->isImpersonated() ? Yii::$app->request->getImpersonatedClientId() == $company->client_id : false;
            case (UserType::RestaurantAdmin):
            case (UserType::RestaurantTeam):
                return false;
            default:
                return false;
        }
    }

//    public static function isCuisineAllowed($user, $cuisine_id) {
//        switch ($user->user_type) {
//            case (UserType::Admin):
//            case (UserType::ClientAdmin):
//                $cuisine = Cuisine::findOne($cuisine_id);
//                return Yii::$app->request->isImpersonated() ? Yii::$app->request->getImpersonatedClientId() == $cuisine->client_id : false;
//            case (UserType::RestaurantAdmin):
//            case (UserType::RestaurantTeam):
//                return false;
//            default:
//                return false;
//        }
//    }

    public static function allowActionsForRoles(array $actions, array $roles)
    {
        return [
                'allow' => true,
                'actions' => $actions,
                'roles' => $roles
        ];
    }

    public static function allowAllActionsForRoles(array $roles)
    {
        return [
            'allow' => true,
            'roles' => $roles
        ];
    }
    
    public static function allowAllActionsForImpersonatedRoles(array $roles)
    {
        return [
            'allow' => true,
            'roles' => $roles,
            'matchCallback' => function ($rule, $action) {
                return Yii::$app->request->isImpersonated();
            }
        ];
    }

    public static function allowActionsForImpersonatedRoles(array $actions, array $roles)
    {
        return array_merge([
            'actions' => $actions,
        ], static::allowAllActionsForImpersonatedRoles($roles));
    }

    public static function allowActionsForImpersonatedRolesAndSetting(array $actions, array $roles)
    {
        return [
            'allow' => true,
            'actions' => $actions,
            'roles' => $roles,
            'matchCallback' => function ($rule, $action) {
                if (!Yii::$app->request->isImpersonated()){
                    return false;
                }

                if (Yii::$app->user->identity->user_type == UserType::Admin){
                    return true;
                } else {
                    return Yii::$app->user->identity->client->is_corporate_accounts_enabled == 1;
                }
            }
        ];
    }

    public static function allowActionsForCompanyUser($actions, $roles, $company_id)
    {
        return [
            'allow' => true,
            'actions' => $actions,
            'roles' => $roles,
            'matchCallback' => function ($rule, $action) use ($company_id) {
                /** @var User $user */
                if (!isset($company_id)) {
                    return true;
                }
                $user = Yii::$app->user->identity;
                return RbacHelper::isCompanyAllowed($user, $company_id);
            }
        ];
    }

    public static function allowActionsForVoucher($actions, $roles, $voucher_id)
    {
        return [
            'allow' => true,
            'actions' => $actions,
            'roles' => $roles,
            'matchCallback' => function ($rule, $action) use ($voucher_id) {
                /** @var User $user */
                if (!isset($voucher_id)) {
                    return true;
                }
                $voucher = Voucher::findOne($voucher_id);
                return Yii::$app->request->isImpersonated() ? Yii::$app->request->getImpersonatedClientId() == $voucher->client_id : false;
            }
        ];
    }

//    public static function allowActionsForCuisineUser($actions, $roles, $cuisine_id)
//    {
//        return [
//            'allow' => true,
//            'actions' => $actions,
//            'roles' => $roles,
//            'matchCallback' => function ($rule, $action) use ($cuisine_id) {
//                /** @var User $user */
//                if (!isset($company_id)) {
//                    return true;
//                }
//                $user = Yii::$app->user->identity;
//                return RbacHelper::isCuisineAllowed($user, $cuisine_id);
//            }
//        ];
//    }

    public static function allowAllActionsForRestaurantUser($restaurant_id)
    {
        return [
                'allow' => true,
                'matchCallback' => function ($rule, $action) use ($restaurant_id) {
                    /** @var User $user */
                    if (!isset($restaurant_id)) {
                        return true;
                    }
                    $user = Yii::$app->user->identity;
                    return RbacHelper::isRestaurantAllowed($user, $restaurant_id);
                }
        ];
    }

    public static function allowActionsForRestaurantUser($actions,$restaurant_id)
    {
        return array_merge([
            'actions' => $actions
        ], static::allowAllActionsForRestaurantUser($restaurant_id));
    }

    public static function allowActionsForRestaurantGroupUser(array $actions, $restaurant_group_id)
    {
        return [
            'allow' => true,
            'actions' => $actions,
            'matchCallback' => function ($rule, $action) use ($restaurant_group_id) {
                /** @var User $user */
                if (!isset($restaurant_group_id)) {
                    return true;
                }
                $user = Yii::$app->user->identity;
                return RbacHelper::isRestaurantGroupAllowed($user, $restaurant_group_id);
            }
        ];
    }

    public static function allowAllActionsForRestaurantChainUser($restaurant_group_id)
    {
        return [
            'allow' => true,
            'roles' => [UserType::Admin, UserType::ClientAdmin, UserType::RestaurantChainAdmin],
            'matchCallback' => function ($rule, $action) use ($restaurant_group_id) {
                if (!Yii::$app->request->isImpersonated()) {
                    return false;
                }
                /** @var User $user */
                if (!isset($restaurant_group_id)) {
                    return true;
                }
                $user = Yii::$app->user->identity;
                return RbacHelper::isRestaurantChainAllowed($user, $restaurant_group_id);
            }
        ];
    }

}