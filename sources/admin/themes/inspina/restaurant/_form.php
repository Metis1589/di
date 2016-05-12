<?php

use common\enums\AddressType;
use common\enums\RestaurantAddressType;
use common\enums\UserType;
use common\models\RestaurantChain;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\language\T;
use common\components\ImageHelper;
use common\components\IOHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Restaurant */
/* @var $form yii\widgets\ActiveForm */

$this->registerModelActionButtons($model);

if (Yii::$app->request->isImpersonated()) {
    $groupOptions = RestaurantChain::getHierarchyForSelect(Yii::$app->request->getImpersonatedClientId());
} else {
    $groupOptions = RestaurantChain::getHierarchyForSelect($model->client_id);
}
$canChangeGroup = Yii::$app->request->isImpersonated() && in_array(Yii::$app->user->identity->user_type, [UserType::Admin, UserType::ClientAdmin]);

?>

<div class="restaurant-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row form-group">
        
        <div class="col-md-6">
            <?= $form->field($model, 'name',[
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
                ])->textInput(['maxlength' => 255]) ?>
    
            <?= $form->field($model, 'trading_name',[
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
                ])->textInput(['maxlength' => 255]) ?>
            
            <?= $form->field($model, 'address_base_id',[
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
                ])->dropDownList(common\models\AddressBase::getAddressBaseForSelect(),['prompt'=>T::l('Choose address base')]) ?>

            <?= $form->field($model, 'restaurant_group_id',[
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
                ])->dropDownList($groupOptions['items'],['prompt'=>T::l('None'),'options' => $groupOptions['options'],'disabled' => !$canChangeGroup ]) ?>

            <?= empty($model->photo->image_name) ? '' : Html::img(Yii::$app->params['images_base_url'] . ImageHelper::getThumbFilename(IOHelper::getRestaurantImagesPath().$model->photo->image_name), ['height' => '100']) ?>
            <?= $form->field($model->photo, 'image_name')->fileInput() ?>
            
            <?= empty($model->logo_file_name) ? '' : Html::img(Yii::$app->params['images_base_url'] . ImageHelper::getThumbFilename(IOHelper::getRestaurantLogoPath().$model->logo_file_name), ['height' => '100']) ?>
            <?= $form->field($model, 'logo_file_name')->fileInput() ?>
        </div>
        
        <div class="col-md-6">
            <?= $form->field($model, 'is_newest')->checkbox(['class' => 'i-checks']) ?>

            <?= $form->field($model, 'is_featured')->checkbox(['class' => 'i-checks']) ?>
            
            <?= $form->field($model, 'currency_id',[
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
                ])->dropDownList(common\models\Currency::getCurrenciesForSelect(),['prompt'=>T::l('Choose currency')]) ?>
            
            <?= $form->field($model, 'vat_number',[
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
                ])->textInput(['maxlength' => 255]) ?>

        </div>   
    </div>

    <div class="row form-group">
        <legend><?= T::l('Physical Address') ?></legend>
        <div class="col-md-6">

            <?= $form->field($model->physicalAddress, "[physical]address1",[
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
                ])->textInput(['maxlength' => 50]) ?>

            <?= $form->field($model->physicalAddress, "[physical]address2",[
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
                ])->textInput(['maxlength' => 50]) ?>


            <?= $form->field($model->physicalAddress, "[physical]city",[
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
                ])->textInput(['maxlength' => 50]) ?>

            <?= $form->field($model->physicalAddress, "[physical]postcode",[
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
                ])->textInput(['maxlength' => 45]) ?>

            <?= $form->field($model->physicalAddress, "[physical]country_id",[
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
                ])->dropDownList(yii\helpers\ArrayHelper::map(\admin\common\ArrayHelper::translateList(common\models\Country::find()->where(['<>', 'record_type', \common\enums\RecordType::Deleted])->all()),'id','name_key'),['prompt'=>T::l('Choose country')]) ?>

            <?= $form->field($model->physicalAddress, "[physical]phone",[
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
                ])->textInput(['maxlength' => 50]) ?>

        </div>
        <div class="col-md-6">
            <?= $form->field($model->physicalAddress, "[physical]latitude",[
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
                ])->textInput(['maxlength' => 20]) ?>
            <?= $form->field($model->physicalAddress, "[physical]longitude",[
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
                ])->textInput(['maxlength' => 20]) ?>

        </div>
    </div>
    <div class="row form-group">
        <legend><?= T::l('Pickup Address') ?></legend>
        <div class="col-md-6">

            <?= $form->field($model->pickupAddress, "[pickup]address1",[
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
            ])->textInput(['maxlength' => 50]) ?>

            <?= $form->field($model->pickupAddress, "[pickup]address2",[
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
            ])->textInput(['maxlength' => 50]) ?>

            <?= $form->field($model->pickupAddress, "[pickup]city",[
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
            ])->textInput(['maxlength' => 50]) ?>

            <?= $form->field($model->pickupAddress, "[pickup]postcode",[
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
            ])->textInput(['maxlength' => 45]) ?>

            <?= $form->field($model->pickupAddress, "[pickup]country_id",[
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
            ])->dropDownList(yii\helpers\ArrayHelper::map(\admin\common\ArrayHelper::translateList(common\models\Country::find()->where(['<>', 'record_type', \common\enums\RecordType::Deleted])->all()),'id','name_key'),['prompt'=>T::l('Choose country')]) ?>

            <?= $form->field($model->pickupAddress, "[pickup]phone",[
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
            ])->textInput(['maxlength' => 50]) ?>

        </div>
        <div class="col-md-6">
            <?= $form->field($model->pickupAddress, "[pickup]latitude",[
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
            ])->textInput(['maxlength' => 20]) ?>
            <?= $form->field($model->pickupAddress, "[pickup]longitude",[
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
            ])->textInput(['maxlength' => 20]) ?>

        </div>
    </div>

    <div class="row form-group">
        <?php $contactModel = $model->contact;?>
        <legend><?= T::l('Contact') ?></legend>
        <div class="col-md-6">
            <?= $form->field($contactModel, 'first_name',[
                'selectors'=>[
                    'input' => '#contact_first_name',
                    'container' => '#container_contact_first_name'
                ],
                'options'=>[
                    'class'=>'form-group contact_first_name',
                    'id'=>'container_contact_first_name'
                ],
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
                ])->textInput(['maxlength' => 50, 'name'=>'Contact[first_name]', 'id'=>'contact_first_name']) ?>
            
            <?= $form->field($contactModel, 'last_name',[
                'selectors'=>[
                    'input' => '#contact_last_name',
                    'container' => '#container_contact_last_name'
                ],
                'options'=>[
                    'class'=>'form-group contact_last_name',
                    'id'=>'container_contact_last_name'
                ],
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
                ])->textInput(['maxlength' => 50, 'name'=>'Contact[last_name]', 'id'=>'contact_last_name']) ?>
            
            <?= $form->field($contactModel, 'contact_role',[
                'selectors'=>[
                    'input' => '#contact_role',
                    'container' => '#container_contact_role'
                ],
                'options'=>[
                    'class'=>'form-group contact_role',
                    'id'=>'container_contact_role'
                ],
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
                ])->textInput(['maxlength' => 50, 'name'=>'Contact[contact_role]', 'id'=>'contact_role','prompt'=>T::l('Choose role')]) ?>

            <?= $form->field($contactModel, 'number',[
                'selectors'=>[
                    'input' => '#contact_number',
                    'container' => '#container_contact_number',
                ],
                'options'=>[
                    'class'=>'form-group contact_number',
                    'id'=>'container_contact_number'
                ],
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
                ])->textInput(['maxlength' => 50, 'name'=>'Contact[number]', 'id'=>'contact_number']) ?>


            <?= $form->field($contactModel->emails[0], 'id',[
                'selectors'=>[
                    'input' => '#contact_email_0_id',
                    'container' => '.contact_email_0_id'
                ],
                'options'=>[
                    'class'=>'form-group contact_email_0_id'
                ],
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
                ])->hiddenInput(['name'=>'Contact[emails][0][id]', 'id'=>'contact_email_0_id'])
                ->label(false) ?>
                
            <?= $form->field($contactModel->emails[0], 'email',[
                'selectors'=>[
                    'input' => '#contact_email_0_email',
                    'container' => '.contact_email_0_email'
                ],
                'options'=>[
                    'class'=>'form-group contact_email_0_email'
                ],
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
                ])->textInput(['maxlength' => 49, 'name'=>'Contact[emails][0][email]', 'id'=>'contact_email_0_email']) ?>
        </div>
        <div class="col-md-6"></div> 
    </div>
    
    <div class="row form-group">
        <legend><?= T::l('Billing') ?></legend>
        <?php $contactModel = $model->billing;?>
        <div class="col-md-6">
            <?= $form->field($contactModel, 'first_name',[
                'selectors'=>[
                    'input' => '#billing_first_name',
                    'container' => '#container_billing_first_name'
                ],
                'options'=>[
                    'class'=>'form-group billing_first_name',
                    'id'=>'container_billing_first_name'
                ],
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
                ])->textInput(['maxlength' => 50, 'name'=>'Billing[first_name]', 'id'=>'billing_first_name']) ?>
            
            <?= $form->field($contactModel, 'last_name',[
                'selectors'=>[
                    'input' => '#billing_last_name',
                    'container' => '#container_billing_last_name'
                ],
                'options'=>[
                    'class'=>'form-group billing_last_name',
                    'id'=>'container_billing_last_name'
                ],
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
                ])->textInput(['maxlength' => 50, 'name'=>'Billing[last_name]', 'id'=>'billing_last_name']) ?>
            
            <?= $form->field($contactModel, 'contact_role',[
                'selectors'=>[
                    'input' => '#billing_role',
                    'container' => '#container_billing_role'
                ],
                'options'=>[
                    'class'=>'form-group billing_role',
                    'id'=>'container_billing_role'
                ],
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
                ])->textInput(['maxlength' => 50, 'name'=>'Billing[contact_role]', 'id'=>'billing_role','prompt'=>T::l('Choose role')]) ?>

            <?= $form->field($contactModel, 'number',[
                'selectors'=>[
                    'input' => '#billing_number',
                    'container' => '#container_billing_number'
                ],
                'options'=>[
                    'class'=>'form-group billing_number',
                    'id'=>'container_billing_number'
                ],
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
                ])->textInput(['maxlength' => 50, 'name'=>'Billing[number]', 'id'=>'billing_number']) ?>
            <div class="restaurant-contact-emails">
                <input class="deleted_restaurant_contact_emails" type="hidden" name="Billing[deletedEmails]"/>
                <?php foreach ($contactModel->emails as $i => $email) : ?>
                    <div class="restaurant_contact_email" data-count = "<?= $i ?>" data-id = "<?= $email->isNewRecord ? 0 : $email->id ?>">
                        <?= $form->field($contactModel->emails[$i], "[$i]id")->hiddenInput([
                            'name'=>'Billing[emails]['.$i.'][id]', 
                            'id'=>'billing_email_'.$i.'_id',
                        ])->label(false) ?>
                        <?= $form->field($contactModel->emails[$i], "[$i]email", [
                            'selectors'=>[
                                'input' => '#billing_email_'.$i.'_email',
                                'container' => '.billing_email_'.$i.'_email'
                            ],
                            'options'=>[
                                'class'=>'form-group billing_email_'.$i.'_email',
                            ],
                            'inputOptions'=>[
                                'style'=>'width: 95%; margin-right; 5px;display: inline-block;',
                                'class'=>'form-control'
                            ],
                            'template' => '<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}&nbsp;<a class="restaurant_contact_email_delete"><span class="fa fa-times"></span></a></div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'])->textInput([
                                'name'=>'Billing[emails]['.$i.'][email]', 
                                'id'=>'billing_email_'.$i.'_email',
                                'maxlength' => 49
                            ])->label(Yii::t('label','Email') . ' ' . $i > 0 ? $i : Yii::t('label','Email')); ?>
                    </div>
                <?php endforeach; ?>
                <div class="row">
                    <div class="col-md-6 col-md-offset-4">
                        <div class="link_wrapper">
                            <a href="" class="new_restaurant_contact_email"><?= T::l('Add email') ?></a>
                        </div>
                    </div>
                    <input type="hidden" value="<?= Yii::t('error','{email} is missing',['{email}'=>$email->getAttributeLabel('email')]) ?>" class="email-error-source">
                    <input type="hidden" value="<?= Yii::t('error','{email} is not a valid email address',['{email}'=>$email->getAttributeLabel('email')]) ?>" class="email-format-error-source">
                </div>
            </div>
        </div>
    </div>
    
    <div class="row form-group">
        <legend><?= T::l('SEO') ?></legend>
        <div class="col-md-6">
            <?= $form->field($model, 'slug',[
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
                ])->textInput(['maxlength' => 255]) ?>
            
            <?= $form->field($model, 'seo_title',[
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
                ])->textInput(['maxlength' => 255]) ?>
            
            <?= $form->field($model, 'seo_area_id',[
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
                ])->dropDownList(\common\models\SeoArea::getSeoAreaBaseForSelect(),['prompt'=>T::l('Choose seo area')]) ?>

            <?= $form->field($model, 'meta_text',[
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
                ])->textInput(['maxlength' => 1000]) ?>

            <?= $form->field($model, 'meta_description',[
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
                ])->textInput(['maxlength' => 255]) ?>
        </div>
        <div class="col-md-6"></div>      
    </div>
    
    <div class="row form-group">
        <legend><?= T::l('Restaurant description') ?></legend>

        <?=  $this->action('language/translate-control', ['form' => $form, 'model' => $model, 'property' => 'description', 'inputtype'=>'editor']) ?>

        <div class="col-md-6">
            <?php /*
            <?php $model->description = \yii\helpers\Html::decode($model->description); ?>
            <?= $form->field($model, 'description',[
                'template'=>'<div class="row"><div class="col-sm-12">{label}</div><div class="col-sm-12">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
                ])->widget(\mihaildev\ckeditor\CKEditor::className(),[
                        'model' => $model,
                        'attribute' => 'description',
                        'editorOptions' => ['allowedContent' => true]
                ]) ?>
            */ ?>

            <?= $form->field($model, 'price_range',[
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
                ])->dropDownList(common\enums\PriceRange::getLabels(),['prompt'=>T::l('Choose price range')]) ?>
            
            <?= $form->field($model, 'default_cook_time',[
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
                ])->textInput(['maxlength' => 8,'class'=>'form-control time-picker','data-show24Hours'=>'true','data-showseconds'=>'true']) ?>
            
            <?= $form->field($model, 'default_preparation_time',[
                'template'=>'<div class="row"><div class="col-sm-4">{label}</div><div class="col-sm-8">{input}</div></div><div class="col-sm-12">{hint}</div><div class="col-sm-12">{error}</div>'
                ])->textInput(['maxlength' => 8,'class'=>'form-control time-picker','data-show24Hours'=>'true','data-showseconds'=>'true']) ?>
        </div>
        <div class="col-md-6"></div> 
    </div>

    <?=  $this->render('../common/_record_submit_btn', [
        'model' => $model,
    ]) ?>

    <?php ActiveForm::end(); ?>

</div>
