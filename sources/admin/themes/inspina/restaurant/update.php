<?php

use admin\assets\AngularAsset;
use common\components\language\T;

/* @var $this yii\web\View */
/* @var $model common\models\Restaurant */

$this->title = $model->isNewRecord ? Yii::t('label', 'Create Restaurant') : Yii::t('label', 'Update Restaurant') . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Restaurants'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

AngularAsset::register($this, ['restaurant','timepicker','translation','equals', 'timeBoth']);
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
                                <li class="active"><a href="#tab-1" data-toggle="tab"><?= T::l('Restaurant') ?></a></li>
                                <li><a href="#tab-2" data-toggle="tab"><?= T::l('Order Contacts') ?></a></li>
                                <li><a href="#tab-3" data-toggle="tab"><?= T::l('Payment Details') ?></a></li>
                                <li><a href="#tab-4" data-toggle="tab"><?= T::l('Cuisine') ?></a></li>
                                <li><a href="#tab-5" data-toggle="tab"><?= T::l('Schedule') ?></a></li>
                                <li><a href="#tab-6" data-toggle="tab"><?= T::l('Services') ?></a></li>
                                <li><a href="#tab-7" data-toggle="tab" data-users=""><?= T::l('Users') ?></a></li>
                                <li><a href="#tab-8" data-toggle="tab"><?= T::l('Properties') ?></a></li>
                                <li><a href="#tab-9" data-toggle="tab"><?= T::l('Menu Assignments') ?></a></li>
                                <li><a href="#tab-10" data-toggle="tab"><?= T::l('Custom Fields') ?></a></li>
                                <li><a href="#tab-11" data-toggle="tab"><?= T::l('Exports') ?></a></li>
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
                                    <?=  $this->action('restaurant-contact-order/list') ?>
                                </div>
                                <div class="tab-pane" id="tab-3">
                                    <?=  $this->action('restaurant-payment/form') ?>
                                </div>
                                <div class="tab-pane" id="tab-4">
                                    <?=  $this->action('restaurant/cuisine-best-for-item-form', ['restaurant_id' => $model->id]) ?>
                                </div>
                                <div class="tab-pane" id="tab-5">
                                    <?=  $this->action('restaurant-schedule/form', ['model' => 'restaurant']) ?>
                                </div>
                                <div class="tab-pane" id="tab-6">
                                    <?=  $this->action('restaurant-delivery/form', ['model' => 'restaurant']) ?>
                                </div>
                                <div class="tab-pane" id="tab-7">
                                    <?=  $this->action('user/list', ['model' => 'restaurant', 'id' => $model->id]) ?>
                                </div>
                                <div class="tab-pane" id="tab-8">
                                    <?=  $this->action('property-assignment/form', ['model' => $model]) ?>
                                </div>
                                <div class="tab-pane" id="tab-9">
                                    <?=  $this->action('menu-assignment/form', ['model' => $model]) ?>
                                </div>
                                <div class="tab-pane" id="tab-10">
                                    <?=  $this->action('custom-field/form', ['model' => $model]) ?>
                                </div>
                                <div class="tab-pane" id="tab-11">
                                    <?=  $this->action('order-export/form', ['model' => 'restaurant','selectedExport'=>\common\enums\OrderExportType::NewOrders]) ?>
                                </div>
                            <?php endif; ?>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

