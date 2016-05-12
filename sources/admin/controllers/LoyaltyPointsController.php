<?php

namespace admin\controllers;

use Yii;
use common\models\Voucher;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use \common\enums\UserType;
use common\components\identity\RbacHelper;
use yii\filters\AccessControl;

/**
 * VoucherController implements the CRUD actions for Voucher model.
 */
class LoyaltyPointsController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => AccessControl::className(),
                'rules' => [
                        RbacHelper::allowAllActionsForRoles([
                            UserType::Admin, UserType::ClientAdmin
                        ]),
                    ],
            ],
        ];
    }

    public function actionUpdate()
    {
        $model = $this->findModel(Yii::$app->request->getImpersonatedClientId());

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->reloadCache();
            Yii::$app->session->setFlash('success', Yii::t('label', 'Loyalty points successfully updated.'));
            return $this->render('update', [
                'model' => $model,
            ]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    
    private function reloadCache()
    {
        Yii::$app->globalCache->addUpdateCacheAction('loadClients()');
    }

    /**
     * Finds the Client model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Voucher the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = \common\models\Client::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
