<div class="row border-bottom">
    <nav class="navbar navbar-static-top  " role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
            <form role="search" class="navbar-form-custom" method="post" action="search_results.html">
                <div class="form-group">
                    <input type="text" placeholder="<?= Yii::t('label','Search for something...') ?>" class="form-control" name="top-search" id="top-search">
                </div>
            </form>
        </div>
        <ul class="nav navbar-top-links navbar-right">
            <li>
                <span class="m-r-sm text-muted welcome-message"><?= Yii::t('label','Welcome to Admin') ?></span>
            </li>
            <li>
                <?= \yii\helpers\Html::a('<i class="fa fa-sign-out"></i> '.Yii::t('label','Logout'), ['/site/logout']) ?>
            </li>
        </ul>

    </nav>
</div>