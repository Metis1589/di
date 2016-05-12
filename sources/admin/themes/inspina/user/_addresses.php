<?php
use common\components\language\T;
use common\enums\RecordType;
?>

<div class="user-list"  ng-controller="userAddressesController">
    <div class="row">
        <div class="col-xs-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><?= T::l('Addresses') ?></h5>
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
                                <th><?= T::l('Name') ?></th>
                                <th><?= T::l('Title') ?></th>
                                <th><?= T::l('First Name') ?></th>
                                <th><?= T::l('Last Name') ?></th>
                                <th><?= T::l('Address1') ?></th>
                                <th><?= T::l('Country') ?></th>
                                <th><?= T::l('City') ?></th>
                                <th><?= T::l('Postcode') ?></th>
                                <th><?= T::l('Phone') ?></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody ng-show="(users | filter:filterUsers).length > 0">
                            <tr ng-repeat="u in users | filter:filterUsers" ng-class="u.record_type == '<?=RecordType::InActive ?>' ? 'danger' : '' ">
                                <td>{{u.address_id}}</td>
                                <td>{{u.address.name}}</td>
                                <td>{{u.address.title}}</td>
                                <td>{{u.address.first_name}}</td>
                                <td>{{u.address.last_name}}</td>
                                <td>{{u.address.address1}}</td>
                                <td>{{u.address.country.native_name}}</td>
                                <td>{{u.address.city}}</td>
                                <td>{{u.address.postcode}}</td>
                                <td>{{u.address.phone}}</td>
                                <td>
                                    <a title="<?=T::l('Update') ?>" href="" ng-click="edit(u.id)"><span class="fa fa-pencil"></span></a>
                                    <a title="<?=T::l('Deactivate') ?>" href="" ng-click="setStatus(u.id,'<?=RecordType::InActive ?>')" ng-if="u.record_type == '<?=RecordType::Active ?>'"><span class="fa fa-ban"></span></a>
                                    <a title="<?=T::l('Activate') ?>" href="" ng-click="setStatus(u.id, '<?=RecordType::Active ?>')" ng-if="u.record_type == '<?=RecordType::InActive ?>'"><span class="fa fa-check-circle-o"></span></a>
                                    <a title="<?=T::l('Delete') ?>" href="" ng-click="setStatus(u.id, '<?=RecordType::Deleted ?>')"><span class="fa fa-trash"></span></a>
                                </td>
                            </tr>
                        </tbody>
                        <tbody ng-show="(users | filter:filterUsers).length == 0">
                            <tr>
                                <td  colspan="10"><?= T::l('No records') ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?=  $this->action('user/popup-address-form', ['model' => $model]) ?>
</div>
