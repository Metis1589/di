<?php
use common\components\language\T;
use common\enums\RecordType;
?>

<div class="user-list" ng-controller="codesController">
    <div class="row">
        <div class="col-xs-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><?= T::l('Codes') ?></h5>
                    <div class="ibox-tools">
                        <a class="" ng-click="add()">
                            <i class="fa fa-plus"></i>
                        </a>
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <table class="table table-bordered" >
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?= T::l('Code') ?></th>
                                <th><?= T::l('Code name') ?></th>
                                <th><?= T::l('Daily limit') ?></th>
                                <th><?= T::l('Weekly limit') ?></th>
                                <th><?= T::l('Monthly limit') ?></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody ng-show="(codes | filter:filterCodes).length > 0">
                            <tr ng-repeat="c in codes | filter:filterCodes" ng-class="c.record_type == '<?= RecordType::InActive ?>' ? 'danger' : '' ">
                                <td>{{c.id}}</td>
                                <td>{{c.value}}</td>
                                <td>{{c.name}}</td>
                                <td>{{c.daily_limit}}</td>
                                <td>{{c.weekly_limit}}</td>
                                <td>{{c.monthly_limit}}</td>
                                <td>
                                    <a title="<?= T::l('Update') ?>"     href="" ng-click="edit(c.id)"><span class="fa fa-pencil"></span></a>
                                    <a title="<?= T::l('Deactivate') ?>" href="" ng-click="setStatus(c.id, '<?= RecordType::InActive ?>')" ng-if="c.record_type == '<?= RecordType::Active ?>'"><span class="fa fa-ban"></span></a>
                                    <a title="<?= T::l('Activate') ?>"   href="" ng-click="setStatus(c.id, '<?= RecordType::Active ?>')"   ng-if="c.record_type == '<?= RecordType::InActive ?>'"><span class="fa fa-check-circle-o"></span></a>
                                    <a title="<?= T::l('Delete') ?>"     href="" ng-click="setStatus(c.id, '<?= RecordType::Deleted ?>')"><span class="fa fa-trash"></span></a>
                                </td>
                            </tr>
                        </tbody>
                        <tbody ng-show="(codes | filter:filterCodes).length == 0">
                            <tr>
                                <td  colspan="7"><?= T::l('No records') ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?= $this->action('company-user-group-code/popup-form') ?>
</div>