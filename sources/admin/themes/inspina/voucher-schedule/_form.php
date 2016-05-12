<?php
use admin\common\AHtml;
use common\enums\RecordType;

?>

<div class="voucher-schedule-form" ng-controller="voucherScheduleController">
    <script type="text/javascript">
        var voucherId = '<?= $id ?>';
    </script>

    <?= AHtml::input('Is Active',
        ['type'=>'checkbox', 'id'=>'is_parent_schedule', 'ng-model'=>'scheduleIsActive', 'ng-click' => 'disableSchedule("'.RecordType::Active.'","'.RecordType::InActive.'")']
    ) ?>

    <form name="tableform">
        <div ng-repeat="s in schedules" class="row schedule" ng-if="scheduleIsActive">
            <div class="col-xs-2">
                <h3>{{s.day_label}}</h3>
            </div>

            <?=  AHtml::input('{{s.from_label}}',
                ['type'=>'text', 'step'=>'any', 'maxlength'=>'10', 'id'=>'from_{{$index}}', 'min'=>'0', 'max' => '100', 'ng-model'=>'s.from', 'timepicker' =>'', 'class'=>'time-picker-tab', 'data-show24hours'=> 'true',
                    'template' => '<div class="col-xs-4">{label}</div><div class="col-xs-8">{input}</div>'],
                ['form-name' => 'tableform'],
                ['class'=>'form-group col-xs-5']
            ) ?>

            <?=  AHtml::input('To',
                ['type'=>'text', 'step'=>'any', 'maxlength'=>'10', 'id'=>'to_{{$index}}', 'min'=>'0', 'max' => '100', 'ng-model'=>'s.to', 'timepicker' =>'', 'class'=>'time-picker-tab', 'data-show24hours'=> 'true', 'time-both' => 's.from',
                    'template' => '<div class="col-xs-4">{label}</div><div class="col-xs-8">{input}</div>'],
                ['form-name' => 'tableform'],
                ['class'=>'form-group col-xs-5']
            ) ?>

        </div>

        <?= AHtml::errorNotification('{{scheduleSubmitError}}', ['ng-show' => 'hasSubmitScheduleError()']) ?>

        <?= AHtml::saveButton(['ng-click' => 'saveSchedule()', 'ng-disabled' => 'tableform.$invalid']) ?>

        <?= AHtml::waitSpinner(['ng-show' => 'scheduleFormIsSubmitting']) ?>
    </form>
</div>

