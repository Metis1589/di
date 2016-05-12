<?php

use yii\helpers\Html;
use admin\common\FilterHelper;
use admin\assets\AngularAsset;
use kartik\grid\GridView;
use common\components\language\T;
use common\enums\RecordType;
use admin\common\AHtml;

/* @var {{order.restaurant.currency.symbol}}this yii\web\View */
/* @var {{order.restaurant.currency.symbol}}searchModel admin\Controllers\Search\OrderSearch */
/* @var {{order.restaurant.currency.symbol}}dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('label', 'Orders');
$this->params['breadcrumbs'][] = $this->title;
\admin\assets\AngularAsset::register($this, ['order', 'apiService', 'userService', 'cookies', 'ngProgress', 'timer']);
?>

<div class="order-list" ng-app="dineinApp" ng-controller="orderInfoController">
    <input type="hidden" id="api_token" value="<?= Yii::$app->params['api_token']; ?>">
    <input type="hidden" id="_gateway_url" value="<?= Yii::$app->params['gateway_url']; ?>">
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
            </div>
            <div class="col-xs-6 text-right">
                <h1><small></small></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4" ng-show="order.restaurant">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h4><?= T::l('Restaurant Info') ?></h4>
                    </div>
                    <div class="panel-body">
                        <p><?= T::l('Name') ?> : <strong>{{order.restaurant.name}}</strong></p>
                        <p><?= T::l('Default Preparation Time') ?> : {{order.restaurant.default_preparation_time}}</p>
                        <p><?= T::l('Default Cook Time') ?> : {{order.restaurant.default_cook_time}}</p>
                         <p style="margin:0px !important;"><?= T::l('Pickup Address') ?>:</p>
                         <ul ng-show="order.restaurant.pickupAddress" class="ul-without-bullet">
                             <li>{{order.restaurant.pickupAddress.building_number}} {{order.restaurant.pickupAddress.address1}}, {{order.restaurant.pickupAddress.address2}}, {{order.restaurant.pickupAddress.postcode}}, {{order.restaurant.pickupAddress.city}}</li>
                             <li>{{order.restaurant.pickupAddress.phone}}</li>
                             <li>{{order.restaurant.pickupAddress.email}}</li>
                         </ul>
                         <p style="margin:0px !important;"><?= T::l('Physical Address') ?>:</p>
                         <ul ng-show="order.restaurant.physicalAddress" class="ul-without-bullet">
                             <li>{{order.restaurant.physicalAddress.building_number}} {{order.restaurant.physicalAddress.address1}}, {{order.restaurant.physicalAddress.address2}}, {{order.restaurant.physicalAddress.postcode}}, {{order.restaurant.physicalAddress.city}}</li>
                             <li>{{order.restaurant.physicalAddress.phone}}</li>
                             <li>{{order.restaurant.physicalAddress.email}}</li>
                         </ul>
                    </div>
                </div>
            </div>

            <div class="col-xs-8">
                <div class="span7">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h4><?= T::l('Order Info') ?></h4>
                        </div>
                        <div class="panel-body">
                            <div class="col-xs-6">
                                <p><?= T::l('Order number') ?> : {{order.order_number}}</p>
                                <p><?= T::l('Utensils') ?> : <span ng-show="order.is_utensils == 1"><?= T::l('Utensisls are needed') ?></span><span ng-show="order.is_utensils == 0"><?= T::l('Do not need utensils') ?></span></p>
                                <p><?= T::l('Total items') ?> : {{total_quantity}}</p>
                                <p><?= T::l('Max cook time') ?> : {{max_cook_time}}</p>
                                <p><?= T::l('Postcode') ?> : {{order.order_number}}</p>
                                <p><?= T::l('Delivery type') ?> : <strong>{{order.delivery_type}}</strong></p>
                                <p><?= T::l('Member comment') ?> : {{order.member_comment}}</p>
                                <p><?= T::l('Restaurant comment') ?> : {{order.restaurant_comment}}</p>
                                <p><?= T::l('Order status') ?> :  <strong>{{order.status}}</strong></p>
                            </div>
                            <div class="col-xs-6">
                                <p><?= T::l('Estimated time') ?> : {{order.estimated_time}}</p>
                                <p><?= T::l('Delivery provided by') ?> : <strong>{{order.delivery_provider}}</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- / end client details section -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>
            <h4><?= T::l('Name') ?></h4>
            </th>
            <th> 
            <h4><?= T::l('Category') ?></h4>
            </th>
            <th>
            <h4><?= T::l('Quantity') ?></h4>
            </th>
            <th>
            <h4><?= T::l('Cook Time') ?></h4>
            </th>
            <th>
            <h4><?= T::l('Item Rest Total') ?></h4>
            </th>
            <th>
            <h4><?= T::l('Item Web Total') ?></h4>
            </th>
            <th>
            <h4><?= T::l('Rest Price') ?></h4>
            </th>
            <th>
            <h4><?= T::l('Web Price') ?></h4>
            </th>
             <th>
            <h4><?= T::l('Rest Total') ?></h4>
            </th>
            <th>
            <h4><?= T::l('Web Total') ?></h4>
            </th>
           

            </tr>
            </thead>
            <tbody ng-repeat="item in order.orderItems">
                <tr>
                    <td>{{item.menuItem.name_key}}</td>
                    <td class="text-right">{{item.menuItem.menuCategory.name_key}}</td>
                    <td class="text-right">{{item.quantity}}</td>
                    <td class="text-right">{{item.menuItem.cook_time}}</td>
                    <td class="text-right">{{order.restaurant.currency.symbol}}{{item.restaurant_total * item.quantity}}</td>
                    <td class="text-right">{{order.restaurant.currency.symbol}}{{item.web_total * item.quantity}}</td>
                    <td class="text-right"><span>{{order.restaurant.currency.symbol}}</span>{{item.restaurant_price}}</td>
                    <td class="text-right"><span>{{order.restaurant.currency.symbol}}</span>{{item.web_price}}</td>
                    <td class="text-right"><span>{{order.restaurant.currency.symbol}}</span>{{item.restaurant_price*item.quantity}}</td>
                    <td class="text-right"><span>{{order.restaurant.currency.symbol}}</span>{{item.web_price*item.quantity}}</td>
                </tr>
                <tr ng-repeat="option in item.orderOptions" ng-show="item.orderOptions.length !== 0">
                    <td class="text-right">{{option.menuOption.name_key}}</td>
                    <td class="text-right"></td>
                    <td class="text-right">{{option.quantity*item.quantity}}</td>
                    <td class="text-right"></td>
                    <td class="text-right"></td>
                    <td class="text-right"></td>
                    <td class="text-right"><span ng-show="option.restaurant_price > 0">{{order.restaurant.currency.symbol}}{{option.restaurant_price}}</span></td>
                    <td class="text-right"><span ng-show="option.web_price > 0">{{order.restaurant.currency.symbol}}{{option.web_price}}</span></td>
                    <td class="text-right"><span ng-show="option.restaurant_price > 0">{{order.restaurant.currency.symbol}}{{option.restaurant_price*option.quantity*item.quantity}}</span></td>
                    <td class="text-right"><span ng-show="option.web_price > 0">{{order.restaurant.currency.symbol}}{{option.web_price*option.quantity*item.quantity}}</span></td>
                </tr>
                <tr ng-if="item.special_instructions">
                    <td class="text-right"> <?= T::l('Special Instructions:') ?></td>
                    <td colspan="9">{{item.special_instructions}}</td>
                </tr>
            </tbody>
        </table>
        <div class="row text-right">
            <div class="col-xs-2 col-xs-offset-8">
                <p>
                    <strong>
                        <?= T::l('Restaurant Total:') ?> <br>
                        <?= T::l('Web Total:') ?> <br>
                        <?= T::l('Delivery charge:') ?> <br>
                        <?= T::l('Payment charge:') ?> <br>
                        <?= T::l('Discount:') ?> <br>
                        <?= T::l('Grand Total:') ?> <br>
                    </strong>
                </p>
            </div>
            <div class="col-xs-2">
                <strong>
                    <span>{{order.restaurant.currency.symbol}}</span>{{order.restaurant_subtotal | number:2}} <br>
                    <span>{{order.restaurant.currency.symbol}}</span>{{order.subtotal | number:2}} <br>
                    <span>{{order.restaurant.currency.symbol}}</span>{{order.delivery_charge | number:2}} <br>
                    <span>{{order.restaurant.currency.symbol}}</span>{{order.payment_charge | number:2}} <br>
                    <span>{{order.restaurant.currency.symbol}}</span>{{order.discount_total | number:2}} <br>
                    <span>{{order.restaurant.currency.symbol}}</span>{{order.total | number:2}} <br>
                </strong>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4" ng-show="order.voucher_data">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h4><?= T::l('Voucher Info') ?></h4>
                    </div>
                    <div class="panel-body">
                        <p><?= T::l('Code') ?> : {{order.voucher_data.code}}</p>
                        <p><?= T::l('Category') ?> : {{order.voucher_data.category}}</p>
                        <p><?= T::l('Promotion type') ?> : {{order.voucher_data.promotion_type}}</p>
                        <p><?= T::l('Start date') ?> : {{order.voucher_data.start_date}}</p>
                        <p><?= T::l('End date') ?> : {{order.voucher_data.end_date}}</p>
                        <p><?= T::l('Discount value') ?> : {{order.voucher_data.discount_value}}</p>
                        <p><?= T::l('Value type') ?> : {{order.voucher_data.value_type}}</p>
                    </div>
                </div>
            </div>
            <div class="col-xs-4" ng-show="order.delivery_address_data">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h4><?= T::l('Delivery Address') ?></h4>
                    </div>
                    <div class="panel-body">
                        <p><?= T::l('First Name') ?> : {{order.delivery_address_data.first_name}}</p>
                        <p><?= T::l('Last Name') ?> : {{order.delivery_address_data.last_name}}</p>
                        <p><?= T::l('City') ?> : {{order.delivery_address_data.city}}</p>
                        <p><?= T::l('Postcode') ?> : {{order.delivery_address_data.postcode}}</p>
                        <p><?= T::l('Address1') ?> : {{order.delivery_address_data.address1}}</p>
                        <p><?= T::l('Phone') ?> : {{order.delivery_address_data.phone}}</p>
                        <p><?= T::l('Driver Instructions') ?> : {{order.delivery_address_data.instructions}}</p>
                    </div>
                </div>
            </div>
            <div class="col-xs-4" ng-show="order.billing_address_data">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h4><?= T::l('Billing Address') ?></h4>
                    </div>
                    <div class="panel-body">
                        <p><?= T::l('First Name') ?> : {{order.billing_address_data.first_name}}</p>
                        <p><?= T::l('Last Name') ?> : {{order.billing_address_data.last_name}}</p>
                        <p><?= T::l('City') ?> : {{order.billing_address_data.city}}</p>
                        <p><?= T::l('Postcode') ?> : {{order.billing_address_data.postcode}}</p>
                        <p><?= T::l('Address1') ?> : {{order.billing_address_data.address1}}</p>
                        <p><?= T::l('Phone') ?> : {{order.billing_address_data.phone}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

