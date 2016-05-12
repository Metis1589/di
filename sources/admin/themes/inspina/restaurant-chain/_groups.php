

<?php

use admin\assets\AngularAsset;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantChain */
/* @var $form yii\widgets\ActiveForm */

?>

<style>
    input.ng-invalid {
        border: 1px solid red;
    }
</style>

<div ng-controller="restaurantGroupController" class="restaurant-group-form">
<!--    {{options}}-->
    
    <form editable-form name="tableform" onaftersave="saveTable()" oncancel="cancel()" shown="true">
        
        <input type="hidden" id="restaurant_chain_id" value="<?= Yii::$app->request->get('id') ?>"/>
        <!-- table -->
        <table class="menu-options table table-bordered table-hover table-condensed">
            <tr style="font-weight: bold">
                <td class="col-xs-8"><?= Yii::t('label', 'Name') ?></td>
                <td class="col-xs-2"><?= Yii::t('label', 'Actions') ?></td>
            </tr>
            <tr ng-repeat="option in options | filter:filterOptions" ng-class="option.record_type == 'Inactive' ? 'active' : ''">
                <td style="padding-left:{{option.level*20}}px" class="">
<!--                   {{$index}} ----
                    {{getLastIndex(option.level, $index)}}---
                    {{option.id}} ---
                    {{option.parent_id}}-->
<!--                    {{getLastElementInLevel(option.parent_id, option.level).name_key}}-->
<!--                    {{option.sort_order}}--<br>-->

<!--                    {{option.menu_option_category_type_id}}-*-<br>-->

                    <span editable-text="option.name_key" e-form="tableform" e-required="">
                        {{ option.name_key}}
                    </span>
                </td>
                <td class="row">
                    <span class="col-xs-4 text-nowrap">
                        <a href="" class="fa fa-list-ol" ng-show="tableform.$visible" ng-click="addCategory(option)"></a>
                    </span>

                    <span class="col-xs-4 text-nowrap">
                        <a href="" class="fa fa fa-pencil" ng-show="tableform.$visible" ng-click="edit(option.id)"></a>
                        <a href="" class="fa fa-check-circle-o" ng-show="tableform.$visible" ng-click="activate(option)" ng-if="option.record_type == 'Inactive'"></a>
                        <a href="" class="fa fa-ban" ng-show="tableform.$visible" ng-click="deactivate(option)" ng-if="option.record_type == 'Active'"></a>
                        <a href="" class="fa fa-trash" ng-show="tableform.$visible" ng-click="deleteOption(option)"></a>
                    </span>
                </td>
            </tr>
        </table>

        <!-- buttons -->
        <div class="btn-edit">
            <span class="pull-right">
                <button type="button" class="btn btn-default" ng-show="!tableform.$visible" ng-click="tableform.$show()">
                    edit
                </button>
            </span>
        </div>
        <div class="btn-form" ng-show="tableform.$visible">
            <span class="">
                <button type="button" ng-disabled="tableform.$waiting" ng-click="addCategory(null)" class="btn btn-success"><?= Yii::t('label', 'Add Parent Group') ?></button>
            </span>

            <span class="pull-right">
                <span class="save_error" ng-show="save_error"><?= Yii::t('label', 'Error saving menu options') ?></span>
                <span class="spinner fa fa-spinner fa-pulse" ng-show="saving"></span>
                <button type="submit" ng-disabled="tableform.$waiting || tableform.$invalid" class="btn btn-primary"><?= Yii::t('label', 'Save') ?></button>
                <button type="button" ng-disabled="tableform.$waiting" ng-click="tableform.$cancel()" class="btn btn-default"><?= Yii::t('label', 'Cancel') ?></button>
            </span>
        </div>
    </form>
</div>
