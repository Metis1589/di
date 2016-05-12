<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\MenuItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-item-form-allergies">
    <?php $form = ActiveForm::begin(['action' => \yii\helpers\Url::toRoute(['save-menu-allergies', 'id' => $model->id])]); ?>

    <h2> <?= Yii::t('label','Please assign allergies to menu item') ?> </h2>
    <div class="ibox-content">
        <ul class="todo-list m-t ui-sortable">
            <?php
            echo Html::checkBoxList('checkBoxList', $selected_allergies, \common\models\Allergy::getAllergies(1), ['class' => 'todo-list', 'item' =>
                function ($index, $label, $name, $checked, $value) {
                    $checkbox = Html::checkbox($name, $checked, [
                                'value' => $value,
                                'label' => '<label style="display: block; width: 100%;" for="' . $label . '">' . $label . '</label>',
                                'labelOptions' => [
                                    'class' => 'ckbox ckbox-primary checkbox-inline',
                                    'style' => 'display: inline-block; width:100%'
                                ],
                                'id' => $label,
                                'class' => 'm-l-xs',
                    ]);

                    return '<li>' . $checkbox . '</li>';
                }]);
                    ?>
                </ul>
                
                <br/>
                 <?= Html::submitButton(Yii::t('label', 'Update'), ['class' => 'btn btn-primary right']) ?>
            </div>

        <?php ActiveForm::end(); ?> 
</div>
