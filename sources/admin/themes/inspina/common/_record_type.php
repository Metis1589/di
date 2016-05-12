<?php


?>
<?php  if ($model->isNewRecord): ?>
    <div class="row">
        <div class="col-xs-12">
            <?= $form->field($model, 'record_type')->radioList([ 'Active' => Yii::t('label','Active'), 'Inactive' => Yii::t('label','Inactive'), ], ['class' => 'i-checks']) ?>
        </div>
    </div>
<?php  endif; ?>