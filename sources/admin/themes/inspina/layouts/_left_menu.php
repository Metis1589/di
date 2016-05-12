<?php
use common\components\language\T;
use common\enums\UserType;
use common\models\Client;
use yii\helpers\Html;
?>
<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <?= \yii\helpers\Html::a('<i class="fa fa-sign-out"></i> '.Yii::t('label','Logout'), ['/site/logout']) ?>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="clear">
                            <span class="block m-t-xs">
                                <strong class="font-bold"><?=Yii::$app->user->identity->username ?></strong>
                             </span>
                            <?php if (in_array(Yii::$app->user->identity->user_type, [UserType::Admin])): ?>
                                <span class="text-muted text-xs block"><?= Yii::$app->request->isImpersonated() ? T::l('Client').': '. Yii::$app->request->getImpersonatedClientName() : T::l('Impersonate Client') ?><b class="caret"></b></span>
                            <?php endif; ?>
                        </span>
                    </a>
                    <?php if (in_array(Yii::$app->user->identity->user_type, [UserType::Admin])): ?>
                        <ul class="dropdown-menu animated fadeInRight m-t-xs">
                            <?php $impersonateClients = Client::getClientsForSelect() ?>
                            <!--                        <li class="divider"></li>-->

                            <?php if (Yii::$app->request->isImpersonated()): ?>
                                <li><?= Html::a(T::l('Clear'), ['/client/impersonate-clear']) ?></li>
                            <?php else: ?>
                                <?php foreach($impersonateClients as $id => $name): ?>
                                    <li><?= Html::a($name, ['/client/impersonate', 'id' => $id]) ?></li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    <?php endif; ?>
                </div>
                <div class="logo-element">
                    DineIn
                </div>
            </li>
            <?php if(Yii::$app->request->isImpersonated()): ?>
                <?= $this->render('_left_nav_impersonated') ?>
            <?php else: ?>
                <?= $this->render('_left_nav_admin') ?>
            <?php endif; ?>

            
        </ul>

    </div>
</nav>