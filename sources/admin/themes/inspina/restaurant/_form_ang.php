<?php

use admin\common\AHtml;
use common\components\language\T;
use yii\helpers\Html;

?>

<div class="restaurant-details-form" ng-controller="restaurantDetailsController">

    <form editable-form name="tableform" onaftersave="saveTable()" oncancel="cancel()">

        <?= AHtml::waitSpinner(['ng-show' => 'restaurantDetailsFormIsSubmitting']) ?>

        <div class="row">
            <div class="col-md-6">
                <?= AHtml::input('Restaurant name',
                    ['type'=>'text', 'maxlength'=>'500', 'id'=>'restaurant_name', 'required'=>'', 'ng-model'=>'restaurant.name'],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                ) ?>

                <span>DDB</span>
                <?= AHtml::input('Trading name',
                    ['type'=>'text', 'maxlength'=>'500', 'id'=>'trading_name', 'required'=>'', 'ng-model'=>'restaurant.trading_name'],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                ) ?>

                <?= AHtml::input('Restaurant base',
                    ['type'=>'text', 'maxlength'=>'500', 'id'=>'address_base_id', 'required'=>'', 'ng-model'=>'restaurant.address_base_id'],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                ) ?>

                <?= AHtml::input('Restaurant group',
                    ['type'=>'text', 'maxlength'=>'500', 'id'=>'restaurant_group_id', 'required'=>'', 'ng-model'=>'restaurant.restaurant_group_id'],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                ) ?>
                
                <?= AHtml::input('Logo',
                    ['type'=>'text', 'maxlength'=>'500', 'id'=>'restaurant_group_id', 'required'=>'', 'ng-model'=>'restaurant.restaurant_photo.image_name'],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                ) ?>
            </div>
            <div class="col-md-6">
                <?= AHtml::input('Is Featured',
                    ['type'=>'checkbox', 'id'=>'is_featured', 'ng-model'=>'restaurant.is_featured']
                ) ?>

                <?= AHtml::input('Is New',
                    ['type'=>'checkbox', 'id'=>'is_new', 'ng-model'=>'restaurant.is_newest']
                ) ?>

                <span>DDB</span>
                <?= AHtml::input('Currency',
                    ['type'=>'text', 'maxlength'=>'500', 'id'=>'restaurant_group_id', 'required'=>'', 'ng-model'=>'restaurant.currency'],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                ) ?>

                <?= AHtml::input('Openned Date',
                    ['type'=>'text', 'maxlength'=>'500', 'id'=>'opening_date', 'required'=>'', 'ng-model'=>'restaurant.opening_date'],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                ) ?>
                
                <span>DDB</span>
                <?= AHtml::input('VAT Number',
                    ['type'=>'text', 'maxlength'=>'500', 'id'=>'vat_number', 'required'=>'', 'ng-model'=>'restaurant.vat_number'],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                ) ?>
                
            </div>  
        </div>
        

        <h3><?= T::l('Addresses') ?></h3>
        <div class="row">
            <div class="col-md-6">
                <?= AHtml::input('Address 1',
                    ['type'=>'text', 'maxlength'=>'500', 'id'=>'restaurant_contact_first_name', 'required'=>'', 'ng-model'=>'restaurant.restaurantadress.address.address1'],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                ) ?>

                <?= AHtml::input('Address 2',
                    ['type'=>'text', 'maxlength'=>'500', 'id'=>'restaurant_contact_last_name', 'required'=>'', 'ng-model'=>'restaurant.restaurantadress.address.last_name'],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                ) ?>
                
                <?= AHtml::input('House number',
                    ['type'=>'text', 'maxlength'=>'500', 'id'=>'restaurant_contact_role', 'required'=>'', 'ng-model'=>'restaurant.restaurantadress.address.role'],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                ) ?>
                
                <?= AHtml::input('City',
                    ['type'=>'text', 'maxlength'=>'500', 'id'=>'restaurant_address_city', 'required'=>'', 'ng-model'=>'restaurant.restaurantadress.address.role'],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                ) ?>
                
                <?= AHtml::input('Post code',
                    ['type'=>'text', 'maxlength'=>'500', 'id'=>'restaurant_address_postcode', 'required'=>'', 'ng-model'=>'restaurant.restaurantadress.address.role'],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                ) ?>
                
                <?= AHtml::input('Country',
                    ['type'=>'text', 'maxlength'=>'500', 'id'=>'restaurant_address_country', 'required'=>'', 'ng-model'=>'restaurant.restaurantadress.address.role'],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                ) ?>
                
                <?= AHtml::input('Phone',
                    ['type'=>'text', 'maxlength'=>'500', 'id'=>'restaurant_address_phone', 'required'=>'', 'ng-model'=>'restaurant.restaurantadress.address.role'],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                ) ?>
            </div>
            <div class="col-md-6">
                <?= AHtml::input('Latitude',
                    ['type'=>'text', 'maxlength'=>'500', 'id'=>'restaurant_address_phone', 'required'=>'', 'ng-model'=>'restaurant.restaurantadress.address.latitude'],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                ) ?>
                <?= AHtml::input('Longtitude',
                    ['type'=>'text', 'maxlength'=>'500', 'id'=>'restaurant_address_phone', 'required'=>'', 'ng-model'=>'restaurant.restaurantadress.address.longtitude'],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                ) ?>
            </div>  
        </div>
        
        <h3><?= T::l('Contact') ?></h3>
        <div class="row">
            <div class="col-md-6">
                <?= AHtml::input('First name',
                    ['type'=>'text', 'maxlength'=>'500', 'id'=>'restaurant_contact_first_name', 'required'=>'', 'ng-model'=>'restaurant.contact.first_name'],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                ) ?>

                <?= AHtml::input('Last name',
                    ['type'=>'text', 'maxlength'=>'500', 'id'=>'restaurant_contact_last_name', 'required'=>'', 'ng-model'=>'restaurant.contact.last_name'],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                ) ?>
                
                <?= AHtml::input('Role',
                    ['type'=>'text', 'maxlength'=>'500', 'id'=>'restaurant_contact_role', 'required'=>'', 'ng-model'=>'restaurant.contact.role'],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                ) ?>
                
                <?= AHtml::input('Email',
                    ['type'=>'text', 'maxlength'=>'500', 'id'=>'restaurant_contact_email', 'required'=>'', 'ng-model'=>'restaurant.contact.email'],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                ) ?>

                <?= AHtml::input('Phone',
                    ['type'=>'text', 'maxlength'=>'500', 'id'=>'restaurant_contact_phone', 'required'=>'', 'ng-model'=>'restaurant.contact.phone'],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                ) ?>
            </div>
            <div class="col-md-6">
            </div>  
        </div>
        
        <h3><?= T::l('Billing') ?></h3>
        <div class="row">
            <div class="col-md-6">
                <?= AHtml::input('First name',
                    ['type'=>'text', 'maxlength'=>'500', 'id'=>'restaurant_billing_first_name', 'required'=>'', 'ng-model'=>'restaurant.billing.first_name'],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                ) ?>

                <?= AHtml::input('Last name',
                    ['type'=>'text', 'maxlength'=>'500', 'id'=>'restaurant_billing_last_name', 'required'=>'', 'ng-model'=>'restaurant.billing.last_name'],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                ) ?>
                
                <?= AHtml::input('Role',
                    ['type'=>'text', 'maxlength'=>'500', 'id'=>'restaurant_billing_role', 'required'=>'', 'ng-model'=>'restaurant.billing.role'],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                ) ?>
                
                <?= AHtml::input('Email',
                    ['type'=>'text', 'maxlength'=>'500', 'id'=>'restaurant_billing_email', 'required'=>'', 'ng-model'=>'restaurant.billing.email'],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                ) ?>

                <?= AHtml::input('Phone',
                    ['type'=>'text', 'maxlength'=>'500', 'id'=>'restaurant_billing_phone', 'required'=>'', 'ng-model'=>'restaurant.billing.phone'],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                ) ?>
            </div>
            <div class="col-md-6">
            </div>  
        </div>
        
        <h3><?= T::l('SEO') ?></h3>
        <div class="row">
            <div class="col-md-6">
                <?= AHtml::input('SLUG',
                    ['type'=>'text', 'maxlength'=>'500', 'id'=>'restaurant_slug', 'required'=>'', 'ng-model'=>'restaurant.slug'],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                ) ?>

                <?= AHtml::input('Title',
                    ['type'=>'text', 'maxlength'=>'500', 'id'=>'restaurant_seo_title', 'required'=>'', 'ng-model'=>'restaurant.seo_title'],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                ) ?>

                <span>DDB</span>
                <?= AHtml::input('Area',
                    ['type'=>'text', 'maxlength'=>'500', 'id'=>'address_base_id', 'required'=>'', 'ng-model'=>'restaurant.address_base_id'],
                    ['form-name' => 'tableform', 'rules' => []]
                ) ?>

                <?= AHtml::input('Meta text',
                    ['type'=>'text', 'maxlength'=>'500', 'id'=>'restaurant_meta_description', 'ng-model'=>'restaurant.meta_description'],
                    ['form-name' => 'tableform', 'rules' => []]
                ) ?>
                
                <?= AHtml::input('Meta keywords',
                    ['type'=>'text', 'maxlength'=>'500', 'id'=>'restaurant_meta_keywords', 'required'=>'', 'ng-model'=>'restaurant.meta_keywords'],
                    ['form-name' => 'tableform', 'rules' => []]
                ) ?>
            </div>
            <div class="col-md-6">
                
            </div>  
        </div>
        
        <h3><?= T::l('Food') ?></h3>
        <div class="row">
            <div class="col-md-6">
                <span>DDB</span>
                <?= AHtml::input('Restaurant description',
                    ['type'=>'text', 'id'=>'restaurant_description', 'required'=>'', 'ng-model'=>'restaurant.restaurant_description'],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                ) ?>

                <span>DDB</span>
                <?= AHtml::input('Price range',
                    ['type'=>'text', 'maxlength'=>'500', 'id'=>'restaurant_seo_title', 'required'=>'', 'ng-model'=>'restaurant.price_range'],
                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                ) ?>

                <span>DDB</span>
                <?= AHtml::input('Default cook time',
                    ['type'=>'text', 'maxlength'=>'500', 'id'=>'address_base_id', 'required'=>'', 'ng-model'=>'restaurant.avg_prepare_time'],
                    ['form-name' => 'tableform', 'rules' => []]
                ) ?>

                <?= AHtml::input('Default preparation time',
                    ['type'=>'text', 'maxlength'=>'500', 'id'=>'restaurant_meta_description', 'ng-model'=>'restaurant.default_food_prep_time'],
                    ['form-name' => 'tableform', 'rules' => []]
                ) ?>
            </div>
            <div class="col-md-6">
                
            </div>  
        </div>
        
        <?= AHtml::errorNotification('{{submitError}}', ['ng-show' => 'hasSubmitError()']) ?>

        <?= AHtml::saveButton(['ng-click' => 'saveRestaurantDetails()']) ?>

    </form>


</div>
