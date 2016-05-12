<?php

use common\enums\UserType;
use admin\assets\AngularAsset;
use common\components\language\T;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->isNewRecord ? Yii::t('label', 'Create User') : Yii::t('label', 'Update User') . ' ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

AngularAsset::register($this, ['user', 'equals']);
?>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">

                <div class="panel blank-panel">
                    <?php if (!$model->isNewRecord): ?>
                    <div class="panel-heading">
                        <div class="panel-options">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab-1" data-toggle="tab"><?= T::l('User') ?></a></li>
                                <?php if (!$model->isNewRecord && in_array($model->user_type, [UserType::Member, UserType::CorporateMember])): ?>
                                    <li><a href="#tab-2" data-toggle="tab"><?= T::l('User Addresses') ?></a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="panel-body">
                        <div class="tab-content" ng-app="dineinApp">
                            <div class="tab-pane active" id="tab-1">
                                <?= $this->render('_form', [
                                    'model' => $model,
                                ]) ?>
                            </div>

                            <?php if (!$model->isNewRecord && in_array($model->user_type, [UserType::Member, UserType::CorporateMember])): ?>
                                <div class="tab-pane" id="tab-2">
                                    <?= $this->render('_addresses', [
                                    'model' => $model,
                                ]) ?>
                                </div>
                            <?php endif; ?>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

