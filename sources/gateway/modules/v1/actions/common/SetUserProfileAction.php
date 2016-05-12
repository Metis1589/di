<?php
namespace gateway\modules\v1\actions\common;

use gateway\modules\v1\forms\common\SetUserProfileForm;
use gateway\modules\v1\components\PostApiAction;
use Exception;
use Yii;

class SetUserProfileAction extends PostApiAction
{
	/**
	 * Creates request form used to validate request parameters.
	 *
	 * @return SetUserProfileForm
	 */
	protected function createRequestForm()
	{
		return new SetUserProfileForm();
	}

	/**
	 * Save user profile.
	 *
	 * @param SetUserProfileForm $requestForm Request form class instance.
	 *
	 * @return string|boolean
	 */
	protected function getResponseData($requestForm)
	{
		try {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $user = Yii::$app->user->identity;
                $user->first_name = $requestForm->first_name;
                $user->last_name = $requestForm->last_name;
                $user->username = $requestForm->email;
                if (!empty($requestForm->password)) {
                    $user->password = $user->generatePassword($requestForm->password);
                }
                if (!$user->save()) {
                    throw new Exception('Error saving profile');
                }

                $transaction->commit();
                return [
                    'name'  => $user->first_name,
                    'email' => $user->username,
                ];

            } catch (Exception $ex) {
                $transaction->rollBack();
                return $ex;
            }
		}
		catch (Exception $ex) {
			return $ex;
		}
	}
}
