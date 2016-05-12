<?php

use admin\common\AHtml;
use \common\components\language\T;

?>
<div class="text-center">
    <a data-toggle="modal" class="hidden" href="#edit-extype-popup" id="extype-popup-open"></a>
</div>
<div id="edit-extype-popup" class="modal fade" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <form name="tableform">

                            <?= AHtml::waitSpinner(['ng-show' => 'extypeFormIsSubmitting']) ?>

                            <?= AHtml::input('Expense type',
                                ['type' => 'text', 'maxlength' => '50', 'id' => 'name', 'ng-model' => 'editedExtype.name', 'required' => ''],
                                ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                            ) ?>

                            <?= AHtml::input('Limit per order',
                                ['type' => 'number', 'step'=>'any', 'id' => 'limit_per_order', 'ng-model' => 'editedExtype.limit_per_order', 'required' => '', 'ng-min' => 'editedExtype.limit_type == \'' . common\enums\CompanyLimitType::Soft . '\' ? editedExtype.soft_limit_max : 0'],
                                ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                            ) ?>

                            <div class="form-group">
                                <label class="control-label" for="extype_group"><?= T::l('Expense type group') ?></label>
                                <select ng-model="editedExtype.company_user_group_id" class="form-control">
                                    <option ng-repeat="group in groups" ng-selected="editedExtype.company_user_group_id == group.id" value="{{group.id}}">{{group.name}}</option>
                                </select>
                            </div>

                            <?= AHtml::input('Limit type',
                                ['type'=>'select', 'items'=> [ common\enums\CompanyLimitType::Soft => common\enums\CompanyLimitType::Soft, common\enums\CompanyLimitType::Hard => common\enums\CompanyLimitType::Hard ], 'id' => 'expt_limit_type', 'ng-model' => 'editedExtype.limit_type', 'required' => ''],
                                ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                            ) ?>

                            <div ng-show="editedExtype.limit_type === '<?= common\enums\CompanyLimitType::Soft ?>'">
                            <?= AHtml::input('Soft limit max',
                                ['type' => 'number', 'step'=>'any', 'id' => 'soft_limit_max', 'ng-model' => 'editedExtype.soft_limit_max']
                            ) ?>
                            </div>

                            <div ng-repeat="s in schedules" class="row schedule">
                                <div class="col-xs-2">
                                    <h3>{{s.day_label}}</h3>
                                </div>

                                <?=  AHtml::input('{{s.from_label}}',
                                    ['type'=>'text', 'step'=>'any', 'timepicker' => true, 'maxlength'=>'10', 'id'=>'from_{{$index}}', 'class' => 'timepicker', 'ng-model'=>'s.from', 'data-show24hours' => 'true', 'time-both' => 's.to',
                                        'template' => '<div class="col-xs-4">{label}</div><div class="col-xs-8">{input}</div>'],
                                    [],
                                    ['class'=>'form-group col-xs-5']
                                ) ?>

                                <?=  AHtml::input('To',
                                    ['type'=>'text', 'step'=>'any', 'timepicker' => true, 'maxlength'=>'10', 'id'=>'to_{{$index}}', 'class' => 'timepicker', 'ng-model'=>'s.to', 'data-show24hours' => 'true', 'time-both' => 's.from',
                                        'template' => '<div class="col-xs-4">{label}</div><div class="col-xs-8">{input}</div>'],
                                    [],
                                    ['class'=>'form-group col-xs-5']
                                ) ?>

                            </div>

                            <?= AHtml::errorNotification('{{extypeSubmitError}}', ['ng-show' => 'hasExtypeSubmitError()']) ?>

                            <?= AHtml::saveButton(['ng-click' => 'save()', 'ng-disabled' => 'tableform.$invalid']) ?>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->registerJs("
    $('#edit-extype-popup').on('shown.bs.modal', function() {
        $('.timepicker').timepicker();
    });
"); ?>