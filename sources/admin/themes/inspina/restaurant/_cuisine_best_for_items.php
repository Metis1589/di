<?php

use admin\common\AHtml;
use common\components\language\T;

?>

<div class="cuisine-best-for-form" ng-controller="cuisineBestForItemController">
    <form>
        <?= AHtml::waitSpinner(['ng-show' => 'isSubmitting']) ?>
        <div class="row">
            <div class="col-xs-6 col-xs-offset-6">
                <h3><?=T::l('Cuisine') ?></h3>
                <?php foreach($cuisines as $cuisine): ?>
                    <div class="row">
                        <input type="checkbox" id="cuisine_<?= $cuisine->id ?>" ng-click="assingCuisine('<?= $cuisine->id ?>')" <?= in_array($cuisine->id, $assignedCuisines) ? 'checked="checked"' : '' ?> >
                        <label for="cuisine_<?= $cuisine->id ?>"><?= Yii::$app->globalCache->getLabel($cuisine->name_key) ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </form>
</div>
