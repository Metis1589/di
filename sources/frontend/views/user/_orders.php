<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 4/5/2015
 * Time: 2:05 PM
 */
use frontend\components\language\T;
?>
<div ng-show="tab == 'pastOrders'">
    <h3 class="only_mobile"><?=T::l('MY ORDERS')?></h3>
    <div class="form_devider"></div>
    <div class="past_orders_block form_devider" ng-repeat="order in orders">
        <h5>{{order.restaurant_name}} {{order.items.length}}</h5>
        <div class="delivery_items">
            <table>
                <tr ng-repeat="item in order.items">
                    <td>{{item.quantity}}</td>
                    <td>x</td>
                    <td>{{item.menuItem.name_key}}</td>
                    <td>{{order.currency_symbol}} {{item.web_price * item.quantity | number:2}}</td>
                </tr>
            </table>
            <input type="submit" value="<?= T::l('Reorder') ?>" ng-click="reorder(order)">
        </div>
    </div>
</div>