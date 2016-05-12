<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model gromver\cmf\common\models\Page */

$this->title = Yii::t('label','Update page');
$this->params['breadcrumbs'][] = ['label' => Yii::t('label','Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
<?php
/*
$script = '
$("#page-language_id").change(function(){
    window.location = "'.(Yii::$app->getLanguage('iso')=='en'?'':'/'.Yii::$app->getLanguage('iso')).Url::toRoute('/page/update',['id'=>$model['id']]).'?id='.$model['id'].'&language_id="+$(this).val();
})'
;
$this->registerJs($script);
 */
?>