<?php

use common\components\language\T;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MenuItem */

$this->title = Yii::t('label', 'Update Menu Item') . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Menu Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('label', 'Update');

\admin\assets\AngularAsset::register($this, ['menuOptions', 'translation']);
?>
<div class="row"  ng-app="dineinApp">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">

                <div class="panel blank-panel">
                    <?php if (!$model->isNewRecord): ?>
                    <div class="panel-heading">
                        <div class="panel-options">
                            <ul class="nav nav-tabs">
                                <li class="<?= ($tab == 'menu_item') ? 'active' : '' ?>"><a href="#tab-1" data-toggle="tab"><?= T::l('Menu Item') ?></a></li>
                                <li class="<?= ($tab == 'menu_options') ? 'active' : '' ?>"><a href="#tab-2" data-toggle="tab"><?= T::l('Menu Options') ?></a></li>
                                <li class="<?= ($tab == 'allergies') ? 'active' : '' ?>"><a href="#tab-3" data-toggle="tab"><?= T::l('Allergies') ?></a></li>
                                <li class="<?= ($tab == 'allergies') ? 'active' : '' ?>"><a href="#tab-4" data-toggle="tab"><?= T::l('Custom Fields') ?></a></li>
                            </ul>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane <?= ($tab == 'menu_item') ? 'active' : '' ?>" id="tab-1">
                                <?= $this->render('_form', [
                                    'model' => $model,
                                ]) ?>
                            </div>
                            <?php if (!$model->isNewRecord): ?>
                                <div class="tab-pane <?= ($tab == 'menu_options') ? 'active' : '' ?>" id="tab-2">
                                    <?= $this->render('_options', [
                                    'model' => $model,
                                ]) ?>
                                </div>
                            
                                <div class="tab-pane <?= ($tab == 'allergies') ? 'active' : '' ?>" id="tab-3">
                                    <?= $this->render('_allergies', [
                                    'selected_allergies' => $selected_allergies,
                                    'model' => $model
                                ]) ?>
                                </div>
                                <div class="tab-pane" id="tab-4">
                                    <?=  $this->action('custom-field/form', ['model' => $model]) ?>
                                </div>
                            <?php endif; ?>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

