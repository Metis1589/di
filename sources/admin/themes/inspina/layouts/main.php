<?php
use admin\assets\ThemeAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

ThemeAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>

    <div id="wrapper">
        <?= Yii::$app->user->isGuest ? '' : $this->render('_left_menu') ?>

        <div id="page-wrapper" class="gray-bg">
            <!--<?= $this->render('_top_menu') ?>-->
            <?php
                foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
                    echo '<div class="alert alert-dismissible alert-' . $key . '">'
                        . '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . $message . '</div>';
                }
            ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-8">
                    <h2><?= $this->title ?></h2>
                    <?= Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        'activeItemTemplate' => '<li class="active"><strong>{link}</strong></li>'
                    ]) ?>
                </div>
                <div class="col-sm-4">
                    <div class="title-action">
                        <?= $this->renderActionButtons() ?>
                    </div>
                </div>
            </div>
            <div class="wrapper wrapper-content animated fadeInRight">
                <?= $content ?>
            </div>
            <div class="footer">
                <div>
                    <strong><?= Yii::t('label','Copyright') ?></strong><?= Yii::t('label','DineIn Company') ?> &copy; 2014-2015
                </div>
            </div>

        </div>
    </div>

    <?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>
