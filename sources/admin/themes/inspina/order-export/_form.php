<?php
/**
 * @author jarik <jarik1112@gmail.com>
 * @date   7/16/15
 * @time   11:34 AM
 */

use admin\common\AHtml;
use common\enums\RecordType;
use common\components\language\T;
use common\enums\OrderExportType;

$selectedExport = !empty($selectedExport) ? $selectedExport :  false;
?>
<div class="order-export-form" ng-controller="orderExportController">
    <script type="text/javascript">
        var orderExportModel = '<?= $model ?>';
    </script>

    <?= AHtml::input(T::l('Is Active'),
        [
            'type'=>'checkbox',
            'ng-model'=>'orderExportIsActive',
            'ng-click' => 'switchType("'.RecordType::Active.'","'.RecordType::InActive.'")'
        ]
    ) ?>

    <form name="exportForm" >
        <?= AHtml::waitSpinner(['ng-show' => 'formSubmitting']) ?>
        <hr/>
        <div class="row" ng-if="orderExportIsActive" >
            <div class="col-xs-2">
                <?= AHtml::saveButton(['ng-click' => 'addNewConfig()'],T::l('Add new config')) ?>
            </div>
        </div>
        <div  ng-repeat="c in export" ng-if="orderExportIsActive">

            <div class="row">
                <hr />
                <div class="col-xs-7">
                    <?= AHtml::input(
                        T::l('Notification Email (Comma separated)'),
                        ['type' => 'text', 'maxlength' => 100, 'id' => 'email_{{$index}}', 'ng-model' => 'c.email']
                    ) ?>

                    <?= AHtml::input(
                        'File Name Prefix',
                        ['type' => 'text', 'maxlength' => 100, 'id' => 'prefix_{{$index}}', 'ng-model' => 'c.filename']
                    ) ?>
                    <?php
                    if(!$selectedExport) {
                        echo AHtml::input(
                            T::l('Export Template'),
                            [
                                'type'      => 'select',
                                'items'     => [
                                    OrderExportType::NewOrders => 'New Orders',
                                    OrderExportType::NewUsers  => 'New Users',
                                ],
                                'selection' => OrderExportType::NewOrders,
                                'ng-model'  => 'c.type',
                            ]
                        );
                    }else{
                        echo AHtml::input('',['type'=>'hidden','ng-init'=>'c.type = "'.$selectedExport.'"','value'=>$selectedExport]);
                    }
                    ?>

                    <h4><?=T::l('Sftp config')?></h4>
                    <?= AHtml::input(
                        'Ssh User',
                        ['type' => 'text', 'maxlength' => 50, 'id' => 'ssh_user_{{$index}}', 'ng-model' => 'c.ssh_user']
                    ) ?>

                    <?= AHtml::input(
                        'Ssh Host',
                        ['type' => 'text', 'maxlength' => 100, 'id' => 'ssh_host_{{$index}}', 'ng-model' => 'c.ssh_host']
                    ) ?>
                    <?= AHtml::input(
                        'Ssh Port',
                        ['type' => 'text', 'maxlength' => 5, 'id' => 'ssh_port_{{$index}}', 'ng-model' => 'c.ssh_port']
                    ) ?>

                    <?= AHtml::input(
                        'Ssh Password',
                        ['type' => 'text', 'maxlength' => 50, 'id' => 'ssh_password_{{$index}}', 'ng-model' => 'c.ssh_password']
                    ) ?>

                    <div class="form-group">
                        <label class="control-label" for="ssh_public_key_{{$index}}"><?=T::l('Ssh Public Key')?></label>
                        <?= \yii\helpers\Html::textarea(
                            'ssh_public_key_{{$index}}',
                            '',
                            ['class'=>"form-control",'rows'=>"5",'id' => 'ssh_public_key_{{$index}}}', 'ng-model' => 'c.ssh_public_key']
                        ) ?>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="ssh_private_key_{{$index}}"><?=T::l('Ssh Public Key')?></label>
                        <?= \yii\helpers\Html::textarea(
                            'ssh_private_key_{{$index}}',
                            '',
                            ['class'=>"form-control",'rows'=>"5",'id' => 'ssh_private_key_{{$index}}', 'ng-model' => 'c.ssh_private_key']
                        ) ?>
                    </div>

                    <?= AHtml::input(
                        'Ssh Passphrase',
                        ['type' => 'text', 'maxlength' => 100,'id' => 'ssh_key_passpharse_{{$index}}', 'ng-model' => 'c.ssh_key_passpharse']
                    ) ?>

                    <?= AHtml::input(
                        'Host Path',
                        ['type' => 'text', 'maxlength' => 255, 'id' => 'hos_dir_{{$index}}', 'ng-model' => 'c.host_dir']
                    ) ?>
                </div>
                <div class="col-xs-2">
                    <?= AHtml::saveButton(['ng-click' => 'removeConfig(c.id,$index)'],'Remove config') ?>
                </div>
            </div>

            <?= AHtml::errorNotification('{{submitError}}', ['ng-show' => 'submitError']) ?>
        </div>
        <?= AHtml::saveButton(['ng-click' => 'saveExport()', 'ng-disabled' => 'tableform.$invalid']) ?>
    </form>
</div>
