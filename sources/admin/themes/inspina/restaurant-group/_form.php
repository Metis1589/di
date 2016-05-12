<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\RestaurantChain;
use common\models\Currency;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantGroup */
/* @var $form yii\widgets\ActiveForm */

$this->registerModelActionButtons($model);

?>

<div class="restaurant-group-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-xs-10">
            <?= $form->field($model, 'name_key')->textInput(['maxlength' => 190, 'disabled' => true]) ?>
        </div>
        <div class="col-xs-2"><br/>
            <?= Html::a(Yii::t('label','Translate'), Url::toRoute(['/label/update-by-code', 'code' => $model->name_key]), [
                'class' => 'btn btn-success'.($model->isNewRecord ? ' disabled' : '')
            ]) ?>
         </div>
    </div>
    <?php if (!$model->isNewRecord): ?>
        <div class="row">
            <div class="col-xs-12" style = "height:60px; ">
                <label class="control-label"> <?= Yii::t('label','Restaurant Group name')?></label><br>
                  <?= Yii::$app->globalCache->getLabel($model->name_key) ?> 
                <br/>
            </div>
        </div>
    <?php endif; ?>

    <?=  $this->render('../common/_record_type', [
        'model' => $model,
        'form' => $form
    ]) ?>

    <?=  $this->render('../common/_record_info', [
        'model' => $model,
        'form' => $form
    ]) ?>

    <?=  $this->render('../common/_record_submit_btn', [
        'model' => $model,
    ]) ?>

    <?php ActiveForm::end(); ?>

</div>
