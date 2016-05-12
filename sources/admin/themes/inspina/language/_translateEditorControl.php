<?php

use admin\common\AHtml;
use yii\helpers\Html;
use common\components\language\T;
$uniqueId = uniqid();

?>

<div ng-controller="translationeditorController" data-label-code="<?= $label_code ?>">

    <div class="col-xs-11">
        <?php $model->$property = \yii\helpers\Html::decode($model->$property); ?>
        <?= $form->field($model, $property,[
            'template' => $model->isNewRecord ? '{label}{input}{error}' : '{label}<div class="input-group">{input}<span class="input-group-btn"><a href="#edit-translation-'.$uniqueId.'" data-toggle="modal" id="translation-popup-open" class="btn btn-primary">'.T::l('translate').'</a></span></div>{error}',
        ])->widget(\mihaildev\ckeditor\CKEditor::className(),[
            'model' => $model,
            'attribute' => $property,
            'editorOptions' =>
                ['allowedContent' => true] + \mihaildev\elfinder\ElFinder::ckeditorOptions('manager', [

                ])
        ]) ?>
    </div>
    <?= Html::input('hidden','language',Yii::$app->translationLanguage->language,['id'=>'app-language']) ?>

    <?php if (!$model->isNewRecord): ?>
        <div id="edit-translation-<?= $uniqueId ?>" class="modal fade" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-12">

                                <?= AHtml::waitSpinner(['ng-show' => 'translationFormIsSubmitting']) ?>

                                <?php foreach($languages as $language): ?>
                                    <?php $translation = Yii::$app->globalCache->getLabelByLanguage($language['iso_code'], $label_code) ?>
                                    <?= AHtml::input($language['iso_code'],
                                        ['id'=>'editor-'.$language['iso_code'], 'ng-model'=> 'translations.'.$language['iso_code'], 'data-iso-code'=>$language['iso_code'], 'type'=>'editor',
                                            'value'=> $translation == $model->getLabelCodeForProperty($property) ? '' : $translation, 'data-value'=> $translation == $model->getLabelCodeForProperty($property) ? '' : $translation]
                                    ) ?>
                                <?php endforeach; ?>

                                <?= AHtml::errorNotification('{{submitError}}', ['ng-show' => 'hasSubmitError()']) ?>

                                <?= AHtml::saveButton(['ng-click' => 'save("'. $label_code .'")']) ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>