<?php
use common\components\language\T;
use common\enums\RecordType;
use common\enums\RestaurantContactOrderType;
?>

<div class="restaurant-contact-order-list" ng-controller="orderContactsController">
    <?php foreach(RestaurantContactOrderType::values() as $type): ?>
        <div class="row">
            <div class="col-xs-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?= RestaurantContactOrderType::getLabels()[$type] ?></h5>
                        <div class="ibox-tools">
                            <a class="" ng-click="add('<?=$type ?>')">
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
                                    <th><?= T::l('Role') ?></th>
                                    <th><?= T::l($type == RestaurantContactOrderType::Email ? 'Email' : 'Phone') ?></th>
                                    <th><?= T::l('Charge') ?></th>
                                    <?php if ($type == RestaurantContactOrderType::Ivr): ?>
                                        <th><?= T::l('Delay') ?></th>
                                    <?php endif; ?>
                                    <th><?= T::l('Status') ?></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody ng-show="(contacts | filter:filterContacts('<?= $type ?>')).length > 0">
                                <tr ng-repeat="c in contacts | filter:filterContacts('<?= $type ?>')" ng-class="c.record_type == '<?=RecordType::InActive ?>' ? 'danger' : '' ">
                                    <td>{{c.id}}</td>
                                    <td>{{c.name}}</td>
                                    <td>{{c.role}}</td>
                                    <td><?= ($type == RestaurantContactOrderType::Email ? '{{c.email}}' : '{{c.number}}') ?></td>
                                    <td>{{c.charge}}</td>
                                    <?php if ($type == RestaurantContactOrderType::Ivr): ?>
                                        <td>{{c.delay_in_min}}</td>
                                    <?php endif; ?>
                                    <td>{{c.record_type}}</td>
                                    <td>
                                        <a title="<?=T::l('Update') ?>" href="" ng-click="edit(c.id)"><span class="fa fa-pencil"></span></a>
                                        <a title="<?=T::l('Deactivate') ?>" href="" ng-click="setStatus(c.id,'<?=RecordType::InActive ?>')" ng-if="c.record_type == '<?=RecordType::Active ?>'"><span class="fa fa-ban"></span></a>
                                        <a title="<?=T::l('Activate') ?>" href="" ng-click="setStatus(c.id, '<?=RecordType::Active ?>')" ng-if="c.record_type == '<?=RecordType::InActive ?>'"><span class="fa fa-check-circle-o"></span></a>
                                        <a title="<?=T::l('Delete') ?>" href="" ng-click="setStatus(c.id, '<?=RecordType::Deleted ?>')"><span class="fa fa-trash"></span></a>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody ng-show="(contacts | filter:filterContacts('<?= $type ?>')).length == 0">
                                <tr>
                                    <td  colspan="6"><?= T::l('No records') ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <?=  $this->action('restaurant-contact-order/form') ?>
</div>
