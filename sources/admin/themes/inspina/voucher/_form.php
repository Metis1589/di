<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \common\enums\VoucherCategory;

/* @var $this yii\web\View */
/* @var $model common\models\Voucher */
/* @var $form yii\widgets\ActiveForm */

$this->registerJs('$("#voucher-category").change();');
$this->registerJs('$("#voucher-assignment").change();');
$this->registerJs('$("#voucher-discount_type:visible").change();');
$this->registerJs('$("#voucher-validation_service:visible").change();');

$menu_categories = \common\models\User::getCollectionByModel('menu_category');
$menu_items = \common\models\User::getCollectionByModel('menu_item');
?>
<style>
    .chosen-container{
        width:100%;
    }
</style>    

<div class="voucher-form">

    <?php $form = ActiveForm::begin(); ?>

    <legend><?= Yii::t('label', 'Assignment') ?></legend>

    <?= $form->field($model, 'assignment')->radioList(\common\enums\VoucherAssignmentType::getLabels(), ['prompt' => '', 'class' => 'i-checks', 'onchange' => '
                        $("[id$=\'Select\']").hide();
                        $("[id$=\'Select\']").attr("disabled","disabled");
                        var value = $("input[type=\'radio\']:checked", ".voucher-form").val();
                        $(".voucher-form").find("#"+value+"Select").removeAttr("disabled");
                        $(".voucher-form").find("#"+value+"Select").show();
                        if (value == "User") {
                            $("#voucher-user_id").val("'. $user_selected . '");
                            $("#voucher-user_id").chosen();
                            $("#voucher-user_id").css("width", "100%");
                            $("#voucher-user_id").trigger("chosen:updated");
                        }
                    '])->label(false) ?>

    <div id="UserSelect" class="none">
        <?= $form->field($model, 'user_id')->dropDownList(\common\models\User::getCollectionByModel('user'), ['prompt' => Yii::t('label', 'Please select member'), 'class' => 'form-control chosen-select']) ?>
    </div>

    <div id="RestaurantSelect" class="none">
        <?= $form->field($model, 'restaurant_id')->dropDownList(\common\models\User::getCollectionByModel('restaurant'), ['prompt' => Yii::t('label', 'Please select restaurant')]) ?>
    </div>

    <div id="RestaurantChainSelect" class="none">
        <?= $form->field($model, 'restaurant_chain_id')->dropDownList(\common\models\User::getCollectionByModel('restaurant_chain'), ['prompt' => Yii::t('label', 'Please select restaurant chain')]) ?>
    </div>

    <div id="RestaurantGroupSelect" class="none">
        <?= $form->field($model, 'restaurant_group_id')->dropDownList(\common\models\User::getCollectionByModel('restaurant_group'), ['prompt' => Yii::t('label', 'Please select restaurant group')]) ?>
    </div>
    
    <div id="ClientSelect" class="none">
        <?= $form->field($model, 'client_id')->hiddenInput(['value' =>  Yii::$app->request->getImpersonatedClientId()])->label(false) ?>
    </div>

    <legend><?= Yii::t('label', 'Base Configuration') ?></legend>

    <div class="row">
        <div class="col-xs-3">
            <?= $form->field($model, 'code')->textInput(['maxlength' => 250]) ?>
        </div>
        <div class="col-xs-3">
            <?= $form->field($model, 'validation_service')->dropDownList(common\enums\ValidationServiceType::getLabels(), ['prompt' => Yii::t('label', 'Please select validation service'),'onchange' => '
                        var value = $(this).val();
                        if (value == "' . \common\enums\VoucherValidationService::EagleEye . '"){
                             $(".ee-min-length").show();
                             $(".ee-max-length").show();
                        } else {
                            $(".ee-min-length").hide();
                            $(".ee-max-length").hide();
                        }
                    ']) ?>
        </div>
        <div class="col-xs-3 ee-min-length">
            <?= $form->field($model, 'code_min_length')->textInput(['class' => 'form-control']) ?>
        </div>
        <div class="col-xs-3 ee-max-length">
           <?= $form->field($model, 'code_max_length')->textInput(['class' => 'form-control']) ?>
        </div>
    </div>

    <?= $form->field($model, 'description')->textInput(['maxlength' => 500]) ?>

    <div class="row">
        <div class="col-xs-3">
            <?= $form->field($model, 'start_date')->textInput(['class' => 'form-control date-jui-picker']) ?>
        </div>
        <div class="col-xs-3">
            <?= $form->field($model, 'end_date')->textInput(['class' => 'form-control date-jui-picker']) ?>
        </div>
        <div class="col-xs-3">
            <?= $form->field($model, 'promotion_type')->dropDownList(\common\enums\VoucherPromotionType::getLabels(), ['prompt' => '']) ?>
        </div>
        <div class="col-xs-3">
            <?= $form->field($model, 'max_times_per_user')->textInput(['maxlength' => 10]) ?>
        </div>
    </div>


    <legend><?= Yii::t('label', 'Type Configuration') ?></legend>

    <?= $form->field($model, 'category')->dropDownList(VoucherCategory::getLabels(), ['prompt' => Yii::t('label', 'Select promotion category'), 'onchange' => '
                        var value = $(this).val();
                        if (value == "' . VoucherCategory::Free . '") {
                            $("#Discount").hide();
                            $("#ItemQuantity").hide();
                            $("#PriceValue").hide();
                            $("#MenuItem").hide();
                            $("#MenuItem").find("#voucher-menu_item_id").prop("disabled", true);
                            $("#menu-category").hide();
                            $("#multiple-menu-category").hide();
                            $("#multiple-menu-items").hide();
                            $("#voucher-value_type option[value=\'Percent\']").removeAttr(\'disabled\');
                        }
                        if (value == "' . VoucherCategory::Delivery . '" || value == "' . VoucherCategory::Wine . '" '
                        . ' || value == "' . VoucherCategory::Food . '" || value == "' . VoucherCategory::All . '") {
                            $("#Discount").show();
                            $("#ItemQuantity").show();
                            $("#PriceValue").hide();
                            $("#MenuItem").hide();
                            $("#MenuItem").find("#voucher-menu_item_id").prop("disabled", true);
                            $("#menu-category").hide();
                            $("#multiple-menu-category").hide();
                            $("#multiple-menu-items").hide();
                            $("#voucher-value_type option[value=\'Percent\']").removeAttr(\'disabled\');
                        }
                        if (value == "' . VoucherCategory::FoodPrice . '") {
                            $("#PriceValue").show();                            
                            $("#ItemQuantity").hide();
                            $("#Discount").show();
                            $("#MenuItem").hide();
                            $("#MenuItem").find("#voucher-menu_item_id").prop("disabled", true);
                            $("#menu-category").hide();
                            $("#multiple-menu-category").hide();
                            $("#multiple-menu-items").hide();
                            $("#voucher-value_type option[value=\'Percent\']").removeAttr(\'disabled\');
                        }
                        if (value == "' . VoucherCategory::MenuItems . '") {
                            $("#PriceValue").hide();
                            $("#Discount").show();
                            $("#MenuItem").show();
                            $("#MenuItem").find("#voucher-menu_item_id").prop("disabled", false);
                            $("#ItemQuantity").show();
                            $("#menu-category").hide();
                            $("#multiple-menu-category").hide();
                            $("#multiple-menu-items").hide();
                            $("#voucher-value_type option[value=\'Percent\']").removeAttr(\'disabled\');
                        }
                        if (value == "' . VoucherCategory::FreeWithinCategory . '" ) {
                            $("#PriceValue").hide();
                            $("#Discount").hide();
                            $("#MenuItem").hide();
                            $("#MenuItem").find("#voucher-menu_item_id").prop("disabled", true);
                            $("#ItemQuantity").show();
                            $("#menu-category").show();
                            $("#multiple-menu-category").hide();
                            $("#multiple-menu-items").hide();
                            $("#voucher-value_type option[value=\'Percent\']").removeAttr(\'disabled\');
                        }
                        if (value == "' . VoucherCategory::FreeItem . '") {
                            $("#PriceValue").hide();
                            $("#Discount").hide();
                            $("#MenuItem").hide();
                            $("#MenuItem").find("#voucher-menu_item_id").prop("disabled", true);
                            $("#multiple-menu-items").removeAttr("disabled");
                            $("#ItemQuantity").show();
                            $("#menu-category").hide();
                            $("#multiple-menu-category").hide();
                            $("#multiple-menu-items").show();
                            $(".chosen-select").chosen();
                             var ids = "'. $menu_items_selected . '".split(",");
                            $(".chosen-select").val(ids);
                            $(".chosen-container").css("width", "100%");
                            $(".chosen-select").trigger("chosen:updated");
                            $("#voucher-value_type option[value=\'Percent\']").removeAttr(\'disabled\');
                        }
                        if (value == "' . VoucherCategory::OffByCategory . '" || value == "' . VoucherCategory::MultipleCategoriesSinglePrice . '") {
                            if (value == "' . VoucherCategory::MultipleCategoriesSinglePrice . '"){
                                $("#voucher-value_type option[value=\'Percent\']").attr("disabled","disabled");
                            } else {
                                $("#voucher-value_type option[value=\'Percent\']").removeAttr(\'disabled\');
                            }

                            $("#PriceValue").hide();
                            $("#Discount").show();
                            $("#MenuItem").hide();
                            $("#MenuItem").find("#voucher-menu_item_id").prop("disabled", true);
                            $("#ItemQuantity").show();
                            $("#menu-category").hide();
                            $("#multiple-menu-items").hide();
                            $("#multiple-menu-category").show();
                            $(".chosen-select").chosen();
                            var ids = "'. $menu_category_selected . '".split(",");
                            $(".chosen-select").val(ids);
                            $(".chosen-container").css("width", "100%");
                            $(".chosen-select").trigger("chosen:updated");
                        }
                        if (value == "' . VoucherCategory::MultipleItemsSinglePrice . '") {
                            $("#PriceValue").hide();
                            $("#Discount").show();
                            $("#MenuItem").hide();
                            $("#MenuItem").find("#voucher-menu_item_id").prop("disabled", true);
                            $("#ItemQuantity").show();
                            $("#menu-category").hide();
                            $("#multiple-menu-category").hide();
                            $("#multiple-menu-items").show();
                            var ids = "'. $menu_items_selected . '".split(",");
                            $(".chosen-select").val(ids);
                            $(".chosen-select").chosen();
                            $(".chosen-container").css("width", "100%");
                            $(".chosen-select").trigger("chosen:updated");
                            $("#voucher-value_type option[value=\'Percent\']").attr("disabled","disabled");
                        }
                    ']) ?>


    <div id="menu-category" class="row none">
        <div class="col-xs-6">
                <?= $form->field($model, 'source_menu_category')->dropDownList($menu_categories, ['prompt' => '']) ?>
        </div>
        <div class="col-xs-6">
                <?= $form->field($model, 'target_menu_category')->dropDownList($menu_categories, ['prompt' => '']) ?>
        </div>
    </div>
    
    <div id="multiple-menu-category" class="row none">
        <div class="col-xs-12">
                <?= $form->field($model, 'menu_category')->dropDownList($menu_categories, ['data-placeholder' => Yii::t('label', 'Please select menu category'),'multiple' => 'true','class'=> 'chosen-select', 'selected' => $menu_category_selected ,'style' => 'width:100%']) ?>
        </div>
    </div>
    
    
    <div id="multiple-menu-items" class="row none">
        <div class="col-xs-12">
                <?= $form->field($model, 'menu_item_ids')->dropDownList($menu_items, ['data-placeholder' => Yii::t('label', 'Please select menu item'),'multiple' => 'true','class'=> 'chosen-select', 'style' => 'width:100%']) ?>
        </div>
    </div>
    
    
    <div id="Discount" class="row">
        <div class="col-xs-6">
                <?= $form->field($model, 'discount_value')->textInput(['maxlength' => 10]) ?>
        </div>
        <div class="col-xs-6">
                <?= $form->field($model, 'value_type')->dropDownList(\common\enums\VoucherValueType::getLabels(), ['prompt' => '']) ?>
        </div>
    </div>
    
    <div id="PriceValue" class="none">
        <?= $form->field($model, 'price_value')->textInput(['maxlength' => 10]) ?>
    </div>

    <div id="ItemQuantity" class="none">
        <?= $form->field($model, 'item_quantity')->textInput(['maxlength' => 10]) ?>
    </div>
    
    <div id="MenuItem" class="row none">
        <div class="col-xs-4">
           <?= $form->field($model, 'discount_type')->radioList(common\enums\VoucherDiscountType::getLabels(),['class' => 'i-checks', 'onchange' => '
                        var value = $("input[type=\'radio\']:checked", "#MenuItem").val();
                        if (value == "' . \common\enums\VoucherDiscountType::Price . '"){
                             $("#PriceValue").show();
                             $("#Discount").hide();
                        } else {
                            $("#PriceValue").hide();
                            $("#Discount").show();
                        }
                    ']) ?> 
        </div>
        <div class="col-xs-8">
            <?= $form->field($model, 'menu_item_id')->dropDownList($menu_items, ['prompt' => Yii::t('label', 'Please select menu item')]) ?>
        </div>
    </div>
    

    <?php if ($model->isNewRecord): ?>

        <?= $form->field($model, 'record_type')->radioList([ 'Active' => Yii::t('label', 'Active'), 'Inactive' => Yii::t('label', 'Inactive'),], ['class' => 'i-checks', 'prompt' => Yii::t('label', 'Select ...')]) ?>

    <?php endif; ?>

    <?=
    $this->render('../common/_record_info', [
        'model' => $model,
    ])
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('label', 'Create') : Yii::t('label', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?=
        $model->isNewRecord ? '' : Html::a($model->record_type == 'Active' ? Yii::t('label', 'Deactivate') : Yii::t('label', 'Activate'), [$model->record_type == 'Active' ? 'deactivate' : 'activate', 'id' => $model->id], [
                    'class' => $model->record_type == 'Active' ? 'btn btn-danger ' : 'btn btn-success ',
                ])
        ?>
        <?=
        $model->isNewRecord ? '' : Html::a(Yii::t('label', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger right ',
                    'data' => [
                        'confirm' => Yii::t('label', 'Are you sure you want to delete?'),
                        'method' => 'post',
                    ],
                ])
        ?>    
    </div>

<?php ActiveForm::end(); ?>

</div>
