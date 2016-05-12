<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = $baseUrl.Yii::$app->urlManager->createUrl(['site/reset-password', 'token' => $user->reset_hash]);
?>

<?= Yii::t('label', 'Member hello') ?> <?= Html::encode($user->name) ?>,

<?= Yii::t('label', 'Member follow the link below to reset your password:') ?>

<?= Html::a(Html::encode($resetLink), $resetLink) ?>