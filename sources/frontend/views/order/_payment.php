<?php
use frontend\components\language\T;
?>
<div class="payment">
    <div class="ba_wrap">
        <table class="total-mobile">
            <tr>
                <td> <?= T::l('Total Amount Needed') ?> </td>
                <td> {{corporateInfo.currencySymbol}}{{orderTotal() | number:2}}</td>
            </tr>
            <tr>
                <td> <?= T::l('Current Amount Allocated') ?> </td>
                <td ng-class="{'not-valid': toBeAllocated() < 0}"> {{corporateInfo.currencySymbol}}{{totalAllocated() | number:2}}</td>
            </tr>
        </table>
        <div class="exptype_select abs-select">
            <span class="pseudo_input"></span>
            <input type="hidden" placeholder="{{corporateInfo.selectedExpenseType.name}}">
            <ul>
                <li
                    ng-click="changeExpenseType(e.id)"
                    ng-repeat="e in corporateInfo.userGroup.activeExpenseTypes"
                    repeat-end="initExpenseTypeSelect()">
                    <input type="radio" class="orange-cb" ng-model="corporateInfo.selectedExpenseType.id" ng-value="e.id">
                    <label class="orange-cb">{{e.name}}</label>
                </li>
            </ul>
        </div>

        <div class="user-settings-mobile only_mobile slset"  ng-repeat="user in corporateInfo.users" repeat-end="initUserSettingsMobile()" ng-if="!corporateInfo.has_inntouch">
            <i class="select_set_carret"></i>
            <span class="pseudo_input no-update">{{user.corp_user.first_name}} {{user.corp_user.last_name}}</span>
            <div>
                <div class="user-title input">{{user.corp_user.first_name}} {{user.corp_user.last_name}}</div>
                <validation-summary class="standalone_error" form-name="'user_{{$index}}'" custom-error="orderCheckoutError"></validation-summary>
                <form name="user_{{$index}}">
                    <div class="exptype_select input">
                        <span class="pseudo_input"></span>
                        <input type="hidden" name="code" placeholder="{{getSelectedCode($index) != null ? getSelectedCode($index).name : '<?=T::l('CODE')?>'}}">
                        <ul>
                            <li
                                ng-click="changeCode($parent.$index, c.id)"
                                ng-repeat="c in user.user_group.activeCodes"
                                repeat-end="initCodeSelect()">
                                <input type="radio" class="orange-cb" ng-model="corporateInfo.users[$parent.$index].code_id" ng-value="c.id">
                                <label class="orange-cb">{{c.name}}</label>
                            </li>
                        </ul>
                    </div>
                    <div class="input limit">
                        <span><?=T::l('Spending Allowance')?></span>
                        <span>{{corporateInfo.currencySymbol}}{{getCorpUserLimit($index) | number:2}}</span>
                    </div>
                    <input type="text" class="allocation input" name="allocation" ng-model="user.corp_user.allocation" ng-blur="setCorpUserData($index); user.corp_user.isCommentVisible = null" money ng-class="{'ng-invalid' : !isAllocationValid($index) }" placeholder="{{corporateInfo.currencySymbol}} <?= T::l('Allocation') ?>"/>
                    <div class="inline comment-block input" ng-show="isCommentVisible($index)">
                        <textarea ng-model="user.corp_user.comment" name="comment" placeholder="<?=T::l('Please type your reason for exceeding limit here')?>"></textarea>
                        <button class="ready_to_pay" type="button" ng-click="setCorpUserData($index); user.corp_user.isCommentVisible = false;" ng-disabled="user.corp_user.comment.trim() == ''"><?= T::l('ADD COMMENT') ?></button>
                    </div>
                    <button
                        class="submit"
                        ng-disabled="!user_{{$index}}.$valid"
                        type="button"
                        ng-click="setCorpUserData($index)"><?= T::l('APPLY') ?></button>
                </form>

            </div>
        </div>

        <div class="add-user-mobile only_mobile slset" ng-if="!corporateInfo.has_inntouch">
            <i class="select_set_carret"></i>
			<span class="pseudo_input no-update" ng-click="showNewCorpUserForm(-1, false)"><?= T::l('ADD NAME') ?></span>
            <div>
                <div class="user-title input"><?= T::l('ADD NAME') ?></div>
                <validation-summary class="standalone_error" form-name="tableform_add_name" custom-error="orderCheckoutError"></validation-summary>
                <form name="tableform_add_name_mobile">
                    <input type="text" class="label name input" placeholder="<?=T::l('FIRST NAME')?>" name="first_name" ng-model="newCorpUser.firstName" maxlength="255" required="">
                    <input type="text" class="label name input" placeholder="<?=T::l('LAST NAME')?>" name="last_name" ng-model="newCorpUser.lastName" maxlength="255" required=""/>
                    <input type="email" class="label name input" placeholder="<?=T::l('EMAIL')?>" name="email" ng-model="newCorpUser.email" maxlength="255" required=""/>
                    <input type="text" class="label name input"  placeholder="<?=T::l('COMPANY')?>" name="company" ng-model="newCorpUser.company" maxlength="255" required=""/>
                    <button class="submit" ng-click="addCorpUser()" ng-disabled="tableform_add_name_mobile.$invalid" type="button"><?= T::l('ADD NAME TO ORDER') ?></button>
                </form>
            </div>
        </div>

        <div class="user-settings only_desctop" ng-repeat="user in corporateInfo.users" ng-if="!corporateInfo.has_inntouch">
            <div class="inline">
                <input type="text" class="label name" disabled="disabled" value="{{user.corp_user.first_name}} {{user.corp_user.last_name}}"/>
            </div>

            <div class="exptype_select inline">
                <span class="pseudo_input"></span>
                <input type="hidden" placeholder="{{getSelectedCode($index) != null ? getSelectedCode($index).name : '<?= T::l('Code')?>'}}">
                <ul>
                    <li
                        ng-click="changeCode($parent.$index, c.id)"
                        ng-repeat="c in user.user_group.activeCodes"
                        repeat-end="initCodeSelect()">
                        <input type="radio" class="orange-cb" ng-model="corporateInfo.users[$parent.$index].code_id" ng-value="c.id">
                        <label class="orange-cb">{{c.name}}</label>
                    </li>
                </ul>
            </div>

            <p class="limit inline only_desctop"> {{corporateInfo.currencySymbol}}{{getCorpUserLimit($index) | number:2}}</p>
            <dinein-tooltip
                class="only_desctop"
                header="'<?=T::l('Order Limit')?>'"
                message="<?=T::l('The selected expense type has a')?> {{corporateInfo.currencySymbol}}{{getCorpUserLimit($index) | number:2}} <?=T::l('hard limit')?> {{isCorpUserLimitSoft($index) ? ('<?= T::l('and a') ?> ' + corporateInfo.currencySymbol + (getCorpUserLimitSoft($index) | number:2) + ' <?= T::l('soft limit') ?>') : ''}} <?= T::l('for this user\'s group') ?>"></dinein-tooltip>
            <div class="inline">
                <input type="text" class="allocation" ng-model="user.corp_user.allocation" ng-blur="setCorpUserData($index); user.corp_user.isCommentVisible = null" money ng-class="{'ng-invalid' : !isAllocationValid($index) }" placeholder="{{corporateInfo.currencySymbol}} <?= T::l('Allocation') ?>"/>
            </div>
            <div class="inline remove-corp-user" ng-click="removeCorpUser($index)" ng-show="$index > 0"></div>
            <div class="inline comment-block" ng-show="isCommentVisible($index)">
                <textarea ng-model="user.corp_user.comment" placeholder="<?=T::l('Please type your reason for exceeding limit here')?>"></textarea>
                <button class="ready_to_pay" type="button" ng-click="setCorpUserData($index); user.corp_user.isCommentVisible = false;" ng-disabled="user.corp_user.comment.trim() == ''"><?= T::l('ADD COMMENT') ?></button>
            </div>

        </div>

        <a class="slset add-name-btn only_desctop" ng-click="showNewCorpUserForm(-1, true)" ng-show="!showAddName" ng-if="!corporateInfo.has_inntouch">
            <?= T::l('ADD NAME') ?>
            <i class="select_set_carret"></i>
        </a>

        <form action="" name="tableform_add_name" id="tableform_add_name" ng-show="showAddName" class="add-name-form" ng-if="!corporateInfo.has_inntouch">
            <a class="add-name-header" ng-click="hideNewCorpUserForm()"><?= T::l('ADD NAME') ?></a>
            <p ng-show="addUserError">{{addUserError}}</p>
            <input type="email" name="email" ng-model="newCorpUser.email" placeholder="<?= T::l('EMAIL') ?>" maxlength="255" required=""/>
            <input type="text" name="firstName" ng-model="newCorpUser.firstName" placeholder="<?= T::l('FIRST NAME') ?>" maxlength="255" required=""/>
            <input type="text" name="lastName" ng-model="newCorpUser.lastName" placeholder="<?= T::l('LAST NAME') ?>" maxlength="255" required=""/>
            <input type="text" name="company" ng-model="newCorpUser.company" placeholder="<?= T::l('COMPANY') ?>" maxlength="255" required=""/>
            <button class="ready_to_pay" type="button" ng-click="addCorpUser()" ng-disabled="tableform_add_name.$invalid"><?= T::l('ADD NAME TO ORDER') ?></button>
        </form>

        <div class="clearfix" ng-if="!corporateInfo.has_inntouch"></div>

        <table class="total" ng-if="!corporateInfo.has_inntouch">
            <tr>
                <td>
                    <?=T::l('Total Allocated') ?>
                </td>
                <td>
                    {{corporateInfo.currencySymbol}}{{totalAllocated() | number:2}}
                </td>
            </tr>
            <tr>
                <td>
                    <?=T::l('To be Allocated or Paid') ?>
                </td>
                <td ng-class="{'not-valid': toBeAllocated() < 0}">
                    {{corporateInfo.currencySymbol}}{{toBeAllocated() | number:2}}
                </td>
            </tr>
            <tr>
                <td>
                    <?=T::l('Order Total') ?>
                </td>
                <td>
                    {{corporateInfo.currencySymbol}}{{orderTotal() | number:2}}
                </td>
            </tr>
        </table>
    </div>
</div>
