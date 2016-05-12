<?php
use common\enums\UserType;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$url = in_array($user->user_type, [UserType::Member, UserType::CorporateMember]) ? $user->client->url : Yii::$app->params['baseUrl'];

$activateLink = $url.Yii::$app->urlManager->createUrl(['site/activate', 'token' => $user->activation_hash]);
?>

<?= Yii::t('label', 'Member hello') ?> <?= Html::encode($user->first_name) . ' ' . Html::encode($user->last_name)?>,

<?= Yii::t('label', 'Member follow the link below to activate your account:') ?>

<?= Html::a(Html::encode($activateLink), $activateLink) ?>
