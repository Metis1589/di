<?php
namespace gateway\modules\v1\actions\common;

use common\components\language\T;
use common\enums\RecordType;
use common\enums\UserAddressType;
use common\enums\UserType;
use common\models\Address;
use common\models\CompanyDomain;
use common\models\Country;
use common\models\User;
use common\models\UserAddress;
use gateway\modules\v1\forms\common\RegisterForm;
use gateway\modules\v1\components\PostApiAction;
use Exception;
use Yii;

class RegisterAction extends PostApiAction
{
    /**
     * Creates request form used to validate request parameters.
     *
     * @return RegisterForm
     */
    protected function createRequestForm()
    {
        return new RegisterForm();
    }

    /**
     * Register user.
     *
     * @param RegisterForm $requestForm Request form class instance.
     *
     * @return string|boolean
     */
    protected function getResponseData($requestForm)
    {
        try {
            $transaction = Yii::$app->db->beginTransaction();

            try {
                $address = new Address();

                $address->name       = T::l('Default');
                $address->title      = $requestForm->title;
                $address->first_name = $requestForm->first_name;
                $address->last_name  = $requestForm->last_name;
                $address->address1   = $requestForm->address1;
                $address->address2   = $requestForm->address2;
                $address->city       = $requestForm->city;
                $address->postcode   = $requestForm->postcode;
                $address->phone      = $requestForm->phone;
                $address->email      = $requestForm->username;
                $address->country_id = Country::getDefault()->id;

                if (!$address->save()) {
                    throw new Exception('Error saving address');
                }

                $user = new User();
                $user->username    = $requestForm->username;
                $user->password    = $user->generatePassword($requestForm->password);
                $user->client_id   = $requestForm->getClient()['id'];
                $company = $this->getCompany($user->username, $user->client_id);
                if (isset($company)) {
                    $user->company_id  = $company->id;
                    $user->company_user_group_id = $company->companyDefaultExternalUserGroup->id;
                }

                $user->user_type   = isset($company) ? UserType::CorporateMember : UserType::Member;
                $user->title       = $requestForm->title;
                $user->first_name  = $requestForm->first_name;
                $user->last_name   = $requestForm->last_name;
                $user->record_type = RecordType::InActive;

                if (!$user->save()) {
                    throw new Exception('Error saving user');
                }

                $userAddress = new UserAddress();
                $userAddress->user_id = $user->id;
                $userAddress->address_id = $address->id;
                $userAddress->address_type = UserAddressType::Primary;

                if (!$userAddress->save()) {
                    throw new Exception('Error saving user address');
                }

                $user->generateHash();

                $transaction->commit();

                return true;

            } catch (Exception $e) {
                Yii::error($e->__toString());
                $transaction->rollBack();
                return false;
            }

        } catch (Exception $ex) {
            Yii::error($ex->__toString());
            return $ex;
        }
    }

    private function getCompany($email, $client_id) {
        $domain = strtolower(substr(strrchr($email, "@"), 1));
        $company_domain = CompanyDomain::find()->joinWith(['company','company.client'])->where([
            'company_domain.record_type' => RecordType::Active,
            'company.record_type' => RecordType::Active,
            'client.record_type' => RecordType::Active,
            'client_id' => $client_id,
        ])->andWhere("LOWER(domain) = '$domain'")->one();
        if (isset($company_domain)) {
            return $company_domain->company;
        }
        return null;
    }
}