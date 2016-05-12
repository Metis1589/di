<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Client;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\BestForItem */
/* @var $form yii\widgets\ActiveForm */

$this->registerModelActionButtons($model);

?>

<div class="best-for-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-xs-10">
            <?= $form->field($model, 'name_key')->textInput(['maxlength' => 50, 'disabled' => true, 'value' => $model->isNewRecord ? '' : Yii::$app->globalCache->getLabel($model->name_key)]) ?>
        </div>
        <div class="col-xs-2"><br/>
            <?= Html::a(Yii::t('label','Translate'), Url::toRoute(['/label/update-by-code', 'code' => $model->name_key]), [
                'class' => 'btn btn-success'.($model->isNewRecord ? ' disabled' : '')
            ]) ?>
         </div>
    </div>
    <div class="hr-line-dashed"></div>

    <?=  $this->render('../common/_record_type', [
        'model' => $model,
        'form' => $form
    ]) ?>

    <?=  $this->render('../common/_record_info', [
        'model' => $model,
    ]) ?>

    <?=  $this->render('../common/_record_submit_btn', [
        'model' => $model,
    ]) ?>

    <?php ActiveForm::end(); ?>

</div>
