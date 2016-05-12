<?php

namespace admin\controllers;

use common\enums\RecordType;
use common\models\Label;
use common\models\LabelLanguage;
use Yii;
use common\models\Language;
use admin\controllers\search\LanguageSearch;
use yii\base\Exception;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\components\identity\RbacHelper;
use common\enums\UserType;
use yii\web\Response;

/**
 * LanguageController implements the CRUD actions for Language model.
 */
class LanguageController extends BaseController
{
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
            'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        RbacHelper::allowActionsForRoles(['index', 'create', 'update', 'activate', 'deactivate', 'delete'],[
                            UserType::Admin,
                        ]),
                        RbacHelper::allowActionsForRoles(['translate-control', 'save-translations'],
                            UserType::values()
                        ),
                    ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Language models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LanguageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Language model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Language();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->reloadCache();
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Language model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->reloadCache();
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Language model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->record_type = 'Deleted';
        if ($model->save())
        {
            $this->reloadCache();
            return $this->redirectToPreviousPage();
        }
        
        throw new Exception('Error saving model with record_type = Deleted');
    }
    
    /**
     * Activates an existing Language model.
     * If successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionActivate($id)
    {
        $model = $this->findModel($id);
        $model->record_type = 'Active';
        if (!$model->save())
        {
            $this->reloadCache();
            $error =  array_shift(array_values($model->getFirstErrors()));
            Yii::$app->session->setFlash('danger', $error);
            return $this->redirectToPreviousPage();
        }
        return $this->redirect(['index']);
    }
    
    /**
     * Deactivates an existing Language model.
     * If successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDeactivate($id)
    {
        $model = $this->findModel($id);
        $model->record_type = 'Inactive';
        if ($model->save())
        {
            $this->reloadCache();
            return $this->redirectToPreviousPage();
        }
        
        throw new Exception('Error saving model with record_type = Inactive');
    }

    public function actionSaveTranslations() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = json_decode(Yii::$app->request->rawBody, true);

        $label = Label::find()->where(['code' => $data['label_code']])->one();

        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach($data['translations'] as $isoCode => $translation) {
                $languageId = Yii::$app->globalCache->getLanguageId($isoCode);
                $labelLanguage = $label->findLabelLanguage($isoCode);

                if (!isset($labelLanguage) && empty($translation)) {
                    continue;
                }

                if (!isset($labelLanguage)) {
                    $labelLanguage = new LabelLanguage();
                    $labelLanguage->label_id = $label->id;
                    $labelLanguage->language_id = $languageId;
                    $labelLanguage->record_type = RecordType::Active;
                }
                if (empty($translation) && !$labelLanguage->isNewRecord) {
                    $labelLanguage->record_type = RecordType::Deleted;
                } else {
                    $labelLanguage->value = $translation;
                }

                if (!$labelLanguage->save()) {
                    throw new Exception('Can not save LabelLanguage');
                }
                Yii::$app->globalCache->deleteLabelByLanguage($label->code, $isoCode);
            }
            $transaction->commit();

        } catch (Exception $ex) {
            $transaction->rollBack();
            return ['Error' => $ex->getMessage()];
        }

        return ['Success' => true];
    }

    public function actionTranslateControl($form, $model, $property,$inputtype='text') {
        $languages = Yii::$app->globalCache->getLanguageList();
        $view = $inputtype=='editor' ? '_translateEditorControl' : '_translateControl';
        return $this->renderPartial($view, [
            'form' => $form,
            'model' => $model,
            'property' => $property,
            'label_code' => isset($model->oldAttributes[$property]) ? $model->oldAttributes[$property] : Yii::$app->translationLanguage->language,
            'languages' => $languages,
            'inputType'=> $inputtype
        ]);
    }


    private function reloadCache()
    {
        Yii::$app->globalCache->addUpdateCacheAction("loadCache()");
    }



    /**
     * Finds the Language model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Language the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Language::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
