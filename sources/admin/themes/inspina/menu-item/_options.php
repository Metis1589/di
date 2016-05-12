<?php
/* @var $this yii\web\View */
/* @var $model common\models\MenuItem */
/* @var $form yii\widgets\ActiveForm */

?>

<style>
    input.ng-invalid {
        border: 1px solid red;
    }

    table.menu-options input[type=text], table.menu-options select {
        width: 100%;
    }
</style>


<div ng-controller="menuOptionsController" class="menu-option-form">
<!--    {{options}}-->
    
    <form editable-form name="tableform" onaftersave="saveTable()" oncancel="cancel()" shown="true">

        <input type="hidden" id="menu_item_id" value="<?= Yii::$app->request->get('id') ?>"/>
        <!-- table -->
        <table class="menu-options table table-bordered table-hover table-condensed">
            <tr style="font-weight: bold">
                <td class="col-xs-2"><?= Yii::t('label', 'Name') ?></td>
                <td class="col-xs-2"><?= Yii::t('label', 'Description') ?></td>
                <td class="col-xs-2"><?= Yii::t('label', 'Category Type') ?></td>
                <td class="col-xs-1"><?= Yii::t('label', 'Max Items') ?></td>
                <td class="col-xs-1"><?= Yii::t('label', 'Web Price') ?></td>
                <td class="col-xs-1"><?= Yii::t('label', 'Restaurant Price') ?></td>
                <td class="col-xs-1"><?= Yii::t('label', 'Is Default') ?> </td>
                <td class="col-xs-2"><?= Yii::t('label', 'Actions') ?></td>
            </tr>
            <tr ng-repeat="option in options | filter:filterOptions" ng-class="option.record_type == 'Inactive' ? 'active' : (option.menu_option_category_type_id ? 'warning' : '')">
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
                <td>
                    <span editable-text="option.description_key" e-form="tableform">
                        {{ option.description_key}}
                    </span>
                </td>
                <td>
                    <span editable-select="option.menu_option_category_type_id" e-form="tableform" ng-if="option.menu_option_category_type_id !== null" e-ng-options="categoryType.id as categoryType.name for categoryType in categoryTypes">
                        {{ showCategoryType(option)}}
                    </span>
                </td>
                <td>
                    <span ng-if="option.menu_option_category_type_id !== null" editable-text="option.max_category_items" e-form="tableform" e-numeric-only="" e-maxlength="5">
                        {{ option.max_category_items}}
                    </span>
                </td>
                <td>
                    <span editable-text="option.web_price" e-form="tableform" e-numeric-two-decimals="" e-maxlength="10">
                        {{ option.web_price}}
                    </span>
                </td>
                <td>
                    <span editable-text="option.restaurant_price" e-form="tableform" e-numeric-two-decimals="" e-maxlength="10">
                        {{ option.restaurant_price}}
                    </span>
                </td>
                <td>
                    <span ng-if="option.menu_option_category_type_id == null" editable-checkbox="option.is_default" e-form="tableform">
                        {{ option.is_default}}
                    </span>
                </td>
                <td class="row">
                    <span class="col-xs-4 text-nowrap">
                        <a href="" class="fa fa-list-ol" ng-show="tableform.$visible" ng-click="addCategory(option)" ng-if="option.menu_option_category_type_id !== null"></a>
                        <a href="" class="fa fa-sitemap" ng-show="tableform.$visible" ng-click="addOption(option)" ng-if="option.menu_option_category_type_id !== null"></a>
                    </span>

                    <span class="col-xs-4 text-nowrap">
                        <a href="" class="fa fa-arrow-up" ng-show="tableform.$visible" ng-click="up(option)" ng-if="option.sort_order != 1"></a>
                        <a href="" class="fa fa-arrow-down" ng-show="tableform.$visible" ng-click="down(option)" ng-if="getLastElementInLevel(option.parent_id, option.level).id != option.id"></a>
                    </span>

                    <span class="col-xs-4 text-nowrap">
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
                <button type="button" ng-disabled="tableform.$waiting" ng-click="addCategory(null)" class="btn btn-success"><?= Yii::t('label', 'Add Category') ?></button>
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


