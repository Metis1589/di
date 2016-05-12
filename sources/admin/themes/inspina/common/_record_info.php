<?php



use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $model common\models\Address */

?>

<?php if (!$model->isNewRecord): ?>

    <div class="record-info">

        <label class="control-label"><?= Yii::t('label','Created On') ?></label><br>
        <?= Yii::$app->formatter->asDatetime($model->create_on); ?><br><br>

        <label class="control-label"><?= Yii::t('label','Last Update') ?></label><br>
        <?= Yii::$app->formatter->asDatetime($model->last_update); ?><br><br>
        
    </div>

<?php endif ?>