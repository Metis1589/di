<?php

use admin\common\AHtml;

?>
<div class="text-center">
    <a data-toggle="modal" class="hidden" href="#edit-code-popup" id="code-popup-open"></a>
</div>
<div id="edit-code-popup" class="modal fade" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <form name="tableform">

                            <?= AHtml::waitSpinner(['ng-show' => 'codeFormIsSubmitting']) ?>

                            <?= AHtml::input('Unique code',
                                ['type' => 'text', 'maxlength' => '50', 'id' => 'value', 'ng-model' => 'editedCode.value', 'required' => ''],
                                ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                            ) ?>

                            <?= AHtml::input('Code name',
                                ['type' => 'text', 'maxlength' => '50', 'id' => 'name', 'ng-model' => 'editedCode.name', 'required' => ''],
                                ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                            ) ?>

                            <?= AHtml::input('Daily limit value',
                                ['type' => 'text', 'maxlength' => '20', 'id' => 'daily_limit', 'ng-model' => 'editedCode.daily_limit'],
                                ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                            ) ?>

                            <?= AHtml::input('Weekly limit value',
                                ['type' => 'text', 'maxlength' => '20', 'id' => 'weekly_limit', 'ng-model' => 'editedCode.weekly_limit'],
                                ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                            ) ?>

                            <?= AHtml::input('Monthy limit value',
                                ['type' => 'text', 'maxlength' => '20', 'id' => 'monthly_limit', 'ng-model' => 'editedCode.monthly_limit'],
                                ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                            ) ?>

                            <?= AHtml::input('Limit type',
                                ['type' => 'select', 'items'=> [ common\enums\CompanyLimitType::Soft => common\enums\CompanyLimitType::Soft, common\enums\CompanyLimitType::Hard => common\enums\CompanyLimitType::Hard ], 'id' => 'limit_type', 'ng-model' => 'editedCode.limit_type', 'required' => ''],
                                ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                            ) ?>

                            <?= AHtml::input('Record type',
                                ['type' => 'select', 'items'=> [ common\enums\RecordType::Active => common\enums\RecordType::Active, common\enums\RecordType::InActive => common\enums\RecordType::InActive ], 'id' => 'record_type', 'ng-model' => 'editedCode.record_type', 'required' => ''],
                                ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                            ) ?>

                            <?= AHtml::errorNotification('{{codeSubmitError}}', ['ng-show' => 'hasCodeSubmitError()']) ?>

                            <?= AHtml::saveButton(['ng-click' => 'save()', 'ng-disabled' => 'tableform.$invalid']) ?>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
