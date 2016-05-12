<?php
namespace gateway\modules\v1\controllers;

use common\components\language\T;
use common\enums\UserType;
use yii\rest\Controller;
use common\components\identity\RbacHelper;

class CommonController extends Controller
{

    /**
     * Returns array of controller actions.
     *
     * @return array
     */
    public function actions()
    {
        return [
            'get-restaurant'             => 'gateway\modules\v1\actions\common\GetRestaurantAction',
            'restaurants-search'         => 'gateway\modules\v1\actions\common\RestaurantsSearchAction',
            'get-menus'                  => 'gateway\modules\v1\actions\common\GetMenusAction',
            'reorder'                    => 'gateway\modules\v1\actions\common\ReorderAction',
            'get-order'                  => 'gateway\modules\v1\actions\common\GetOrderAction',
            'get-order-status'           => 'gateway\modules\v1\actions\common\GetOrderStatusAction',
            'set-order-item'             => 'gateway\modules\v1\actions\common\SetOrderItemAction',
            'set-voucher'                => 'gateway\modules\v1\actions\common\SetVoucherAction',
            'set-driver-charge'          => 'gateway\modules\v1\actions\common\SetDriverChargeAction',
            'get-client-data'            => 'gateway\modules\v1\actions\common\GetClientDataAction',
            'get-client-data-load-time'  => 'gateway\modules\v1\actions\common\GetClientDataLoadTimeAction',
            'checkout'                   => 'gateway\modules\v1\actions\common\CheckoutAction',
            'login'                      => 'gateway\modules\v1\actions\common\LoginAction',
            'internal-login'             => 'gateway\modules\v1\actions\common\InternalLoginAction',
            'logout'                     => 'gateway\modules\v1\actions\common\LogoutAction',
            'register'                   => 'gateway\modules\v1\actions\common\RegisterAction',
            'request-password-reset'     => 'gateway\modules\v1\actions\common\RequestPasswordResetAction',
            'password-reset'             => 'gateway\modules\v1\actions\common\PasswordResetAction',
            'activate-account'           => 'gateway\modules\v1\actions\common\ActivateAccountAction',
            'get-user-profile'           => 'gateway\modules\v1\actions\common\GetUserProfileAction',
            'set-user-profile'           => 'gateway\modules\v1\actions\common\SetUserProfileAction',
            'get-user-addresses'         => 'gateway\modules\v1\actions\common\GetUserAddressesAction',
            'get-user-orders'            => 'gateway\modules\v1\actions\common\GetUserOrdersAction',
            'get-postcode'               => 'gateway\modules\v1\actions\common\GetPostcodeAction',
            'save-user-address'          => 'gateway\modules\v1\actions\common\SaveUserAddressAction',
            'contact-us'                 => 'gateway\modules\v1\actions\common\ContactUsAction',
            'get-order-list'             => 'gateway\modules\v1\actions\common\GetOrderListAction',
            'update-order-status'        => 'gateway\modules\v1\actions\common\UpdateOrderStatusAction',
            'update-order-refund'        => 'gateway\modules\v1\actions\common\UpdateOrderRefundAction',
            'get-internal-order'         => 'gateway\modules\v1\actions\common\GetInternalOrderAction',
            'corp-get-users'             => 'gateway\modules\v1\actions\common\CorpGetUsersAction',
            'corp-remove-user'           => 'gateway\modules\v1\actions\common\CorpRemoveUserAction',
            'corp-set-user'              => 'gateway\modules\v1\actions\common\CorpSetUserAction',
            'corp-set-user-data'         => 'gateway\modules\v1\actions\common\CorpSetUserDataAction',
            'corp-get-expense-types'     => 'gateway\modules\v1\actions\common\GetExpenseTypesAction',
            'payment-notification'       => 'gateway\modules\v1\actions\common\PaymentNotificationAction',
            'save-payment'               => 'gateway\modules\v1\actions\common\SavePaymentAction',
            'generate-voucher-by-points' => 'gateway\modules\v1\actions\common\GenerateVoucherByPointsAction',
            'add-review'                 => 'gateway\modules\v1\actions\common\AddReviewAction',
            'get-reviews-by-user'        => 'gateway\modules\v1\actions\common\GetReviewsByUserAction',
            'get-reviews-by-restaurant'  => 'gateway\modules\v1\actions\common\GetReviewsByRestaurantAction',
            'get-suggestion-data'        => 'gateway\modules\v1\actions\common\GetSuggestionDataAction',
            'suggest-restaurant'         => 'gateway\modules\v1\actions\common\SuggestRestaurantAction',
            'signup-restaurant'          => 'gateway\modules\v1\actions\common\SignUpRestaurantAction',
            'get-delivery-charge'        => 'gateway\modules\v1\actions\common\GetDeliveryChargeAction',
            'report-generate-pl'         => 'gateway\modules\v1\actions\common\ReportGeneratePLAction',
            'get-delivery-time'          => 'gateway\modules\v1\actions\common\GetDeliveryTimeAction',
            'ivr-router'                 => 'gateway\modules\v1\actions\common\IvrRouterAction',
            'get-custom-fields'          => 'gateway\modules\v1\actions\common\GetCustomFieldsAction',
            'update-order-item-display'  => 'gateway\modules\v1\actions\common\UpdateOrderItemDisplayAction',
            'set-menu-item-record-type'  => 'gateway\modules\v1\actions\common\SetMenuItemRecordTypeAction',
        ];
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    RbacHelper::allowActionsForRoles(['get-restaurant'],      [ UserType::UNAUTHORIZED, UserType::Member, UserType::CorporateMember ]),
                    RbacHelper::allowActionsForRoles(['restaurants-search'],  [ UserType::UNAUTHORIZED, UserType::Member, UserType::CorporateMember ]),
                    RbacHelper::allowActionsForRoles(['get-menus'],           [ UserType::UNAUTHORIZED, UserType::Member, UserType::CorporateMember, UserType::RestaurantApp ]),
                    RbacHelper::allowActionsForRoles(['reorder'],             [ UserType::Member, UserType::CorporateMember ]),
                    RbacHelper::allowActionsForRoles(['get-order'],           [ UserType::UNAUTHORIZED, UserType::Member, UserType::CorporateMember ]),
                    RbacHelper::allowActionsForRoles(['get-order-status'],    [ UserType::UNAUTHORIZED, UserType::Member, UserType::CorporateMember ]),
                    RbacHelper::allowActionsForRoles(['set-order-item'],      [ UserType::UNAUTHORIZED, UserType::Member, UserType::CorporateMember ]),
                    RbacHelper::allowActionsForRoles(['set-voucher'],         [ UserType::UNAUTHORIZED, UserType::Member, UserType::CorporateMember ]),
                    RbacHelper::allowActionsForRoles(['set-driver-charge'],   [ UserType::UNAUTHORIZED, UserType::Member, UserType::CorporateMember ]),
                    RbacHelper::allowActionsForRoles(['get-client-data'],     [ UserType::UNAUTHORIZED ]),
                    RbacHelper::allowActionsForRoles(['get-client-data-load-time'], [ UserType::UNAUTHORIZED ]),
                    RbacHelper::allowActionsForRoles(['checkout'],            [ UserType::UNAUTHORIZED, UserType::Member, UserType::CorporateMember ]),
                    RbacHelper::allowActionsForRoles(['login'],               [ UserType::UNAUTHORIZED, UserType::Member, UserType::CorporateMember ]),
                    RbacHelper::allowActionsForRoles(['internal-login'],      [ UserType::UNAUTHORIZED, UserType::Admin ]),
                    RbacHelper::allowActionsForRoles(['logout'],              [ UserType::Member, UserType::CorporateMember,UserType::RestaurantApp ]),
                    RbacHelper::allowActionsForRoles(['register'],            [ UserType::UNAUTHORIZED ]),
                    RbacHelper::allowActionsForRoles(['password-reset'],      [ UserType::UNAUTHORIZED, UserType::Member, UserType::CorporateMember ]),
                    RbacHelper::allowActionsForRoles(['request-password-reset'],      [ UserType::UNAUTHORIZED ]),
                    RbacHelper::allowActionsForRoles(['activate-account'],    [ UserType::UNAUTHORIZED ]),
                    RbacHelper::allowActionsForRoles(['get-user-profile'],    [ UserType::Member, UserType::CorporateMember ]),
                    RbacHelper::allowActionsForRoles(['set-user-profile'],    [ UserType::Member, UserType::CorporateMember ]),
                    RbacHelper::allowActionsForRoles(['get-user-addresses'],  [ UserType::Member, UserType::CorporateMember ]),
                    RbacHelper::allowActionsForRoles(['get-user-orders'],     [ UserType::Member, UserType::CorporateMember ]),
                    RbacHelper::allowActionsForRoles(['save-user-address'],   [ UserType::Member, UserType::CorporateMember ]),
                    RbacHelper::allowActionsForRoles(['contact-us'],          [ UserType::UNAUTHORIZED, UserType::Member, UserType::CorporateMember ]),
                    RbacHelper::allowActionsForRoles(['get-postcode'],        [ UserType::UNAUTHORIZED ]),
                    RbacHelper::allowActionsForRoles(['get-order-list'],      [ UserType::Admin, UserType::ClientAdmin, UserType::RestaurantApp ]),
                    RbacHelper::allowActionsForRoles(['update-order-status'], [ UserType::Admin, UserType::ClientAdmin, UserType::RestaurantApp ]),
                    RbacHelper::allowActionsForRoles(['update-order-refund'], [ UserType::Admin, UserType::ClientAdmin ]),
                    RbacHelper::allowActionsForRoles(['get-internal-order'],  [ UserType::Admin, UserType::ClientAdmin, UserType::RestaurantApp ]),
                    RbacHelper::allowActionsForRoles(['corp-get-users', 'corp-remove-user', 'corp-set-user', 'corp-set-user-data', 'corp-get-expense-types'], [ UserType::CorporateMember ]),
                    RbacHelper::allowActionsForRoles(['payment-notification'],       [ UserType::UNAUTHORIZED ]),
                    RbacHelper::allowActionsForRoles(['save-payment'],               [ UserType::UNAUTHORIZED, UserType::CorporateMember, UserType::Member ]),
                    RbacHelper::allowActionsForRoles(['generate-voucher-by-points'], [ UserType::CorporateMember, UserType::Member ]),
                    RbacHelper::allowActionsForRoles(['add-review'],                 [ UserType::UNAUTHORIZED, UserType::CorporateMember, UserType::Member ]),
                    RbacHelper::allowActionsForRoles(['get-reviews-by-user'],        [ UserType::CorporateMember, UserType::Member ]),
                    RbacHelper::allowActionsForRoles(['get-reviews-by-restaurant'],  [ UserType::UNAUTHORIZED, UserType::CorporateMember, UserType::Member ]),
                    RbacHelper::allowActionsForRoles(['get-suggestion-data'],        [ UserType::UNAUTHORIZED, UserType::CorporateMember, UserType::Member ]),
                    RbacHelper::allowActionsForRoles(['suggest-restaurant'],         [ UserType::UNAUTHORIZED, UserType::CorporateMember, UserType::Member ]),
                    RbacHelper::allowActionsForRoles(['signup-restaurant'],          [ UserType::UNAUTHORIZED, UserType::CorporateMember, UserType::Member ]),
                    RbacHelper::allowActionsForRoles(['get-delivery-charge'],        [ UserType::UNAUTHORIZED, UserType::CorporateMember, UserType::Member ]),
                    RbacHelper::allowActionsForRoles(['get-delivery-time'],          [ UserType::UNAUTHORIZED, UserType::CorporateMember, UserType::Member ]),
                    RbacHelper::allowActionsForRoles(['report-generate-pl'],         [ UserType::UNAUTHORIZED, UserType::Admin, UserType::ClientAdmin ]), //todo adjust
                    RbacHelper::allowActionsForRoles(['ivr-router'],                 [ UserType::UNAUTHORIZED ]),
                    RbacHelper::allowActionsForRoles(['get-custom-fields'],          [ UserType::RestaurantApp ]),
                    RbacHelper::allowActionsForRoles(['update-order-item-display'],  [ UserType::RestaurantApp ]),
                    RbacHelper::allowActionsForRoles(['set-menu-item-record-type'],  [ UserType::RestaurantApp ]),
                ],
                'denyCallback' => function ($rule, $action) {
                    header('Content-Type: application/json');
                    print json_encode(['status_code' => 500, 'error_message' => T::e('NO_PERMISSIONS'), 'data' => null]);
                    die();
                }
            ]
        ];
    }
}