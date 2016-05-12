<?php
use common\components\language\T;
use common\enums\RecordType;
?>

<div class="user-list" ng-controller="userController" load-url="/user/get-users?model=<?= $model ?>&id=<?= $id ?>">
    <div class="row">
        <div class="col-xs-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><?= T::l('Users') ?></h5>
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
                                <th><?= T::l('Username') . $model?></th>
                                <th><?= T::l('User Type') ?></th>
                                <th><?= T::l('Status') ?></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody ng-show="(users | filter:filterUsers).length > 0">
                            <tr ng-repeat="u in users | filter:filterUsers" ng-class="u.record_type == '<?=RecordType::InActive ?>' ? 'danger' : '' ">
                                <td>{{u.id}}</td>
                                <td>{{u.username}}</td>
                                <td>{{u.user_type}}</td>
                                <td>{{u.record_type}}</td>
                                <td>
                                    <a title="<?=T::l('Update') ?>" href="" ng-click="edit(u.id, 'user-popup-open-<?= $model ?>')"><span class="fa fa-pencil"></span></a>
                                    <a title="<?=T::l('Deactivate') ?>" href="" ng-click="setStatus(u.id,'<?=RecordType::InActive ?>')" ng-if="u.record_type == '<?=RecordType::Active ?>'"><span class="fa fa-ban"></span></a>
                                    <a title="<?=T::l('Activate') ?>" href="" ng-click="setStatus(u.id, '<?=RecordType::Active ?>')" ng-if="u.record_type == '<?=RecordType::InActive ?>'"><span class="fa fa-check-circle-o"></span></a>
                                    <a title="<?=T::l('Delete') ?>" href="" ng-click="setStatus(u.id, '<?=RecordType::Deleted ?>')"><span class="fa fa-trash"></span></a>
                                </td>
                            </tr>
                        </tbody>
                        <tbody ng-show="(users | filter:filterUsers).length == 0">
                            <tr>
                                <td  colspan="6"><?= T::l('No records') ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?=  $this->action('user/popup-form', ['model' => $model]) ?>
</div>
