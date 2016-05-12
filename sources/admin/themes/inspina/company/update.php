<?php

use admin\assets\AngularAsset;
use common\components\language\T;

/* @var $this yii\web\View */
/* @var $model common\models\Company */

$this->title = $model->isNewRecord ? Yii::t('label', 'Create Company') : Yii::t('label', 'Update Company') . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Companies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

AngularAsset::register($this, ['company', 'timepicker', 'equals', 'timeBoth']);
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
                                <li class="active"><a href="#tab-1" data-toggle="tab"><?= T::l('Company') ?></a></li>
                                <li><a href="#tab-2" data-toggle="tab" data-users=""><?= T::l('Users') ?></a></li>
                                <li><a href="#tab-3" data-toggle="tab" data-admins=""><?= T::l('Administrators') ?></a></li>
                                <li><a href="#tab-4" data-toggle="tab"><?= T::l('Domains') ?></a></li>
                                <li><a href="#tab-5" data-toggle="tab" data-groups=""><?= T::l('Groups') ?></a></li>
                                <li><a href="#tab-6" data-toggle="tab" data-codes=""><?= T::l('Codes') ?></a></li>
                                <li><a href="#tab-7" data-toggle="tab" data-exptypes=""><?= T::l('Expense Types') ?></a></li>
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
                            <?php if (!$model->isNewRecord): ?>
                                <div class="tab-pane" id="tab-2">
                                    <?= $this->action('user/company-list', [
                                        'model'      => 'company_member',
                                        'company_id' => $model->id,
                                        'controller' => 'user'
                                    ]) ?>
                                </div>
                                <div class="tab-pane" id="tab-3">
                                    <?= $this->action('user/company-list', [
                                        'model'      => 'company_admin',
                                        'company_id' => $model->id,
                                        'controller' => 'admin',
                                    ]) ?>
                                </div>
                                <div class="tab-pane" id="tab-4">
                                    <?= $this->action('company-domain/company-domains', [
                                        'company_id' => $model->id
                                    ]) ?>
                                </div>
                                <div class="tab-pane" id="tab-5">
                                    <?= $this->action('company-user-group/company-groups', [
                                        'company_id' => $model->id
                                    ]) ?>
                                </div>
                                <div class="tab-pane" id="tab-6">
                                    <?= $this->action('company-user-group-code/company-codes', [
                                        'company_id' => $model->id
                                    ]) ?>
                                </div>
                                <div class="tab-pane" id="tab-7">
                                    <?= $this->action('expense-type/company-expense-types', [
                                        'company_id' => $model->id
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