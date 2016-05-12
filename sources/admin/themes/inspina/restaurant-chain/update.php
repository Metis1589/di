<?php

use admin\assets\AngularAsset;
use yii\helpers\Html;
use common\components\language\T;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantChain */

$this->title = Yii::t('label', 'Update') . ' ' . Yii::$app->globalCache->getLabel($model->name_key);
$this->title = $model->isNewRecord ? Yii::t('label', 'Create Restaurant Chain') : Yii::t('label', 'Update Restaurant Chain') . ' ' . Yii::$app->globalCache->getLabel($model->name_key);

$this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Restaurant Chains'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

AngularAsset::register($this, ['restaurant','restaurantGroup','timepicker','translation']);
?>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">

                <div class="panel blank-panel" ng-app="dineinApp">
                    <?php if (!$model->isNewRecord): ?>
                    <div class="panel-heading">
                        <div class="panel-options">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab-1" data-toggle="tab"><?= T::l('Restaurant Chain') ?></a></li>
                                <li><a href="#tab-2" data-toggle="tab"><?= T::l('Restaurant Groups') ?></a></li>
                                <li><a href="#tab-3" data-toggle="tab"><?= T::l('Services') ?></a></li>
                                <li><a href="#tab-4" data-toggle="tab"><?= T::l('Schedule') ?></a></li>
                                <li><a href="#tab-5" data-toggle="tab"><?= T::l('Properties') ?></a></li>
                                <li><a href="#tab-6" data-toggle="tab"><?= T::l('Menu Assignment') ?></a></li>
                                <li><a href="#tab-7" data-toggle="tab"><?= T::l('Exports') ?></a></li>
                            </ul>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab-1">
                                <?= $this->render('_form', [
                                    'model' => $model,
                                ]) ?>
                            </div>
                            <?php if (!$model->isNewRecord): ?>
                                <div class="tab-pane" id="tab-2">
                                     <?= $this->render('_groups', [
                                    'model' => $model,
                                ]) ?>
                                </div>
                                <div class="tab-pane" id="tab-3">
                                    <?=  $this->action('restaurant-delivery/form', ['model' => 'restaurant_chain']) ?>
                                </div>
                                <div class="tab-pane" id="tab-4">
                                    <?=  $this->action('restaurant-schedule/form', ['model' => 'restaurant_chain']) ?>
                                </div>
                                <div class="tab-pane" id="tab-5">
                                    <?=  $this->action('property-assignment/form', ['model' => $model]) ?>
                                </div>
                                <div class="tab-pane" id="tab-6">
                                    <?=  $this->action('menu-assignment/form', ['model' => $model]) ?>
                                </div>
                                <div class="tab-pane" id="tab-7">
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


