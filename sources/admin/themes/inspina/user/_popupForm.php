<?php

use admin\common\AHtml;
use common\enums\UserType;
use common\components\language\T;

?>
<div class="text-center">
    <a data-toggle="modal" class="hidden" href="#edit-user-popup-<?= $model ?>" id="user-popup-open-<?= $model ?>"></a>
</div>
<div id="edit-user-popup-<?= $model ?>" class="modal fade" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <form name="tableform">
                            <?= AHtml::waitSpinner(['ng-show' => 'userFormIsSubmitting']) ?>

                            <?= AHtml::input('Username',
                                ['type'=>'email', 'maxlength'=>'255', 'id'=>'username', 'min'=>'0', 'max' => '100', 'ng-model'=>'editedUser.username', 'required'=>''],
                                ['form-name' => 'tableform', 'rules' => ['required' => '... is missing', 'pattern' => '... is invalid']]
                            ) ?>

                            <?= AHtml::input('Password',
                                ['type'=>'password', 'maxlength'=>'255', 'id'=>'new_password', 'ng-model' => 'editedUser.new_password'],
                                ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                            ) ?>

                            <?= AHtml::input('Confirm Password',
                                ['type'=>'password', 'maxlength'=>'255', 'id'=>'repassword', 'ng-model' => 'editedUser.repassword', 'ng-required' => 'editedUser.new_password', 'equals' => 'editedUser.new_password'],
                                ['form-name' => 'tableform', 'rules' => ['required' => '... is missing', 'equals' => '... is not match']]
                            ) ?>


                                <?= AHtml::input('First name',
                                    ['type'=>'text', 'maxlength'=>'255', 'id'=>'first_name', 'ng-model'=>'editedUser.first_name', 'required' => ''],
                                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                                ) ?>

                                <?= AHtml::input('Last name',
                                    ['type'=>'text', 'maxlength'=>'255', 'id'=>'last_name', 'ng-model'=>'editedUser.last_name', 'required' => ''],
                                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                                ) ?>

                                <?= AHtml::input('Title',
                                    ['type'=>'select', 'items'=> \common\enums\AddressTitle::getLabels(), 'id'=>'user_title', 'ng-model'=>'editedUser.user_title', 'required' => ''],
                                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                                ) ?>

                                <?php if ($controller === 'user'): ?>
                                    <?= AHtml::input('Address 1',
                                        ['type'=>'text', 'maxlength'=>'255', 'id'=>'address1', 'ng-model'=>'editedUser.primaryAddress.address1', 'required' => ''],
                                        ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                                    ) ?>

                                    <?= AHtml::input('Address 2',
                                        ['type'=>'text', 'maxlength'=>'255', 'id'=>'address2', 'ng-model'=>'editedUser.primaryAddress.address2'],
                                        ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                                    ) ?>

                                    <?= AHtml::input('Country',
                                        ['type'=>'select', 'items'=> yii\helpers\ArrayHelper::map(\admin\common\ArrayHelper::translateList(common\models\Country::find()->all()),'id','name_key'), 'id'=>'country_id', 'ng-model'=>'editedUser.primaryAddress.country_id', 'required' => ''],
                                        ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                                    ) ?>

                                    <?= AHtml::input('City',
                                        ['type'=>'text', 'maxlength'=>'255', 'id'=>'city', 'ng-model'=>'editedUser.primaryAddress.city', 'required' => ''],
                                        ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                                    ) ?>

                                    <?= AHtml::input('Postcode',
                                        ['type'=>'text', 'maxlength'=>'255', 'id'=>'postcode', 'ng-model'=>'editedUser.primaryAddress.postcode', 'required' => ''],
                                        ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                                    ) ?>

                                    <?= AHtml::input('Phone',
                                        ['type'=>'text', 'maxlength'=>'255', 'id'=>'phone', 'ng-model'=>'editedUser.primaryAddress.phone', 'required' => ''],
                                        ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                                    ) ?>

                                    <label><input type="checkbox" ng-model="editedUser.is_corporate_approved">&nbsp;<?= T::l('Is Corporate Approved') ?></label>
                                <?php endif; ?>


                            <?php if (!$company_id): ?>
                                <?= AHtml::input('Role',
                                    ['type'=>'select', 'items'=> UserType::getLabelsByModel($model), 'id'=>'user_type', 'ng-model'=>'editedUser.user_type', 'required' => ''],
                                    ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                                ) ?>
                            <?php endif; ?>

                            <?php if ($company_id && $controller === 'user'): ?>
                                <?php
                                    $items     = \common\models\Company::getCompanyGroups($company_id);
                                    $selection = '';
                                    foreach ($items as $idx => $item) {
                                        if ($item === \common\enums\DefaultCompanyGroup::DefaultExternal) {
                                            $selection = $idx;
                                        }
                                    }
                                ?>

                                <div ng-if="editedUser.is_corporate_approved">
                                    <?= AHtml::input('User group',
                                        ['type'=>'select', 'items'=> $items, 'id'=>'company_user_group_id', 'ng-model'=>'editedUser.company_user_group_id', 'required' => '', 'selection' => $selection],
                                        ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                                    ) ?>
                                </div>
                            <?php endif; ?>

                            <?= AHtml::errorNotification('{{userSubmitError}}', ['ng-show' => 'hasUserSubmitError()']) ?>

                            <?= AHtml::saveButton(['ng-click' => 'save()', 'ng-disabled' => 'tableform.$invalid']) ?>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
