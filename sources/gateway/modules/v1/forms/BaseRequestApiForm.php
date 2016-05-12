<?php
namespace gateway\modules\v1\forms;

use common\components\language\T;
use common\models\User;
use Yii;
use yii\base\ErrorException;

class BaseRequestApiForm extends FormModel
{
    public $client_key;
    public $api_token;

    public function rules()
    {
        return array_merge(
            [
                ['client_key', 'isClientValid'],
//                ['client_key', 'required', 'message' => T::e('Client key is missing'), 'when' => function($model) {
//                    return Yii::$app->user->isGuest;
//                }],


                //['api_token', 'required', 'message' => T::e('Api Token is missing')],
                ['api_token', 'isTokenValid']
            ],
            $this->customRules()
        );
    }

	/**
	 * Custom validation rules. Can be re-declared in the child class to extend validation rules.
	 *
	 * @return array
	 */
	protected function customRules()
	{
		return [
        ];
	}

    public function getClient() {
        return Yii::$app->globalCache->getClient($this->client_key);
    }

    public function isClientValid() {

        if ($this->client_key == null) {
            if (!Yii::$app->user->isGuest) {
                $client_id = Yii::$app->user->identity->client_id;
                $client = Yii::$app->globalCache->getClientById($client_id);

                if ($client == null) {
                    $this->addError('client_key', T::e('Invalid client key'));
                }

                $this->client_key = $client['key'];
            }
        }
        else {
            $client = Yii::$app->globalCache->getClient($this->client_key);
            if ($client == null) {
                $this->addError('client_key', T::e('Invalid client key'));
            }
        }
    }

    public function isTokenValid(){
        if ($this->api_token != null) {

            /** @var User $user */
            $user = \common\models\User::find()->where(['api_token' => $this->api_token])->one();

            if ($user == null) {
                $this->addError('api_token', T::e('Invalid api token'));
            } else {
                Yii::$app->user->enableSession = false;
                if (!Yii::$app->user->login($user)) {
                    $this->addError('api_token', T::e('Invalid api token'));
                    return;
                }

                if (isset($user->client)) {
                    $this->client_key = $user->client->key;
                }

//                if (empty($this->client_key)) {
//                    $this->addError('api_token', T::e('Invalid client key'));
//                }
            }
        }
    }
}