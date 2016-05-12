<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CompanyUserGroupUser */

$this->title = $model->isNewRecord ?  Yii::t('label', 'Assign User') : Yii::t('label', 'Assign User') . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'User Group'), 'url' => ['/company-user-group/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Company User Group Users'), 'url' => ['index']];
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
