<?php

use admin\common\AHtml;
use common\enums\UserType;
use common\components\language\T;

?>
<div class="text-center">
    <a data-toggle="modal" class="hidden" href="#edit-readyby-popup" id="readyby-popup-open"></a>
</div>
<div id="edit-readyby-popup" class="modal fade" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <form name="tableform2">
                            <?= AHtml::input('Time',
                                ['maxlength'=>'255', 'id'=>'ready_by', 'timepicker' => true, 'ng-model'=>'editedOrder.ready_by', 'ng-required' => 'editedOrder.ready_by_time == ""','class' => 'form-control datetime-jui-picker', 'template' => '<span ng-if="editedOrder.current_status == \'ReadyBy\' || editedOrder.current_status == \'OrderConfirmed\'">{label}{input}{errors}</span>'],
                                ['form-name' => 'tableform2', 'rules' => ['required' => '... is missing', 'pattern' => '... is invalid']]
                            ) ?>

                            <?= AHtml::input('Minutes',
                                ['type'=>'number', 'step'=>'any', 'maxlength'=>'10', 'id'=>'ready_by_time', 'min'=>'0', 'max' => '100', 'ng-model'=>'editedOrder.ready_by_time', 'ng-required' => 'editedOrder.ready_by == ""', 'template' => '<span ng-if="editedOrder.current_status == \'ReadyBy\' || editedOrder.current_status == \'OrderConfirmed\'">{label}{input}{errors}</span>'],
                                ['form-name' => 'tableform2', 'rules' => ['required' => '... is missing', 'number' => '... is invalid', 'min' => '... is low', 'max' => '... is high']]
                            ) ?>
                            
                            <?= AHtml::input('Cancellation Reason',
                                ['maxlength'=>'255', 'id'=>'cancellation_reason', 'ng-model'=>'editedOrder.cancellation_reason', 'required'=>'','class' => 'form-control','template' => '<span ng-if="editedOrder.current_status == \'OrderCancelled\'">{label}{input}{errors}</span>'],
                                ['form-name' => 'tableform2', 'rules' => ['required' => '... is missing', 'pattern' => '... is invalid'], ]
                            ) ?>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <?= AHtml::saveButton(['ng-click' => 'savePopup(editedOrder.id)', 'ng-disabled' => 'tableform2.$invalid']) ?>
                    </div>
                    <div class="col-xs-6">
                        <?= AHtml::saveButton(['ng-click' => 'cancel(editedOrder.id)'], 'Cancel') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
