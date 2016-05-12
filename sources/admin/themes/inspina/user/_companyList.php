<?php
use common\components\language\T;
use common\enums\RecordType;
?>

<div class="user-list" ng-controller="<?= $controller ?>Controller" load-url="/user/get-company-users?role=<?= \common\enums\UserType::CorporateMember ?>&id=<?= $company_id ?>">
    <div class="row">
        <div class="col-xs-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><?= $controller == 'user' ? T::l('Users') : T::l('Administrators') ?></h5>
                    <div class="ibox-tools">
                        <a class="" ng-click="add('user-popup-open-<?= $model ?>')">
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
                                <th><?= T::l('Username') ?></th>
                                <th><?= T::l('First name') ?></th>
                                <th><?= T::l('Last name') ?></th>
                                <?php if ($controller == 'user'): ?>
                                    <th><?= T::l('Address 1') ?></th>
                                    <th><?= T::l('Address 2') ?></th>
                                    <th><?= T::l('City') ?></th>
                                    <th><?= T::l('Postcode') ?></th>
                                    <th><?= T::l('Phone') ?></th>
                                <?php endif; ?>
                                <th><?= T::l('User Type') ?></th>
                                <?php if ($controller == 'user'): ?>
                                    <th><?= T::l('Group') ?></th>
                                    <th><?= T::l('Is Corporate Approved') ?></th>
                                <?php endif; ?>
                                <th><?= T::l('Status') ?></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody ng-show="(users | filter:filterUsers).length > 0">
                            <tr ng-repeat="u in users | filter:filterUsers" ng-class="u.record_type == '<?=RecordType::InActive ?>' ? 'danger' : '' ">
                                <td>{{u.id}}</td>
                                <td>{{u.username}}</td>
                                <td>{{u.first_name}}</td>
                                <td>{{u.last_name}}</td>
                                <?php if ($controller == 'user'): ?>
                                    <td>{{u.primaryAddress.address1}}</td>
                                    <td>{{u.primaryAddress.address2}}</td>
                                    <td>{{u.primaryAddress.city}}</td>
                                    <td>{{u.primaryAddress.postcode}}</td>
                                    <td>{{u.primaryAddress.phone}}</td>
                                <?php endif; ?>
                                <td>{{u.user_type}}</td>
                                <?php if ($controller == 'user'): ?>
                                    <td>{{u.group_name}}</td>
                                    <td>{{ u.is_corporate_approved ? '<?= T::l('Approved') ?>' : '<?= T::l('Not approved') ?>' }}</td>
                                <?php endif; ?>
                                <td>{{u.record_type}}</td>
                                <td>
                                    <a title="<?=T::l('Update') ?>"     href="" ng-click="edit(u.id, 'user-popup-open-<?= $model ?>')"><span class="fa fa-pencil"></span></a>
                                    <a title="<?=T::l('Deactivate') ?>" href="" ng-click="setStatus(u.id, '<?= RecordType::InActive ?>')" ng-if="u.record_type === '<?=RecordType::Active ?>'"><span class="fa fa-ban"></span></a>
                                    <a title="<?=T::l('Activate') ?>"   href="" ng-click="setStatus(u.id, '<?= RecordType::Active ?>')"  ng-if="u.record_type === '<?=RecordType::InActive ?>'"><span class="fa fa-check-circle-o"></span></a>
                                    <a title="<?=T::l('Delete') ?>"     href="" ng-click="setStatus(u.id, '<?= RecordType::Deleted ?>')"><span class="fa fa-trash"></span></a>
                                </td>
                            </tr>
                        </tbody>
                        <tbody ng-show="(users | filter:filterUsers).length == 0">
                            <tr>
                                <?php if ($controller == 'user'): ?>
                                <td colspan="14"><?= T::l('No records') ?></td>
                                <?php else: ?>
                                <td colspan="8"><?= T::l('No records') ?></td>
                                <?php endif; ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?= $this->action('user/popup-form', [
        'model'      => $model,
        'company_id' => $company_id,
        'controller' => $controller
    ]) ?>
</div>
