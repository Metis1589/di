<?php

use admin\common\AHtml;
use common\enums\RestaurantContactOrderType;

?>

<div class="text-center">
    <a data-toggle="modal" class="hidden" href="#edit-contact-popup" id="contact-popup-open"></a>
</div>
<div id="edit-contact-popup" class="modal fade" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <form class="restaurant-contact-order-form" name="tableform">
                            <?= AHtml::waitSpinner(['ng-show' => 'contactOrderFormIsSubmitting']) ?>

                            <?= AHtml::input('Name',
                                ['type'=>'text', 'maxlength'=>'255', 'id'=>'name', 'required'=>'', 'ng-model'=>'editedContact.name'],
                                ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                            ) ?>

                            <?= AHtml::input('Role',
                                ['type'=>'text', 'maxlength'=>'255', 'id'=>'role', 'required'=>'', 'ng-model'=>'editedContact.role'],
                                ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
                            ) ?>

                            <?= AHtml::input('Phone',
                                ['type'=>'text', 'maxlength'=>'50', 'id'=>'number', 'required'=>'', 'ng-model'=>'editedContact.number'],
                                ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']],
                                ['ng-if' => 'editedContact.type != "'.RestaurantContactOrderType::Email.'"']
                            ) ?>

                            <?= AHtml::input('Email',
                                ['type'=>'email', 'maxlength'=>'150', 'id'=>'email','required'=>'', 'ng-model'=>'editedContact.email'],
                                ['form-name' => 'tableform', 'rules' => ['required' => '... is missing', 'pattern' => '... is invalid']],
                                ['ng-if' => 'editedContact.type == "'.RestaurantContactOrderType::Email.'"']
                            ) ?>

                            <?= AHtml::input('Charge',
                                ['type'=>'number', 'step'=>'any', 'maxlength'=>'10', 'id'=>'charge', 'min'=>'0', 'max' => '100000', 'ng-model'=>'editedContact.charge', 'required' => ''],
                                ['form-name' => 'tableform', 'rules' => ['required' => '... is missing', 'number' => '... is invalid', 'min' => '... is low', 'max' => '... is high']]
                            ) ?>

                            <?= AHtml::input('Delay',
                                ['type'=>'number', 'step'=>'any', 'maxlength'=>'10', 'id'=>'delay', 'min'=>'0', 'max' => '100000', 'ng-model'=>'editedContact.delay_in_min', 'required' => ''],
                                ['form-name' => 'tableform', 'rules' => ['required' => '... is missing', 'number' => '... is invalid', 'min' => '... is low', 'max' => '... is high']],
                                ['ng-if' => '(editedContact.type == "'.RestaurantContactOrderType::Ivr.'")']
                            ) ?>

                            <?= AHtml::errorNotification('{{contactOrderSubmitError}}', ['ng-show' => 'hasContactOrderSubmitError()']) ?>

                            <?= AHtml::saveButton(['ng-click' => 'save()', 'ng-disabled' => 'tableform.$invalid']) ?>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


