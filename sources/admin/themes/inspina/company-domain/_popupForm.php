<?php

use admin\common\AHtml;

?>
<div class="text-center">
    <a data-toggle="modal" class="hidden" href="#edit-domain-popup" id="domain-popup-open"></a>
</div>
<div id="edit-domain-popup" class="modal fade" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <form name="tableform">

                            <?= AHtml::waitSpinner(['ng-show' => 'domainFormIsSubmitting']) ?>

                            <?= AHtml::input('Domain',
                                ['type' => 'text', 'maxlength' => '50', 'id' => 'domain', 'ng-model' => 'editedDomain.domain', 'required' => ''],
                                ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                            ) ?>

                            <?= AHtml::input('Type',
                                    ['type' => 'select', 'items'=> [ common\enums\RecordType::Active => common\enums\RecordType::Active, common\enums\RecordType::InActive => common\enums\RecordType::InActive ], 'id' => 'record_type', 'ng-model' => 'editedDomain.record_type', 'required' => ''],
                                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                            ) ?>

                            <?= AHtml::errorNotification('{{domainSubmitError}}', ['ng-show' => 'hasDomainSubmitError()']) ?>

                            <?= AHtml::saveButton(['ng-click' => 'save()', 'ng-disabled' => 'tableform.$invalid']) ?>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
