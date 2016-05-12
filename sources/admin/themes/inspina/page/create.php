<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\components\language\T;

/* @var $this yii\web\View */
/* @var $model common\models\Page */
/* @var $sourceModel common\models\Page */

$this->title = T::l('Create Page');
$this->params['breadcrumbs'][] = ['label' => T::l('Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-create">
    <?= $this->render('_form', [
        'model' => $model,
        'sourceModel' => $sourceModel
    ]) ?>
</div>

<?php
$script = '
var transenabled = true;
$("#page-title").on("blur", function(e) {
    name = $(this).val();
    if(name){
        transenabled = false;
    }
});
$("#page-title").on("keypress",function() {
    var name = $(this).val();
    if(!name && $("#page-slug").val()){
        transenabled = false;
    }
});
$("#page-title").on("keyup", function(e) {
    if(transenabled){
        var name = $(this).val();
        if(!name && $("#page-slug").val()){
            transenabled = false;
        }
        if(transenabled){
            $.ajax({
               url: "'.Url::base(true).Url::toRoute('/page/slugtip').'",
               data: {title: name},
               success: function(data) {
                   $("#page-slug").val(data);
               }
            });
        }
    }
});'
;
$this->registerJs($script);
?>
