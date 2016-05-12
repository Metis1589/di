<?php

use common\models\RestaurantChain;

?>

<ul>
    <?php foreach($groups as $group): ?>

        <li data-url="/restaurant/index?RestaurantSearch[restaurant_group_id]=<?=$group['id'] ?>"><?=Yii::$app->globalCache->getLabel($group['name_key']) ?>
            <?=  $this->render('_sub_groups', [
                'groups' => $group['groups']
            ]) ?>
        </li>

    <?php endforeach; ?>
</ul>
