<?php

use admin\common\AHtml;
use common\components\language\T;

?>
<div class="text-center">
    <a data-toggle="modal" class="hidden" href="#edit-group-popup" id="group-popup-open"></a>
</div>
<div id="edit-group-popup" class="modal fade" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <form name="tableform">

                            <?= AHtml::waitSpinner(['ng-show' => 'groupFormIsSubmitting']) ?>

                            <div ng-if="editedGroup.name == '<?= common\enums\DefaultCompanyGroup::DefaultExternal ?>' || editedGroup.name == '<?= common\enums\DefaultCompanyGroup::DefaultInternal ?>'">
                                <div class="form-group">
                                    <label class="control-label"><?= T::l('Name') ?></label>
                                    <input type="text" readonly="readonly" disabled="disabled" class="form-control" value="{{editedGroup.name}}">
                                </div>
                            </div>
                            <div ng-if="editedGroup.name != '<?= common\enums\DefaultCompanyGroup::DefaultExternal ?>' && editedGroup.name != '<?= common\enums\DefaultCompanyGroup::DefaultInternal ?>'">
                            <?= AHtml::input('Name',
                                ['type' => 'text', 'maxlength' => '50', 'id' => 'name', 'ng-model' => 'editedGroup.name', 'required' => ''],
                                ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                            ) ?>
                            </div>

                            <?= AHtml::input('Max order per day / per user',
                                ['type' => 'text', 'maxlength' => '3', 'id' => 'max_order_per_day_per_user', 'ng-model' => 'editedGroup.max_order_per_day_per_user', 'required' => ''],
                                ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                            ) ?>

                            <div ng-repeat="(idx, code) in editedGroup.codes">
                                <label><input type="checkbox" ng-model="code.isChecked">&nbsp;{{code.name}}</label>
                            </div>

                            <?= AHtml::input('Type',
                                ['type' => 'select', 'items'=> [ common\enums\RecordType::Active => common\enums\RecordType::Active, common\enums\RecordType::InActive => common\enums\RecordType::InActive ], 'id' => 'record_type', 'ng-model' => 'editedGroup.record_type', 'required' => ''],
                                ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                            ) ?>

                            <?= AHtml::errorNotification('{{groupSubmitError}}', ['ng-show' => 'hasGroupSubmitError()']) ?>

                            <?= AHtml::saveButton(['ng-click' => 'save()', 'ng-disabled' => 'tableform.$invalid']) ?>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
