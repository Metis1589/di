<?php
use frontend\components\language\T;

$this->title = T::l('Site Map');
?>

<section class="sitemap items_form">
    <h1 class="only_desctop"><?= T::l('SITE MAP') ?></h1>
    <h5 class="only_mobile"><?= T::l('SITE MAP') ?></h5>
    <div class="wrapper">
        <div sclass="container">
            <div class="cuisines-container">
                <?php foreach ($cuisines as $cuisine): ?>
                <?php if (count($cuisine['restaurants']) > 0): ?>
                    <div class="sitemap-block">
                        <span class="cuisine-name">
                            <a href="<?= $cuisine['id'] ?>/restaurants/cuisine/<?= $cuisine['seo_name'] ?>.html">
                                <?= $cuisine['name'] ?>
                            </a>
                        </span><br><br>
                        <?php foreach ($cuisine['restaurants'] as $restaurant): ?>
                            <a href="<?= $restaurant['seo_url'] ?>">
                                <?= $restaurant['name'] ?>
                            </a>
                            <br>
                        <?php endforeach ?>
                    </div>
                <?php endif ?>
                <?php endforeach ?>
            </div>

            <div class="clearfix"></div>

            <div class="pages-container">
                <?php foreach ($seo_areas as $area): ?>
                <?php if (count($area['restaurants']) > 0): ?>
                    <div class="sitemap-block">
                        <span class="cuisine-name">

                            <a href="<?= $area['id'] ?>/london_restaurant_delivery_in/<?= $area['seo_name'] ?>.html">
                                <?= $area['name'] ?>
                            </a>
                        </span><br><br>
                        <?php foreach ($area['restaurants'] as $restaurant): ?>
                            <a href="<?= $restaurant['seo_url'] ?>">
                                <?= $restaurant['name'] ?>
                            </a>
                            <br>
                        <?php endforeach ?>
                    </div>
                <?php endif ?>
                <?php endforeach ?>
            </div>

            <div class="clearfix"></div>

            <!-- pages -->

            <div class="pages-container">
                <span class="section-title"><?= T::l('SITE LINKS') ?></span><br/><br/>
                <?php foreach ($pages as $page): ?>
                    <a href="/<?= $page['slug'] ?>">
                        <?= $page['title'] ?>
                    </a>
                    <br>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</section>