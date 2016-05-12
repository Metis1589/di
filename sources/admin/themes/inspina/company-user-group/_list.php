<?php
use common\components\language\T;
use common\enums\RecordType;
?>

<div class="user-list" ng-controller="groupsController">
    <div class="row">
        <div class="col-xs-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><?= T::l('Groups') ?></h5>
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
                                <th><?= T::l('Group') ?></th>
                                <th><?= T::l('Codes') ?></th>
                                <th><?= T::l('Max order per day / per user') ?></th>
                                <th><?= T::l('Users') ?></th>
                                <th><?= T::l('Status') ?></th>
                                <th><?= T::l('Created on') ?></th>
                                <th><?= T::l('Last update') ?></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody ng-show="(groups | filter:filterGroups).length > 0">
                            <tr ng-repeat="g in groups | filter:filterGroups" ng-class="g.record_type == '<?= RecordType::InActive ?>' ? 'danger' : '' ">
                                <td>{{g.id}}</td>
                                <td>{{g.name}}</td>
                                <td>
                                    <span ng-repeat="code in g.companyUserGroupCodeNames">
                                        {{code.name}}<br/>
                                    </span>
                                </td>
                                <td>{{g.max_order_per_day_per_user}}</td>
                                <td>
                                    <span ng-repeat="user in g.companyUserGroupUsers">
                                        <a ng-if="user.is_corporate_approved == 1 ? true : false" title="<?= T::l('Delete') ?>" href="" ng-click="resetUserGroup(g.id, user.id)"><span class="fa fa-trash"></span></a>&nbsp; {{user.username}}<br/>
                                    </span>
                                </td>
                                <td>{{g.record_type}}</td>
                                <td>{{g.create_on}}</td>
                                <td>{{g.last_update}}</td>
                                <td>
                                    <a title="<?= T::l('Update') ?>" href="" ng-click="edit(g.id)"><span class="fa fa-pencil"></span></a>
                                    <div ng-if="g.name !== '<?= \common\enums\DefaultCompanyGroup::DefaultExternal ?>' && g.name !== '<?= \common\enums\DefaultCompanyGroup::DefaultInternal ?>'">
                                        <a title="<?= T::l('Deactivate') ?>" href="" ng-click="setStatus(g.id, '<?= RecordType::InActive ?>')" ng-if="g.record_type == '<?= RecordType::Active ?>'"><span class="fa fa-ban"></span></a>
                                        <a title="<?= T::l('Activate') ?>"   href="" ng-click="setStatus(g.id, '<?= RecordType::Active ?>')"   ng-if="g.record_type == '<?= RecordType::InActive ?>'"><span class="fa fa-check-circle-o"></span></a>
                                        <a title="<?= T::l('Delete') ?>"     href="" ng-click="setStatus(g.id, '<?= RecordType::Deleted ?>')"><span class="fa fa-trash"></span></a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tbody ng-show="(groups | filter:filterGroups).length == 0">
                            <tr>
                                <td  colspan="6"><?= T::l('No records') ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?= $this->action('company-user-group/popup-form') ?>
</div>