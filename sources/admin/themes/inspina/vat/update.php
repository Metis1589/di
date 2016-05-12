<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Vat */

$this->title = $model->isNewRecord ? Yii::t('label', 'Create VAT') : Yii::t('label', 'Update VAT') . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Vats'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>
            </div>
        </div>
    </div>
</div>

