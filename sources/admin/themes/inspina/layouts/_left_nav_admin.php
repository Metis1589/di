<?php

use common\components\language\T;
use yii\helpers\Html;

$has_client_id = Yii::$app->request->getQueryParam('client_id') != null;
$is_restaurant_active = in_array(Yii::$app->controller->id, ['restaurant']);

?>

<li class="nav-title"><?= T::l('Administration Menu') ?></li>
<li class="<?= in_array(Yii::$app->controller->id, ['order']) ? 'active' :'' ?>">
    <a href="<?= \yii\helpers\Url::toRoute(['/order/index']) ?>">
        <i class="fa fa-th-large"></i>
        <span class="nav-label"><?= T::l('Orders') ?></span>
    </a>
</li>
<li class="<?= in_array(Yii::$app->controller->id, ['client']) ? 'active' :'' ?>">
    <a href="<?= \yii\helpers\Url::toRoute(['/client/index']) ?>">
        <i class="fa fa-th-large"></i>
        <span class="nav-label"><?= T::l('Clients') ?></span>
    </a>
</li>
<li class="<?= $is_restaurant_active ? 'active' :'' ?>">
    <a href="<?= \yii\helpers\Url::toRoute(['/restaurant/index']) ?>">
        <i class="fa fa-th-large"></i>
        <span class="nav-label"><?= T::l('All Restaurants') ?></span>
    </a>
</li>
<li class="<?= in_array(Yii::$app->controller->id, ['cuisine', 'allergy']) ? 'active' :'' ?>">
    <a href="#">
        <i class="fa fa-th-large"></i>
        <span class="nav-label"><?= Yii::t('label','Restaurant Food') ?></span>
        <span class="fa arrow"></span>
    </a>
    <ul class="nav nav-second-level">
        <li class="<?= Yii::$app->controller->id == 'cuisine' ? 'active' : '' ?>"><?= Html::a(Yii::t('label','Cuisines'), ['/cuisine/index']) ?></li>
    </ul>
    <ul class="nav nav-second-level">
        <li class="<?= Yii::$app->controller->id == 'allergy' ? 'active' : '' ?>"><?= Html::a(Yii::t('label','Allergies'), ['/allergy/index']) ?></li>
    </ul>
</li>
<li class="<?= in_array(Yii::$app->controller->id, ['address-base','country', 'seo-area']) ? 'active' :'' ?>">
    <a href="#">
        <i class="fa fa-th-large"></i>
        <span class="nav-label"><?= Yii::t('label','Addresses') ?></span>
        <span class="fa arrow"></span>
    </a>
    <ul class="nav nav-second-level">
        <li class="<?= Yii::$app->controller->id == 'country' ? 'active' : '' ?>"><?= Html::a(Yii::t('label','Countries'), ['/country/index']) ?></li>
        <li class="<?= Yii::$app->controller->id == 'address-base' ? 'active' : '' ?>"><?= Html::a(Yii::t('label','Bases'), ['/address-base/index']) ?></li>
        <li class="<?= Yii::$app->controller->id == 'seo-area' ? 'active' : '' ?>"><?= Html::a(Yii::t('label','Seo Areas'), ['/seo-area/index']) ?></li>
    </ul>
</li>

<li class="<?= in_array(Yii::$app->controller->id, ['label','language']) ? 'active' :'' ?>">
    <a href="#">
        <i class="fa fa-th-large"></i>
        <span class="nav-label"><?= Yii::t('label','Translations') ?></span>
        <span class="fa arrow"></span>
    </a>
    <ul class="nav nav-second-level">
        <li class="<?= Yii::$app->controller->id == 'label' ? 'active' : '' ?>"><?= Html::a(Yii::t('label','Labels'), ['/label/index']) ?></li>
        <li class="<?= Yii::$app->controller->id == 'language' ? 'active' : '' ?>"><?= Html::a(Yii::t('label','Languages'), ['/language/index']) ?></li>
    </ul>
</li>
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
<li class="<?= in_array(Yii::$app->controller->id, ['vat', 'currency']) ? 'active' :'' ?>">
    <a href="#">
        <i class="fa fa-th-large"></i>
        <span class="nav-label"><?= Yii::t('label','Finance') ?></span>
        <span class="fa arrow"></span>
    </a>
    <ul class="nav nav-second-level">
        <li class="<?= Yii::$app->controller->id == 'vat' ? 'active' : '' ?>"><?= Html::a(Yii::t('label','VAT'), ['/vat/index']) ?></li>
        <li class="<?= Yii::$app->controller->id == 'currency' ? 'active' : '' ?>"><?= Html::a(Yii::t('label','Currencies'), ['/currency/index']) ?></li>
    </ul>
</li>