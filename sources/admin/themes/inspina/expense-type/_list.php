<?php
use common\components\language\T;
use common\enums\RecordType;
?>

<div class="user-list" ng-controller="extypesController">
    <div class="row">
        <div class="col-xs-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><?= T::l('Expense types') ?></h5>
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
                                <th><?= T::l('Expence nameadd') ?></th>
                                <th><?= T::l('Group name') ?></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody ng-show="(extypes | filter:filterExtypes).length > 0">
                            <tr ng-repeat="xt in extypes | filter:filterExtypes" ng-class="xt.record_type == '<?= RecordType::InActive ?>' ? 'danger' : '' ">
                                <td>{{xt.id}}</td>
                                <td>{{xt.name}}</td>
                                <td>{{xt.company_group}}</td>
                                <td>
                                    <a title="<?= T::l('Update') ?>"     href="" ng-click="edit(xt.id)"><span class="fa fa-pencil"></span></a>
                                    <a title="<?= T::l('Deactivate') ?>" href="" ng-click="setStatus(xt.id, '<?= RecordType::InActive ?>')" ng-if="xt.record_type == '<?= RecordType::Active ?>'"><span class="fa fa-ban"></span></a>
                                    <a title="<?= T::l('Activate') ?>"   href="" ng-click="setStatus(xt.id, '<?= RecordType::Active ?>')"   ng-if="xt.record_type == '<?= RecordType::InActive ?>'"><span class="fa fa-check-circle-o"></span></a>
                                    <a title="<?= T::l('Delete') ?>"     href="" ng-click="setStatus(xt.id, '<?= RecordType::Deleted ?>')"><span class="fa fa-trash"></span></a>
                                </td>
                            </tr>
                        </tbody>
                        <tbody ng-show="(extypes | filter:filterExtypes).length == 0">
                            <tr>
                                <td  colspan="7"><?= T::l('No records') ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?= $this->action('expense-type/popup-form') ?>
</div>