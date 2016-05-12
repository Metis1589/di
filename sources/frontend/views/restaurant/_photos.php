<?php
/* @var $this yii\web\View */
?>

<h3 class="form_devider only_desctop"><?= $model['name'] ?></h3>
<?php  if (count($model['restaurantPhotos']) > 0): ?>
    <img src="<?= Yii::$app->params['images_base_url']?>restaurant/<?=$model['restaurantPhotos'][0]['image_name']?>" width="100%">
<?php  endif; ?>
