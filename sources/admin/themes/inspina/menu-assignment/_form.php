<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\language\T;

/* @var $this yii\web\View */
/* @var $model common\models\MenuAssignment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-assignment-form">

    <?php $form = ActiveForm::begin(['action' => \yii\helpers\Url::toRoute(['menu-assignment/update', 'id' => $id])]); ?>

    <?php echo Html::hiddenInput('modelClass', $model);?>
         <table class="table table-bordered" >
            <thead>
                <tr>
                    <th><?= T::l('Menu') ?></th>
                    <th><?= T::l('Assignments') ?></th>
                </tr>
            </thead>
            <tbody>
 
            <?php foreach ($assignments as $i => $assignment) {
                echo '<tr>';
                echo '<td><label>' . Html::encode($assignment->name_key) . '</label></td>';
                echo '<td>' . $form->field($assignment, "[$assignment->id]record_type")->radioList([ 'Active' => Yii::t('label','Active'), 'Inactive' => Yii::t('label','Inactive'), 'Deleted' => Yii::t('label','Taken from Parent')], ['class' => 'i-checks'])->label(false);'</td>';
                echo '</tr>';
            } ?>
       
         </tbody>
         </table>
         <?= Html::submitButton(Yii::t('label', 'Update'), ['class' => 'btn btn-primary col-xs-12']) ?>
         <?php ActiveForm::end(); ?>                        
</div>
