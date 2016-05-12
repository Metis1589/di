<?php
use frontend\components\language\T;

$languageIsoCode = substr(Yii::$app->language, 0, 2);
?>

<section class="ingredients items_form">
    <h1 class="only_desctop"><?= T::l('ALLERGEN & LIFESTYLE GUIDE') ?></h1>
    <div class="wrapper">
        <h3 class="only_mobile form_devider"><?= T::l('ALLERGY & LIFESTYLE KEY') ?></h3>
        <div class="allergens">
            <h5 class="only_desctop"><?= T::l('ALLERGENS') ?></h5>
            <ul>
                <?php if (isset($allergies) && sizeof($allergies)): ?>
                    <?php foreach ($allergies as $allergy): ?>
                    <li>
                        <img src="<?= Yii::$app->params['images_base_url'] ?>allergy/<?= $allergy['image_file_name']; ?>" alt="<?= $allergy['symbol_key'] ?>">
                        <h6 ng-click="allergy<?=$allergy['id'] ?>DescriptionVisible = !allergy<?=$allergy['id'] ?>DescriptionVisible"
                            ng-mouseover="allergy<?=$allergy['id'] ?>DescriptionVisible = true"
                            ng-mouseleave="allergy<?=$allergy['id'] ?>DescriptionVisible = false"
                            >
                            <?= $allergy['name'][$languageIsoCode] ?>
                            <span class="only_desctop"> (<?= $allergy['symbol_key'] ?>)</span>
                        </h6>
                        <p ng-show="allergy<?=$allergy['id'] ?>DescriptionVisible"><?= $allergy['description'][$languageIsoCode] ?></p>
                    </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
        <div class="lifestyles">
            <h5 class="only_desctop"><?= T::l('LIFESTYLES') ?></h5>
            <ul>
            </ul>
        </div>
    </div>
</section>