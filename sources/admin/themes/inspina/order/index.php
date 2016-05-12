<?php

use common\components\language\T;
use admin\common\AHtml;
use common\enums\DeliveryProvider;
use common\enums\DeliveryType;
use common\enums\OrderStatus;

/* @var $this yii\web\View */
/* @var $searchModel admin\Controllers\Search\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('label', 'Orders');
$this->params['breadcrumbs'][] = $this->title;
\admin\assets\AngularAsset::register($this, ['timer', 'order', 'apiService', 'userService', 'cookies', 'ngProgress','timepicker']);
?>
<style>
    ul
    {
        list-style-type: none;
        margin: 0px !important;
        padding: 5px !important;
    }
    .ui-datepicker {
        z-index: 2000 !important;
    }

</style>

<div class="order-list" ng-app="dineinApp" ng-controller="orderController">
    <input type="hidden" id="client_key" value="<?= Yii::$app->request->isImpersonated() ? Yii::$app->globalCache->getClientById(Yii::$app->request->getImpersonatedClientId())['key'] : '' ?>"/>
    <input type="hidden" id="_gateway_url" value="<?= Yii::$app->params['gateway_url']; ?>">
    <input type="hidden" id="api_token" value="<?= Yii::$app->user->identity->api_token; ?>">
    <div class="row">
        <div class="col-xs-12">
            <div class="ibox float-e-margins">
                <div class="ibox-progress-title">
                    <timer id="countdown" interval="1000" countdown="60" autostart="true">
                        Remaining time to update: {{countdown}} second{{secondsS}}.
                    </timer>
                    Total: {{orders.length}}
                </div>
                <div class="ibox-content" style="overflow: auto;">
                    <table class="table table-bordered" id="order-table">
                        <thead>
                            <tr>
                                <th><?= T::l('Actions') ?></th>
                                <th><?= T::l('Order Details') ?></th>
                                <th><?= T::l('Status') ?></th>
                                <th><?= T::l('Delivery Address') ?></th>
                                <th><?= T::l('Member Comment') ?></th>
                                <th><?= T::l('Restaurant') ?></th>
                                <th><?= T::l('Internal Notes') ?></th>
                                <th><?= T::l('Client Refund') ?></th>
                                <th><?= T::l('Client Cost') ?></th>
                                <th><?= T::l('Client Received') ?></th>
                                <th><?= T::l('Rest Notes') ?></th>
                                <th><?= T::l('Rest Charge') ?></th>
                                <th><?= T::l('Rest Refund') ?></th>
                                <th><?= T::l('Rest Credit') ?></th>
                                <th><?= T::l('Corp Client Refund') ?></th>
                                <th><?= T::l('Corp Restaurant Refund') ?></th>
                                <th><?= T::l('Billing Address') ?></th>
                            </tr>
                        </thead>
                        <tbody ng-show="(orders | filter:filterOrders).length > 0">
                            <tr ng-repeat="order in orders">
                        
                            <td>
                                <?= AHtml::saveButton(['ng-click' => 'save(order, $index)', 'ng-disabled' => '!order.isChanged']) ?>
                                <?= AHtml::saveButton(['ng-click' => 'info(order)'], 'Info') ?>
                                <?= AHtml::saveButton(['ng-click' => 'refund(order)', 'class' => 'btn btn-primary', 'ng-show' => 'order.current_status != "ProcessingPayment" && order.auth_result == "AUTHORISED"'], 'Refund') ?>
                            </td>
                            <td>
                                <ul>
                                    <li>{{order.order_number}}</li>
                                    <li><strong>{{order.restaurant.name}}</strong></li>
                                    <li><strong>{{order.orderItems.length}}</strong></li>
                                    <li>{{order.delivery_type}}</li>
                                    <li ng-show="order.delivery_type === 'DeliveryLater'">{{order.later_date}}</li>
                                    <li><?= T::l('Total') ?> : {{order.total}}</li>
                                    <li><?= T::l('Food Prep') ?> : {{order.food_preparation_time}}</li>
                                    <li ng-show="order.is_corporate == 0"><?= T::l('Retail / Single') ?></li>
                                    <li ng-show="order.is_corporate == 1"><?= T::l('Corp / Single') ?></li>
                                </ul>
                            </td>
                            <td>
                                <div style="width:180px !important">

                                    <?= AHtml::input('', [
                                        'type' => 'select',
                                        'items' => OrderStatus::getAllowedStatusesLabelsCDAL(),
                                        'ng-if'=>'order.delivery_provider == "'.DeliveryProvider::Client.'" && (order.delivery_type == "'. DeliveryType::DeliveryAsap .'" || order.delivery_type == "'. DeliveryType::DeliveryLater .'")',
                                        'ng-change' => 'changeOrderStatus(order)',
                                        'ng-confirm-click' => T::l('Are you sure to update order status?'),
                                        'ng-model' => 'order.current_status',
                                        'template' => '{input}'], []) ?>

                                    <?= AHtml::input('', [
                                        'type' => 'select',
                                        'items' => OrderStatus::getAllowedStatusesLabelsRDAL(),
                                        'ng-if'=>'order.delivery_provider == "'.DeliveryProvider::Restaurant.'" && (order.delivery_type == "'. DeliveryType::DeliveryAsap .'" || order.delivery_type == "'. DeliveryType::DeliveryLater .'")',
                                        'ng-change' => 'changeOrderStatus(order)',
                                        'ng-confirm-click' => T::l('Are you sure to update order status?'),
                                        'ng-model' => 'order.current_status',
                                        'template' => '{input}'], []) ?>

                                    <?= AHtml::input('', [
                                        'type' => 'select',
                                        'items' => OrderStatus::getAllowedStatusesLabelsCAL(),
                                        'ng-if'=>'order.delivery_type == "'. DeliveryType::CollectionAsap .'" || order.delivery_type == "'. DeliveryType::CollectionLater .'"',
                                        'ng-change' => 'changeOrderStatus(order)',
                                        'ng-confirm-click' => T::l('Are you sure to update order status?'),
                                        'ng-model' => 'order.current_status',
                                        'template' => '{input}'], []) ?>

                                    <ul ng-show="(order.orderHistories)" >
                                        <li ng-repeat="history in order.orderHistories">
                                            <div class="row">
                                                <div class="col-xs-4 text-right" style="padding:1px !important">  
                                                    <strong>{{history.status}}</strong>
                                                </div>
                                                <div class="col-xs-8" style="padding:1px !important">
                                                    {{history.create_on}} - {{history.user.username}}
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                            <td style="max-width:250px !important">
                                <ul ng-show="order.delivery_address_data">
                                    <li><strong>{{order.delivery_address_data.first_name}} {{order.delivery_address_data.last_name}}</strong></li>
                                    <li>{{order.delivery_address_data.building_number}} {{order.delivery_address_data.address1}}, {{order.delivery_address_data.address2}}, {{order.delivery_address_data.postcode}}, {{order.delivery_address_data.city}}</li>
                                    <li>{{order.delivery_address_data.phone}}</li>
                                    <li>{{order.delivery_address_data.email}}</li>
                                </ul>

                            </td>
                            <td>{{order.member_comment}}</td>
                            <td style="max-width:250px !important">
                                <ul ng-show="(order.restaurant.pickupAddress)">
                                    <li><strong>{{order.restaurant.name}}</strong></li>
                                    <li>{{order.restaurant.pickupAddress.building_number}} {{order.restaurant.pickupAddress.address1}}, {{order.restaurant.pickupAddress.address2}}, {{order.restaurant.pickupAddress.postcode}}, {{order.restaurant.pickupAddress.city}}</li>
                                    <li>{{order.restaurant.pickupAddress.phone}}</li>
                                    <li>{{order.restaurant.pickupAddress.email}}</li>
                                </ul>
                            </td>
                            <td style="width:150px !important">
                                <textarea style="width:150px !important" elastic ng-model="order.internal_comment" rows="6" ng-change="isChanged = false" ng-required = 'order.client_refund_diff > 0 || order.restaurant_refund_diff > 0'></textarea>
                            </td>
                            <td>
                                <?= AHtml::input('', ['maxlength' => '255', 'ng-change' => 'order.isChanged = true', 'ng-disabled' => 'order.current_status == "ProcessingPayment" || order.auth_result != "AUTHORISED"', 'ng-model' => 'order.client_refund_diff', 'ng-pattern'=>'/^[0-9]{1,7}(\.[0-9]+)?$/', 'ng-required' => 'getTotal(order)','template' => '{input}'], []) ?>
                                <div class="text-right">
                                    <span>{{order.client_refund}}</span>
                                </div>
                            </td>
                            <td>
                                <?= AHtml::input('', ['maxlength' => '255', 'ng-change' => 'order.isChanged = true', 'ng-model' => 'order.client_cost', 'ng-pattern'=>'/^[0-9]{1,7}(\.[0-9]+)?$/', 'template' => '{input}'], []) ?>
                            </td>
                            <td>
                                <?= AHtml::input('', ['maxlength' => '255', 'ng-change' => 'order.isChanged = true', 'ng-model' => 'order.client_received', 'ng-pattern'=>'/^[0-9]{1,7}(\.[0-9]+)?$/', 'template' => '{input}'], []) ?>
                            </td>
                            <td style="width:150px !important">
                                <textarea style="width:150px !important" elastic ng-model="order.restaurant_comment" rows="6" ng-change="order.isChanged = true"></textarea>
                            </td>
                            <td>
                                <?= AHtml::input('', ['maxlength' => '255', 'ng-change' => 'order.isChanged = true', 'ng-model' => 'order.restaurant_charge', 'ng-pattern'=>'/^[0-9]{1,7}(\.[0-9]+)?$/', 'template' => '{input}'], []) ?>
                            </td>
                            <td>
                                <?= AHtml::input('', ['maxlength' => '255', 'ng-change' => 'order.isChanged = true', 'ng-model' => 'order.restaurant_refund_diff', 'ng-disabled' => 'order.current_status == "ProcessingPayment" || order.auth_result != "AUTHORISED"', 'ng-pattern'=>'/^[0-9]{1,7}(\.[0-9]+)?$/', 'ng-required' => 'getTotal(order)', 'template' => '{input}'], []) ?>
                                <div class="text-right">
                                    <span>{{order.restaurant_refund}}</span>
                                </div>
                                
                            </td>
                            <td>
                                <?= AHtml::input('', ['maxlength' => '255', 'ng-change' => 'order.isChanged = true', 'ng-model' => 'order.corporate_client_refund', 'ng-pattern'=>'/^[0-9]{1,7}(\.[0-9]+)?$/', 'template' => '{input}'], []) ?>
                            </td>

                            <td>
                                <?= AHtml::input('', ['maxlength' => '255', 'ng-change' => 'order.isChanged = true', 'ng-model' => 'order.corporate_restaurant_refund', 'ng-pattern'=>'/^[0-9]{1,7}(\.[0-9]+)?$/', 'template' => '{input}'], []) ?>
                            </td>
                            <td>
                                <?= AHtml::input('', ['maxlength' => '255', 'ng-change' => 'order.isChanged = true', 'ng-model' => 'order.restaurant_credit', 'ng-pattern'=>'/^[0-9]{1,7}(\.[0-9]+)?$/', 'template' => '{input}'], []) ?>
                            </td>
                            <td>
                                <ul ng-show="order.billing_address_data">
                                    <li><strong>{{order.billing_address_data.first_name}} {{order.billing_address_data.last_name}}</strong></li>
                                    <li>{{order.billing_address_data.building_number}} {{order.billing_address_data.address1}}, {{order.billing_address_data.address2}}, {{order.billing_address_data.postcode}}, {{order.billing_address_data.city}}</li>
                                    <li>{{order.billing_address_data.phone}}</li>
                                    <li>{{order.billing_address_data.email}}</li>
                                </ul>
                            </td>
                        </tr>
                        </tbody>
                        <tbody ng-show="(orders | filter:filterOrders).length == 0">
                            <tr>
                                <td  colspan="20"><?= T::l('No records') ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?= $this->action('order/ready-by-form') ?>
</div>



