<?php

namespace admin\controllers;

use Yii;
use common\models\Label;
use common\models\Language;
use common\models\LabelLanguage;
use admin\controllers\search\LabelSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\ActiveQuery;
use \yii\base\Exception;
use yii\filters\AccessControl;
use common\components\identity\RbacHelper;
use common\enums\UserType;

/**
 * LabelController implements the CRUD actions for Label model.
 */
class LabelController extends BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        RbacHelper::allowAllActionsForRoles([
                            UserType::Admin,
                        ]),
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
     * Lists all Label models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LabelSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Label model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Label();
        
        $languages = $this->loadLanguages();
        
        if ($model->load(Yii::$app->request->post())) {
            
            $transaction = Yii::$app->db->beginTransaction();
            $saved = false;
            try {
                if (Yii::$app->request->isImpersonated()) {
                    $model->client_id = Yii::$app->request->getImpersonatedClientId();
                }

                if ($model->save() && $this->saveLabelLanguages($languages, $model->id)) {
                    $saved = true;
                    $transaction->commit();
                    $this->reloadCache($model);
                }
                else{
                    $transaction->rollBack();
                }

            } catch (Exception $e) {
                $transaction->rollBack();
            }
            
            if($saved){
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'languages' => $languages
        ]);
    }

    /**
     * Updates an existing Label model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
       if (!Yii::$app->request->isPost){
           Yii::$app->getUser()->setReturnUrl(Yii::$app->request->referrer);
       }
        
       $model = $this->findModel($id);
        
       $languages = $this->loadLanguages($id);

        if ($model->load(Yii::$app->request->post()) ) {
            
            $transaction = Yii::$app->db->beginTransaction();
            $saved = false;
            try {
                
                $languageSaved = $this->saveLabelLanguages($languages, $id);

                if (Yii::$app->request->isImpersonated()) {
                    $model->client_id = Yii::$app->request->getImpersonatedClientId();
                }

                if ($model->save() && $languageSaved){
                    $saved = true;
                } 
                
                $transaction->commit();
                $this->reloadCache($model);

            } catch (Exception $e) {
                $transaction->rollBack();
            }
            
            if($saved){
                return $this->redirect(Yii::$app->getUser()->getReturnUrl());
            }
            
        }

        return $this->render('update', [
            'model' => $model,
            'languages' => $languages
        ]);
    }

    public function actionUpdateByCode($code)
    {
       if (!Yii::$app->request->isPost){
           Yii::$app->getUser()->setReturnUrl(Yii::$app->request->referrer);
       }
        
       $model = Label::findOne(['code'=> $code]);
       
       if (is_null($model)){
           $model = new Label();
           $model->code = $code;
           $model->description = $code;
           $model->record_type = \common\enums\RecordType::Active;
           if (!$model->save()){
               throw new Exception("Error while saving label");
           }
       }
        
       $languages = $this->loadLanguages($model->id);

        if ($model->load(Yii::$app->request->post()) ) {
            
            $transaction = Yii::$app->db->beginTransaction();
            try {
                
                $languageSaved = $this->saveLabelLanguages($languages, $model->id);
                
                if ($model->save() && $languageSaved){
                    $transaction->commit();
                    $this->reloadCache($model);
                } 
                else{
                    $transaction->rollBack();
                }

            } catch (Exception $e) {
                $transaction->rollBack();
                throw new Exception("Error while saving label", $e);
            }
            
            return $this->redirect(Yii::$app->getUser()->getReturnUrl());
        }

        return $this->render('update', [
            'model' => $model,
            'languages' => $languages
        ]);
    }
    
    /**
     * Deletes an existing Label model.
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
            $this->reloadCache($model);
            return $this->redirectToPreviousPage();
        }
        
        throw new Exception('Error saving model with record_type = Deleted');
    }
    
    /**
     * Activates an existing Label model.
     * If successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionActivate($id)
    {
        $model = $this->findModel($id);
        $model->record_type = 'Active';
        if ($model->save())
        {
            $this->reloadCache($model);
            return $this->redirectToPreviousPage();
        }
        
        throw new Exception('Error saving model with record_type = Active');
    }
    
    /**
     * Deactivates an existing Label model.
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
            $this->reloadCache($model);
            return $this->redirectToPreviousPage();
        }
        
        throw new Exception('Error saving model with record_type = Inactive');
    }

    
    /**
     * save language label objects
     * @return boolean  
     */
    private function saveLabelLanguages($languages, $labelId) {

        $postLanguage = Yii::$app->request->post('LabelLanguage');
        $countLanguage = count($postLanguage);
        $count = 0;

        for ($i = 0; $i < $countLanguage; $i++) {
            
            if (count($languages[$i]->labelLanguages) > 0) {
                $labelLanguage = clone $languages[$i]->labelLanguages[0];
                $labelLanguage->setAttributes($postLanguage[$i]);
                if($labelLanguage->value){
                    $labelLanguage->label_id = $labelId;
                    $labelLanguage->language_id = $languages[$i]->id;

                    $languages[$i]->populateRelation('labelLanguages', [$labelLanguage]);

                    if ($languages[$i]->labelLanguages[0]->save()) {
                        $count++;
                    }
                }
                
            }
        }

        // return $count == $countLanguage;
        return true;
    }
    
    
    /**
     * load list of languages by label Id
     * @param integer $id
     * @return array of Language object
     */
    private function loadLanguages($id = null) {
        $languages = array();

        if (!is_null($id)) {
            $languages = Language::find()->with([
                'labelLanguages' => function (ActiveQuery $query) use (&$id) {
                    $query->andWhere('label_id =' . $id);
                }
            ])->orderBy('CASE WHEN `iso_code` = \'en\' THEN 1 ELSE 2 END, name')->where("language.record_type = 'Active'")->all();
        } else {
            $languages = Language::find()->orderBy('CASE WHEN `iso_code` = \'en\' THEN 1 ELSE 2 END, name')->where("language.record_type = 'Active'")->all();
        }

        foreach ($languages as $language) {
            if (count($language->labelLanguages) == 0 || is_null($id)) {
                $labelLanguage = new LabelLanguage();
                $language->populateRelation('labelLanguages', [$labelLanguage]);
            }
        }

        return $languages;
    }

    private function reloadCache($model) {
        $languages = Yii::$app->globalCache->getLanguageList();
        if($languages){
            foreach($languages as $lang){
                Yii::$app->globalCache->invalidateLabel($lang['iso_code'], $model->code);
            }
        }

        if (isset($model->client_id)) {
            Yii::$app->globalCache->addUpdateCacheAction("loadLabels('".$model->client->key."')");
        }
    }
    
    /**
     * Finds the Label model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Label the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Label::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
