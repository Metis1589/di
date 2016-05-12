<?php

use admin\common\AHtml;
use common\components\language\T;
use \common\enums\RecordType;

?>

<div class="restaurant-delivery-form" ng-controller="deliveryController">

    <form editable-form name="tableform" onaftersave="saveTable()" oncancel="cancel()">
        <script>
            var restaurantDeliveryModel = '<?= $model ?>';
        </script>

        <?= AHtml::input('Is Overridden?',
            ['type'=>'checkbox', 'id'=>'is_parent_delivery', 'ng-model'=>'deliveryIsActive', 'ng-click' => 'activateDeliveryService()']
        ) ?>

        <span ng-if="deliveryIsActive">

            <hr/>

            <?= AHtml::waitSpinner(['ng-show' => 'deliveryFormIsSubmitting']) ?>

            <?= AHtml::input('Driving Instructions',
                ['type'=>'text', 'maxlength'=>'500', 'id'=>'driving_instructions', 'ng-model'=>'delivery.driving_instructions']
            ) ?>

            <?= AHtml::input('Driver Instructions',
                ['type'=>'text', 'maxlength'=>'500', 'id'=>'driver_instructions', 'ng-model'=>'delivery.driver_instructions']
            ) ?>

            <?= AHtml::input('Max Range',
                ['type'=>'number', 'step'=>'any', 'maxlength'=>'10', 'id'=>'range', 'min'=>'0', 'max' => '100000', 'ng-model'=>'delivery.range', 'required' => ''],
                ['form-name' => 'tableform', 'rules' => ['required' => '... is missing', 'number' => '... is invalid', 'min' => '... is low', 'max' => '... is high']]
            ) ?>

            <h3><?= T::l('Services') ?></h3>

            <?= AHtml::input('Has Collection Delivery',
                ['type'=>'checkbox', 'id'=>'has_collection', 'ng-model'=>'delivery.has_collection']
            ) ?>

            <?= AHtml::input('Has DineIn Delivery',
                ['type'=>'checkbox', 'id'=>'has_dinein', 'ng-model'=>'delivery.has_dinein', 'ng-disabled'=>'delivery.has_own']
            ) ?>

            <?= AHtml::input('Has Own Delivery',
                ['type'=>'checkbox', 'id'=>'has_own', 'ng-model'=>'delivery.has_own', 'ng-disabled'=>'delivery.has_dinein']
            ) ?>

            <?= AHtml::input('Collect Time In Min',
                ['type'=>'number', 'step'=>'any', 'maxlength'=>'10', 'id'=>'collect_time_in_min', 'min'=>'0', 'max' => '100000', 'ng-model'=>'delivery.collect_time_in_min', 'required' => ''],
                ['form-name' => 'tableform', 'rules' => ['required' => '... is missing', 'number' => '... is invalid', 'min' => '... is low', 'max' => '... is high']],
                ['ng-if'=>'delivery.has_collection']
            ) ?>

            <?= AHtml::input('Rate Type',
                ['type'=>'radiolist', 'items'=>\common\enums\RestaurantDeliveryRateType::getLabels(), 'id'=>'rate_type', 'options' => ['itemOptions' => ['ng-model' => 'delivery.rate_type', 'required' => '']]],
                ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']],
                ['ng-if'=>'delivery.has_own || delivery.has_dinein']
            ) ?>

            <?= AHtml::input('Fixed Charge',
                ['type'=>'number', 'step'=>'any', 'maxlength'=>'10', 'id'=>'fixed_charge', 'min'=>'0', 'max' => '100000', 'ng-model'=>'delivery.fixed_charge', 'required' => ''],
                ['form-name' => 'tableform', 'rules' => ['required' => '... is missing', 'number' => '... is invalid', 'min' => '... is low', 'max' => '... is high']],
                ['ng-if'=>'delivery.rate_type == \''.\common\enums\RestaurantDeliveryRateType::Fixed.'\'']
            ) ?>

            <div class="float-subform" ng-if="(delivery.has_own || delivery.has_dinein) && delivery.rate_type == '<?=\common\enums\RestaurantDeliveryRateType::Float ?>'">
                <div ng-repeat="c in delivery.restaurantDeliveryCharges | filter:chargeFilterOptions" class="row">

                    <?= AHtml::input('Distance',
                        ['type'=>'number', 'step'=>'any', 'maxlength'=>'10', 'id'=>'distance_in_miles_{{c.id}}', 'min'=>'0', 'max' => '100', 'ng-model'=>'c.distance_in_miles', 'required' => '',
                            'template' => '<div class="col-xs-3">{label}</div><div class="col-xs-9">{input}</div>{errors}'],
                        [],
                        ['class'=>'form-group col-xs-3']
                    ) ?>

                    <?= AHtml::input('Charge',
                        ['type'=>'number', 'step'=>'any', 'maxlength'=>'10', 'id'=>'charge_{{c.id}}', 'min'=>'0', 'max' => '100', 'ng-model'=>'c.charge', 'required' => '',
                            'template' => '<div class="col-xs-3">{label}</div><div class="col-xs-9">{input}</div>{errors}'],
                        [],
                        ['class'=>'form-group col-xs-3']
                    ) ?>

                    <div class="form-group col-xs-5" ng-repeat="(key, value) in c.custom_fields">
                            <div class="col-xs-3">{{key}}</div>
                            <div class="col-xs-9"><input type="text" class="form-control" ng-model="c.custom_fields[key]"></div>
                    </div>

                    <div class="col-xs-1">
                        <a href="" ng-click="deleteCharge(c.id)" ng-show="showDeleteRow()"><span class="fa fa-times"></span> </a>
                    </div>

                </div>
                <button type="button" ng-click="addCharge()" class="btn btn-success col-xs-offset-10"><?= T::l('Add') ?></button>
            </div>

        </span>

        <?= AHtml::errorNotification('{{submitError}}', ['ng-show' => 'hasSubmitError()']) ?>

        <?= AHtml::saveButton(['ng-click' => 'saveDelivery()','ng-disabled' => 'tableform.$invalid']) ?>

    </form>


</div>
