<?php

use common\components\language\T;
use common\enums\CustomFieldType;
use common\enums\UserType;
use common\models\Client;
use yii\helpers\Html;
use yii\helpers\Url;

$is_restaurant_active = in_array(Yii::$app->controller->id, ['restaurant']);
$is_custom_field_active = in_array(Yii::$app->controller->id, ['custom-field', 'order-rule']);
$is_restaurant_chain_active = in_array(Yii::$app->controller->id, ['restaurant-chain']);
$is_restaurant_visible = in_array(Yii::$app->user->identity->user_type,[UserType::Admin, UserType::ClientAdmin, UserType::RestaurantChainAdmin, UserType::RestaurantGroupAdmin, UserType::RestaurantAdmin, UserType::RestaurantTeam]);
$is_restaurant_chain_visible = in_array(Yii::$app->user->identity->user_type,[UserType::Admin, UserType::ClientAdmin, UserType::RestaurantChainAdmin]);
$is_menu_visible = in_array(Yii::$app->user->identity->user_type,[UserType::Admin, UserType::ClientAdmin]);
$is_cms_visible = in_array(Yii::$app->user->identity->user_type,[UserType::Admin, UserType::ClientAdmin]);
$is_user_visible = in_array(Yii::$app->user->identity->user_type,[UserType::Admin, UserType::ClientAdmin]);
$is_corporate_accounts_enabled = Client::findOne(Yii::$app->request->getImpersonatedClientId())->is_corporate_accounts_enabled == 1;
$is_company_visible = in_array(Yii::$app->user->identity->user_type,[UserType::Admin]) || (in_array(Yii::$app->user->identity->user_type,[UserType::ClientAdmin]) && $is_corporate_accounts_enabled);
?>

<li class="nav-title"><?= T::l('Client Menu') ?></li>
<li class="<?= in_array(Yii::$app->controller->id, ['client']) ? 'active' :'' ?>">
    <a href="<?= \yii\helpers\Url::toRoute(['/client/update', 'id' => Yii::$app->request->getImpersonatedClientId()]) ?>">
        <i class="fa fa-th-large"></i>
        <span class="nav-label"><?= T::l('Client') ?></span>
    </a>
</li>
<li class="<?= in_array(Yii::$app->controller->id, ['order']) ? 'active' :'' ?>">
    <a href="<?= \yii\helpers\Url::toRoute(['/order/index']) ?>">
        <i class="fa fa-th-large"></i>
        <span class="nav-label"><?= T::l('Orders') ?></span>
    </a>
</li>

<li class="<?= in_array(Yii::$app->controller->id, ['postcode-blacklist']) ? 'active' :'' ?>">
    <a href="#">
        <i class="fa fa-th-large"></i>
        <span class="nav-label"><?= Yii::t('label','Addresses') ?></span>
        <span class="fa arrow"></span>
    </a>
    <ul class="nav nav-second-level">
        <li class="<?= Yii::$app->controller->id == 'postcode-blacklist' ? 'active' : '' ?>"><?= Html::a(Yii::t('label','Postcode Blacklist'), ['/postcode-blacklist/index']) ?></li>
    </ul>
</li>

<li class="<?= ($is_custom_field_active)  ? 'active' :'' ?>">
    <a href="#">
        <i class="fa fa-th-large"></i>
        <span class="nav-label"><?= Yii::t('label','Custom Fields') ?></span>
        <span class="fa arrow"></span>
    </a>
    <ul class="nav nav-second-level">
        <li class="<?= Yii::$app->request->get('type') == CustomFieldType::Client ? 'active' : '' ?>"><?= Html::a(Yii::t('label','Client Fields'), ['/custom-field/index', 'type' => CustomFieldType::Client]) ?></li>
        <li class="<?= Yii::$app->request->get('type') == CustomFieldType::Restaurant ? 'active' : '' ?>"><?= Html::a(Yii::t('label','Restaurant Fields'), ['/custom-field/index', 'type' => CustomFieldType::Restaurant]) ?></li>
        <li class="<?= Yii::$app->request->get('type') == CustomFieldType::MenuItem ? 'active' : '' ?>"><?= Html::a(Yii::t('label','Menu Item Fields'), ['/custom-field/index', 'type' => CustomFieldType::MenuItem]) ?></li>
        <li class="<?= Yii::$app->request->get('type') == CustomFieldType::DeliveryCharge ? 'active' : '' ?>"><?= Html::a(Yii::t('label','Delivery Charge Fields'), ['/custom-field/index', 'type' => CustomFieldType::DeliveryCharge]) ?></li>
        <li class="<?= in_array(Yii::$app->controller->id, ['order-rule']) ? 'active' : '' ?>"><?= Html::a(Yii::t('label','Order Rules'), ['/order-rule/index']) ?></li>
    </ul>
</li>

<?php if ($is_restaurant_visible || $is_restaurant_chain_visible): ?>
    <li class="<?= ($is_restaurant_active || $is_restaurant_chain_active)  ? 'active' :'' ?>">
        <a href="#">
            <i class="fa fa-th-large"></i>
            <span class="nav-label"><?= Yii::t('label','Restaurants') ?></span>
            <span class="fa arrow"></span>
        </a>
        <ul class="nav nav-second-level">
            <?php if ($is_restaurant_visible): ?>
                <li class="<?= $is_restaurant_active ? 'active' : '' ?>"><?= Html::a(Yii::t('label','Restaurants'), ['/restaurant/index']) ?></li>
            <?php endif; ?>
            <?php if ($is_restaurant_chain_visible): ?>
                <li class="<?= $is_restaurant_chain_active ? 'active' : '' ?>"><?= Html::a(Yii::t('label','Chains'), ['/restaurant-chain/index']) ?></li>
            <?php endif; ?>
        </ul>
    </li>
<?php endif; ?>

<?php if ($is_menu_visible): ?>
    <li class="<?= in_array(Yii::$app->controller->id, ['menu', 'menu-category', 'menu-item']) ? 'active' :'' ?>">
        <a href="#">
            <i class="fa fa-th-large"></i>
            <span class="nav-label"><?= Yii::t('label','Restaurant Food') ?></span>
            <span class="fa arrow"></span>
        </a>
        <ul class="nav nav-second-level">
            <li class="<?= Yii::$app->controller->id == 'menu' ? 'active' : '' ?>"><?= Html::a(Yii::t('label','Menus'), ['/menu/index']) ?></li>
            <li class="<?= Yii::$app->controller->id == 'menu-category' ? 'active' : '' ?>"><?= Html::a(Yii::t('label','Menu Categories'), ['/menu-category/index']) ?></li>
            <li class="<?= Yii::$app->controller->id == 'menu-item' ? 'active' : '' ?>"><?= Html::a(Yii::t('label','Menu Items'), ['/menu-item/index']) ?></li>
        </ul>
    </li>
<?php endif; ?>
    
<?php if ($is_user_visible): ?>
    <li class="<?= in_array(Yii::$app->controller->id, ['user']) ? 'active' :'' ?>">
        <a href="#">
            <i class="fa fa-th-large"></i>
            <span class="nav-label"><?= Yii::t('label','Users') ?></span>
            <span class="fa arrow"></span>
        </a>
        <ul class="nav nav-second-level">
            <li class="<?= Yii::$app->controller->id == 'user' ? 'active' : '' ?>"><?= Html::a(Yii::t('label','Users'), ['/user/index']) ?></li>
        </ul>
    </li>
<?php endif; ?>

<?php if ($is_company_visible): ?>

    <li class="<?= in_array(Yii::$app->controller->id, ['company']) ? 'active' :'' ?>">
        <a href="<?= \yii\helpers\Url::toRoute(['/company/index']) ?>">
            <i class="fa fa-th-large"></i>
            <span class="nav-label"><?= T::l('Companies') ?></span>
        </a>
    </li>
<?php endif; ?>
    
<li class="<?= in_array(Yii::$app->controller->id, ['voucher','loyalty-points']) ? 'active' :'' ?>">
    <a href="#">
        <i class="fa fa-th-large"></i>
        <span class="nav-label"><?= Yii::t('label','Voucher') ?></span>
        <span class="fa arrow"></span>
    </a>
    <ul class="nav nav-second-level">
        <li class="<?= Yii::$app->controller->id == 'voucher' ? 'active' : '' ?>"><?= Html::a(Yii::t('label','Vouchers'), ['/voucher/index']) ?></li>
        <li class="<?= Yii::$app->controller->id == 'loyalty-points' ? 'active' : '' ?>"><?= Html::a(Yii::t('label','Loyalty Points'), ['/loyalty-points/update']) ?></li>
    </ul>
</li>      

<?php if ($is_cms_visible): ?>
    <li class="<?= in_array(Yii::$app->controller->id, ['page','emailtemplate']) ? 'active' :'' ?>">
        <a href="#">
            <i class="fa fa-th-large"></i>
            <span class="nav-label"><?= Yii::t('label','CMS') ?></span>
            <span class="fa arrow"></span>
        </a>
        <ul class="nav nav-second-level">
            <li class="<?= Yii::$app->controller->id == 'page' ? 'active' : '' ?>"><?= Html::a(Yii::t('label','Pages'), ['/page/index']) ?></li>
            <li class="<?= Yii::$app->controller->id == 'emailtemplate' ? 'active' : '' ?>"><?= Html::a(Yii::t('label','Emails'), ['/emailtemplate/index']) ?></li>
        </ul>
    </li>
<?php endif; ?>

<li class="<?= in_array(Yii::$app->controller->id, ['report']) ? 'active' :'' ?>">
    <a href="#">
        <i class="fa fa-th-large"></i>
        <span class="nav-label"><?= Yii::t('label', 'Finance') ?></span>
        <span class="fa arrow"></span>
    </a>
    <ul class="nav nav-second-level">
        <li class="<?= Yii::$app->controller->id == 'report' ? 'active' : '' ?>"><?= Html::a(Yii::t('label','Report'), ['/report/index']) ?></li>
    </ul>
</li>    
