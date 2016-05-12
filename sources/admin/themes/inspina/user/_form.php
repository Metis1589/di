<?php

use admin\common\AHtml;
use common\components\language\T;
use yii\widgets\ActiveForm;
use common\enums\UserType;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */

$this->registerModelActionButtons($model);

if (Yii::$app->request->isImpersonated()) {
    $userTypesList = \common\enums\UserType::getUserTypes('restaurant_admin');
} else {
    $userTypesList = \common\enums\UserType::getLabels();
}

$this->registerJs('$("#user-user_type").change();');
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(['options' => ['name' => 'tableform']]); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => 255]) ?>

    <?= AHtml::input('Password',
        ['type'=>'password', 'maxlength'=>'255', 'id'=>'password', 'ng-model' => 'password'],
        ['form-name' => 'tableform', 'rules' => ['required' => '... is missing']]
   ) ?>

    <?= AHtml::input('Confirm Password',
        ['type'=>'password', 'maxlength'=>'255', 'id'=>'repassword', 'ng-model' => 'repassword', 'ng-required' => 'password', 'equals' => 'password'],
        ['form-name' => 'tableform', 'rules' => ['required' => '... is missing', 'equals' => '... is not match']]
    ) ?>

    <?= $form->field($model, 'first_name')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'last_name')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model->primaryAddress, 'address1')->textInput(['maxlength' => 255])->label(T::l('Address 1')) ?>

    <?= $form->field($model->primaryAddress, 'address2')->textInput(['maxlength' => 255])->label(T::l('Address 2')) ?>

    <?= $form->field($model->primaryAddress, 'country_id')->dropDownList(\common\models\Country::getCountriesForSelect(), ['prompt' => Yii::t('label', 'Please select country'),]) ?>

    <?= $form->field($model->primaryAddress, 'city')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model->primaryAddress, 'postcode')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model->primaryAddress, 'phone')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'title')->dropDownList(\common\enums\AddressTitle::getLabels(), ['prompt' => Yii::t('label', 'Please select title'),]) ?>

    <?= $form->field($model, 'user_type')->dropDownList($userTypesList, [
        'prompt'   => Yii::t('label', 'Please select user type'),
        'onchange' => '
            $("[id$=\'Select\']").hide();
            $("[id$=\'Select\']").attr("disabled","disabled");
            $(".user-form").find("#"+$(this).val()+"Select").removeAttr("disabled");
            var value = $(this).val();
            if (value == "'.UserType::RestaurantTeam.'" || value == "'.UserType::RestaurantApp.'") {
                value = "'.UserType::RestaurantAdmin.'";
            }

            if (value == "'.UserType::CorporateMember.'") {
                value = "'.UserType::CorporateAdmin.'";
            }

            if (value == "'.UserType::Member.'" || value == "'.UserType::CorporateMember.'"  || value == "'.UserType::InnTouch.'") {
                value = "'.UserType::ClientAdmin.'";
            }

            $(".user-form").find("#"+value+"Select").show();
        '])
    ?>
    <div id="ClientAdminSelect" class="none">
        <?= $form->field($model, 'client_id')->dropDownList(\common\models\User::getCollectionByModel('client'), ['prompt' => Yii::t('label', 'Please select client')]) ?>
    </div>
    <div id="CorporateAdminSelect" class="none">
        <?= $form->field($model, 'company_id')->dropDownList(\common\models\User::getCollectionByModel('company'), ['prompt' => Yii::t('label', 'Please select company')]) ?>
    </div>
    <div id="RestaurantChainAdminSelect" class="none">
        <?= $form->field($model, 'restaurant_chain_id')->dropDownList(\common\models\User::getCollectionByModel('restaurant_chain'), ['prompt' => Yii::t('label', 'Please select restaurant chain')]) ?>
    </div>
    <div id="RestaurantGroupAdminSelect" class="none">
        <?= $form->field($model, 'restaurant_group_id')->dropDownList(\common\models\User::getCollectionByModel('restaurant_group'), ['prompt' => Yii::t('label', 'Please select restaurant group')]) ?>
    </div>
    <div id="RestaurantAdminSelect" class="none">
        <?= $form->field($model, 'restaurant_id')->dropDownList(\common\models\User::getCollectionByModel('restaurant'), ['prompt' => Yii::t('label', 'Please select restaurant')]) ?>
    </div>
    <?=
        $this->render('../common/_record_info', [
            'model' => $model,
            'form' => $form
        ])
    ?>

    <?=
        $this->render('../common/_record_submit_btn', [
            'model' => $model,
        ])
    ?>

<?php ActiveForm::end(); ?>

</div>
