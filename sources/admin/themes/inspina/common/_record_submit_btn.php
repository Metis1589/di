<?php
use yii\helpers\Html;
?>

<div class="row">
    <div class="col-xs-12">
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('label', 'Create') : Yii::t('label', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success col-xs-12' : 'btn btn-primary col-xs-12']) ?>
        </div>
    </div>
</div>