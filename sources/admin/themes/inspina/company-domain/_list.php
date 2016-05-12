<?php
use common\components\language\T;
use common\enums\RecordType;
?>

<div class="user-list" ng-controller="domainsController">
    <div class="row">
        <div class="col-xs-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><?= T::l('Domains') ?></h5>
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
                                <th><?= T::l('Domain') ?></th>
                                <th><?= T::l('Status') ?></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody ng-show="(domains | filter:filterDomains).length > 0">
                            <tr ng-repeat="d in domains | filter:filterDomains" ng-class="d.record_type == '<?= RecordType::InActive ?>' ? 'danger' : '' ">
                                <td>{{d.id}}</td>
                                <td>{{d.domain}}</td>
                                <td>{{d.record_type}}</td>
                                <td>
                                    <a title="<?= T::l('Update') ?>"     href="" ng-click="edit(d.id)"><span class="fa fa-pencil"></span></a>
                                    <a title="<?= T::l('Deactivate') ?>" href="" ng-click="setStatus(d.id, '<?= RecordType::InActive ?>')" ng-if="d.record_type == '<?= RecordType::Active ?>'"><span class="fa fa-ban"></span></a>
                                    <a title="<?= T::l('Activate') ?>"   href="" ng-click="setStatus(d.id, '<?= RecordType::Active ?>')"   ng-if="d.record_type == '<?= RecordType::InActive ?>'"><span class="fa fa-check-circle-o"></span></a>
                                    <a title="<?= T::l('Delete') ?>"     href="" ng-click="setStatus(d.id, '<?= RecordType::Deleted ?>')"><span class="fa fa-trash"></span></a>
                                </td>
                            </tr>
                        </tbody>
                        <tbody ng-show="(domains | filter:filterDomains).length == 0">
                            <tr>
                                <td  colspan="6"><?= T::l('No records') ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?= $this->action('company-domain/popup-form') ?>
</div>