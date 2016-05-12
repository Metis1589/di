<?php

use admin\common\AHtml;
use common\enums\UserType;

?>

<style>
    .form-group {
        margin-bottom: 5px !important;
    }
</style>    
<div class="text-center">
    <a data-toggle="modal" class="hidden" href="#edit-user-popup" id="user-popup-open"></a>
</div>
<div id="edit-user-popup" class="modal fade" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <form name="tableform">

                            <?= AHtml::waitSpinner(['ng-show' => 'userFormIsSubmitting']) ?>
                            
                            <?= AHtml::input('name',
                                ['maxlength'=>'255', 'id'=>'name', 'min'=>'0', 'max' => '100', 'ng-model'=>'editedUser.address.name', 'required'=>''],
                                ['form-name' => 'tableform', 'rules' => ['required' => '... is missing', 'pattern' => '... is invalid']]
                            ) ?>
                            
                            <?= AHtml::input('title',
                                ['type'=>'select', 'items' => \common\enums\AddressTitle::getLabels(), 'id'=>'title', 'ng-model'=>'editedUser.address.title', 'required'=>''],
                                ['form-name' => 'tableform', 'rules' => ['required' => '... is missing', 'pattern' => '... is invalid']]
                            ) ?>
                            
                            <?= AHtml::input('first_name',
                                ['maxlength'=>'255', 'id'=>'first_name', 'min'=>'0', 'max' => '100', 'ng-model'=>'editedUser.address.first_name', 'required'=>''],
                                ['form-name' => 'tableform', 'rules' => ['required' => '... is missing', 'pattern' => '... is invalid']]
                            ) ?>
                            
                            <?= AHtml::input('last_name',
                                ['maxlength'=>'255', 'id'=>'last_name', 'min'=>'0', 'max' => '100', 'ng-model'=>'editedUser.address.last_name', 'required'=>''],
                                ['form-name' => 'tableform', 'rules' => ['required' => '... is missing', 'pattern' => '... is invalid']]
                            ) ?>
                            
                            <?= AHtml::input('country_id',
                                ['type'=>'select', 'items' => \common\models\Country::getCountriesForSelect(), 'id'=>'country_id', 'ng-model'=>'editedUser.address.country_id', 'required'=>''],
                                ['form-name' => 'tableform', 'rules' => ['required' => '... is missing', 'pattern' => '... is invalid']]
                            ) ?>
                            
                            <?= AHtml::input('city',
                                ['maxlength'=>'255', 'id'=>'city', 'min'=>'0', 'max' => '100', 'ng-model'=>'editedUser.address.city', 'required'=>''],
                                ['form-name' => 'tableform', 'rules' => ['required' => '... is missing', 'pattern' => '... is invalid']]
                            ) ?>
                            
                            <?= AHtml::input('postcode',
                                ['maxlength'=>'255', 'id'=>'postcode', 'min'=>'0', 'max' => '100', 'ng-model'=>'editedUser.address.postcode', 'required'=>''],
                                ['form-name' => 'tableform', 'rules' => ['required' => '... is missing', 'pattern' => '... is invalid']]
                            ) ?>
                            
                            <?= AHtml::input('address1',
                                ['maxlength'=>'255', 'id'=>'address1', 'min'=>'0', 'max' => '100', 'ng-model'=>'editedUser.address.address1', 'required'=>''],
                                ['form-name' => 'tableform', 'rules' => ['required' => '... is missing', 'pattern' => '... is invalid']]
                            ) ?>
                            
                            <?= AHtml::input('address2',
                                ['maxlength'=>'255', 'id'=>'address2', 'min'=>'0', 'max' => '100', 'ng-model'=>'editedUser.address.address2'],
                                ['form-name' => 'tableform', 'rules' => ['required' => '... is missing', 'pattern' => '... is invalid']]
                            ) ?>
                            
                            <?= AHtml::input('address3',
                                ['maxlength'=>'255', 'id'=>'address3', 'min'=>'0', 'max' => '100', 'ng-model'=>'editedUser.address.address3'],
                                ['form-name' => 'tableform', 'rules' => ['required' => '... is missing', 'pattern' => '... is invalid']]
                            ) ?>
                            
                            <?= AHtml::input('phone',
                                ['maxlength'=>'255', 'id'=>'phone', 'min'=>'0', 'max' => '100', 'ng-model'=>'editedUser.address.phone', 'required'=>''],
                                ['form-name' => 'tableform', 'rules' => ['required' => '... is missing', 'pattern' => '... is invalid']]
                            ) ?>
                            
                            <?= AHtml::input('address_type',
                                ['type'=>'select', 'items'=> common\enums\UserAddressType::getLabels(),'maxlength'=>'255', 'id'=>'address_type', 'min'=>'0', 'max' => '100', 'ng-model'=>'editedUser.address_type', 'required'=>''],
                                ['form-name' => 'tableform', 'rules' => ['required' => '... is missing', 'pattern' => '... is invalid']]
                            ) ?>

                            <?= AHtml::errorNotification('{{userSubmitError}}', ['ng-show' => 'hasUserSubmitError()']) ?>

                            <?= AHtml::saveButton(['ng-click' => 'save()', 'ng-disabled' => 'tableform.$invalid']) ?>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
