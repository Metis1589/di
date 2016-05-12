<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Allergy */

$this->title = Yii::t('label', 'Create Allergy');
$this->params['breadcrumbs'][] = ['label' => Yii::t('label', 'Allergies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="allergy-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
