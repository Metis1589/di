<?php
namespace gateway\modules\v1\actions\common;

use common\enums\RecordType;
use common\enums\UserAddressType;
use common\models\Address;
use common\models\UserAddress;
use gateway\models\SessionUser;
use gateway\modules\v1\forms\common\SaveUserAddressForm;
use gateway\modules\v1\components\PostApiAction;
use Exception;
use Yii;
use yii\base\ErrorException;

class SaveUserAddressAction extends PostApiAction
{
	/**
	 * Creates request form used to validate request parameters.
	 *
	 * @return SaveUserAddressForm
	 */
	protected function createRequestForm()
	{
		return new SaveUserAddressForm();
	}

	/**
	 * Returns candidate's gender.
	 *
	 * @param SaveUserAddressForm $requestForm Request form class instance.
	 *
	 * @return string|boolean
	 */
	protected function getResponseData($requestForm)
	{
		try {
            /** @var SessionUser $session_user */
            $session_user = Yii::$app->userCache->getUser();

            if ($requestForm->id) {
                // update address
                $address = Address::findOne(['id' => $requestForm->id, 'record_type' => RecordType::Active]); // todo add user_id condition

                if ($address == null) {
                    throw new ErrorException('Error getting address');
                }

                $address->setAttributes($requestForm->address);

                if (!$address->save()) {
                    throw new ErrorException('Error saving Address');
                }
            }
            else {
                // create new address
                $address = new Address();

                $address->setAttributes($requestForm->address);
                $address->country_id = 1; // todo hardcode

                if (!$address->save()) {
                    throw new ErrorException('Error saving Address');
                }

                $userAddress = new UserAddress();

                $userAddress->user_id = Yii::$app->user->identity->id;
                $userAddress->address_id = $address->id;
                $userAddress->address_type = UserAddressType::Delivery;

                if (!$userAddress->save()) {
                    throw new ErrorException('Error saving user address');
                }
            }

            $session_user->loadAddresses(Yii::$app->user->identity->id);

            Yii::$app->userCache->setUser($session_user);

            return $session_user->addresses;
		}
		catch (Exception $ex) {
            Yii::error($ex->__toString());
			return $ex;
		}
	}
}