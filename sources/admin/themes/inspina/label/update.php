<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Label */

$this->title = $model->isNewRecord ?  Yii::t('label', 'Create Label') : Yii::t('label', 'Update Label') . ' ' . $model->code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Labels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <?= $this->render('_form', [
                    'model' => $model,
                    'languages' => $languages
                ]) ?>
            </div>
        </div>
    </div>
</div>
