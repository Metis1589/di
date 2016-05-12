<?php

use yii\helpers\Html;
use kartik\widgets\DepDrop;
use yii\widgets\ActiveForm;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->title = Yii::t('label', 'Generate Report');
?>
<div class="report">

    <?php $form = ActiveForm::begin([]); ?>
    
    <?= $form->field($model, 'restaurant_chain_id')->dropDownList($chains, ['id'=>'restaurant_chain_id', 'prompt' => Yii::t('label', 'Please select restaurant chain')]); ?>
    
    <?= $form->field($model, 'restaurant_group_id')->widget(DepDrop::classname(), [
        'options'=>['id'=>'restaurant_group_id'],
        'pluginOptions'=>[
            'depends'=>['restaurant_chain_id'],
            'placeholder'=>'Select...',
            'url'=>Url::to(['/report/restaurant-group'])
        ]
    ]);?>
 
    <?= $form->field($model, 'restaurant_id')->widget(DepDrop::classname(), [
        'pluginOptions'=>[
            'depends'=>['restaurant_group_id'],
            'placeholder'=>'Select...',
            'url'=>Url::to(['/report/restaurant'])
        ]
    ]);?>
    
     <?= $form->field($model, 'date_from')->textInput(['class' => 'form-control date-jui-picker']) ?>

     <?= $form->field($model, 'date_to')->textInput(['class' => 'form-control date-jui-picker']) ?>
    
     <?= Html::submitButton(Yii::t('label', 'Generate'), ['class' => 'btn btn-primary right']) ?>
    
    <?php ActiveForm::end(); ?>
</div>
