<?php

use common\components\language\T;

?>

<div class="restaurant-group-tree">
    <ul>
        <li data-url="/restaurant/index?RestaurantSearch[restaurant_group_id]=0">
            <?= T::l('View Unassigned') ?>
        </li>
        <li data-url="/restaurant/index">
            <?= T::l('View All') ?>
            <ul>
                <?php foreach($chains as $chain): ?>
                    <li data-url="/restaurant/index?RestaurantSearch[restaurant_chain_id]=<?=$chain['id'] ?>"><?=Yii::$app->globalCache->getLabel($chain['name_key']) ?>
                        <?=  $this->render('_sub_groups', [
                            'groups' => $chain['groups']
                        ]) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </li>
    </ul>

</div>
