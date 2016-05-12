<?php

use admin\assets\AngularAsset;
use common\components\language\T;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Voucher */

$this->title = Yii::t('label', 'Update Voucher') . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Vouchers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('label', 'Update');

AngularAsset::register($this, ['voucher','timepicker','timeBoth']);
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
                                    <li class="active"><a href="#tab-1" data-toggle="tab"><?= T::l('Voucher') ?></a></li>
                                    <li><a href="#tab-2" data-toggle="tab"><?= T::l('Schedule') ?></a></li>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="panel-body">
                        <div class="tab-content" ng-app="dineinApp">
                            <div class="tab-pane active" id="tab-1">
                                <?= $this->render('_form', [
                                    'model' => $model,
                                    'menu_category_selected' => $menu_category_selected,
                                    'menu_items_selected' => $menu_items_selected,
                                    'user_selected' => $user_selected
                                ]) ?>
                            </div>
                            <?php if (!$model->isNewRecord): ?>
                                <div class="tab-pane" id="tab-2">
                                    <?= $this->action('voucher-schedule/form', ['id' => $model->id]) ?>
                                </div>
                            <?php endif; ?>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>
</div>