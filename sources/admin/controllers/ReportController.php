<?php
namespace admin\controllers;

use Yii;
use common\models\RestaurantChain;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\components\identity\RbacHelper;
use common\enums\UserType;
use yii\helpers\Json;
use frontend\components\language\T;




class ReportController extends BaseController {
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    RbacHelper::allowAllActionsForRoles([
                        UserType::ClientAdmin,
                        UserType::Admin
                    ]),
                ],
            ],
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                ],
            ],
        ];
    }
    
    public function actionIndex() {
        
        $model = new \common\models\Report();
        
        if ($model->load(Yii::$app->request->post())) {
            $contact_email = Yii::$app->globalCache->getClientById(Yii::$app->request->getImpersonatedClientId())['contact_email'];
            Yii::$app->mailer->sendOne($contact_email, T::l('Report link'), 'admin/report', ['link' => $model->getReportUrl()]);
            Yii::$app->session->setFlash('success', T::l('The report link has been sent to email.'));
            return $this->redirect('index', 
            [
                'model' => new \common\models\Report(),
                'chains' => $model->getChains(Yii::$app->request->getImpersonatedClientId())  
            ]);
        }
        return $this->render('index', 
        [
            'model' => $model,
            'chains' => $model->getChains(Yii::$app->request->getImpersonatedClientId())  
        ]);
    }
    
    public function actionRestaurantGroup() {
        $out = [];
        $model = new \common\models\Report();
        
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            $restaurant_chain = $_POST['depdrop_all_params'];
            $restaurant_chain_id = $restaurant_chain['restaurant_chain_id'];
            if ($parents != null) {
                $restaurant_chain_id = $parents[0];
                $out = $model->getGroups($restaurant_chain_id);
                return Json::encode(['output' => $out, 'selected' => '']);
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }
    
    public function actionRestaurant() {
        $model = new \common\models\Report();
        
        if (isset($_POST['depdrop_parents'])) {
            $ids = $_POST['depdrop_parents'];
            $group_id = empty($ids[0]) ? null : $ids[0];
            if ($group_id != null) {
               $data = $model->getRestaurants($group_id);
               return Json::encode(['output'=>$data, 'selected'=>'']);
            }
        }
        return Json::encode(['output'=>'', 'selected'=>'']);
    }
}