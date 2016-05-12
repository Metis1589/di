<?php
if ($page['robots']) {
    $this->registerMetaTag(['name' => 'robots', 'content' => $page['robots']], 'robots');
}
if ($page['description']) {
    $this->registerMetaTag(['name' => 'description', 'content' => $page['description']], 'description');
}
$this->title = $page['title'];
if ($page['slug']) {
    $this->params['breadcrumbs'][] = $page['title'];
}
?>

<section class="custom-page items_form <?=$page['slug']?>">
    <h1 class="only_desctop"><?= mb_strtoupper($page['title']) ?></h1>
    <h2 class="only_mobile"><?= mb_strtoupper($page['title']) ?></h2>
    <div class="wrapper">
        <?= $page['content'] ?>
    </div>
</section>