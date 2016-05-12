<?php

namespace common\models;

use common\enums\EmailType;
use common\enums\UserAddressType;
use gateway\modules\v1\services\EmailService;
use Yii;
use common\enums\RecordType;
use common\enums\UserType;
use \common\components\language\T;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

class User extends UserBase implements IdentityInterface
{
    public $group_name;
    public $user_title;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'user_type', 'first_name', 'last_name'], 'required'],
            ['username',  'common\validators\CustomUniqueValidator', 'message' => T::e('username is already in use')],
            ['username',  'email'],
            ['is_corporate_approved', 'boolean'],
            ['user_type', 'string'],
            [['last_visit', 'dob', 'create_on', 'last_update'], 'safe'],
            [['client_id', 'company_id', 'restaurant_chain_id', 'restaurant_group_id', 'restaurant_id', 'term_and_cond', 'term_and_cond_web', 'term_and_cond_acc_pol', 'affiliate_id'], 'integer'],
            [['username', 'password', 'activation_hash', 'photo', 'know_about', 'reset_password_hash'], 'string', 'max' => 255],
            ['api_token', 'string', 'max' => 255],
            [['term_and_cond', 'term_and_cond_web', 'term_and_cond_acc_pol'], 'default', 'value' => 0],
            ['restaurant_id', 'required', 'when' => function($model) {
                return in_array($model->user_type, [UserType::RestaurantAdmin, UserType::RestaurantTeam, UserType::RestaurantApp]);
            }, 'whenClient' => 'function (attribute, value) { return ($("#user-user_type").val() == "'. UserType::RestaurantAdmin.'" || $("#user-user_type").val() == "'. UserType::RestaurantTeam.'" || $("#user-user_type").val() == "'. UserType::RestaurantApp.'"); }'],
            ['client_id', 'required', 'when' => function($model) {
                return in_array($model->user_type, [
                    UserType::Member,
                    UserType::CorporateMember,
                    UserType::RestaurantAdmin,
                    UserType::RestaurantGroupAdmin,
                    UserType::RestaurantChainAdmin,
                    UserType::CorporateAdmin,
                    UserType::RestaurantTeam,
                    UserType::RestaurantApp,
                    UserType::ClientAdmin,
                    UserType::InnTouch
                ]);
            }, 'whenClient' => 'function (attribute, value) { return ($("#user-user_type").val() == "'. UserType::ClientAdmin.'"); }'],
            ['client_id', 'required', 'when' => function($model) {
                return in_array($model->user_type, [UserType::ClientAdmin, UserType::Member, UserType::CorporateMember]);
            }, 'whenClient' => 'function (attribute, value) { return ($("#user-user_type").val() == "'. UserType::ClientAdmin. '" || $("#user-user_type").val() == "'. UserType::Member. '" || $("#user-user_type").val() == "'. UserType::CorporateMember.'" || $("#user-user_type").val() == "'. UserType::InnTouch.'"); }'],
            ['company_id', 'required', 'when' => function($model) {
                return in_array($model->user_type, [UserType::CorporateAdmin, UserType::CorporateMember]);
            }, 'whenClient' => 'function (attribute, value) { return ($("#user-user_type").val() == "'. UserType::CorporateAdmin.'" || $("#user-user_type").val() == "'. UserType::CorporateMember. '"); }'],
            ['company_user_group_id', 'required', 'when' => function($model) {
                return in_array($model->user_type, [UserType::CorporateMember]) && !$model->id;
            }],
            ['restaurant_chain_id', 'required', 'when' => function($model) {
                return in_array($model->user_type, [UserType::RestaurantChainAdmin]);
            }, 'whenClient' => 'function (attribute, value) { return ($("#user-user_type").val() == "'. UserType::RestaurantChainAdmin.'"); }'],
            ['restaurant_group_id', 'required', 'when' => function($model) {
                return in_array($model->user_type, [UserType::RestaurantGroupAdmin]);
            }, 'whenClient' => 'function (attribute, value) { return ($("#user-user_type").val() == "'. UserType::RestaurantGroupAdmin.'"); }'],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyUserGroup()
    {
        return $this
            ->hasOne(CompanyUserGroup::className(), ['id' => 'company_user_group_id'])
            ->andOnCondition(['company_user_group.record_type' => RecordType::Active]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrimaryAddress()
    {
        return $this
            ->hasOne(Address::className(), ['id' => 'address_id'])
            ->viaTable('user_address', ['user_id' => 'id'], function($query) {
                $query->onCondition([
                    'address_type'             => UserAddressType::Primary,
                    'user_address.record_type' => RecordType::Active
                ]);
            });
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                    => T::l('ID'),
            'username'              => T::l('Username'),
            'password'              => T::l('Password'),
            'user_type'             => T::l('User Type'),
            'last_visit'            => T::l('Last Visit'),
            'activation_hash'       => T::l('Activation Hash'),
            'photo'                 => T::l('Photo'),
            'dob'                   => T::l('Dob'),
            'know_about'            => T::l('Know About'),
            'term_and_cond'         => T::l('Term And Cond'),
            'term_and_cond_web'     => T::l('Term And Cond Web'),
            'term_and_cond_acc_pol' => T::l('Term And Cond Acc Pol'),
            'api_token'             => T::l('Api Token'),
            'reset_password_hash'   => T::l('Reset Password Hash'),
            'affiliate_id'          => T::l('Affiliate ID'),
            'record_type'           => T::l('Record Type'),
            'create_on'             => T::l('Create On'),
            'last_update'           => T::l('Last Update'),
        ];
    }

    public static function getUsersForSelect()
    {
        return ArrayHelper::map(self::find()->where("record_type <> '".RecordType::Deleted."'")->all(), 'id', 'username');
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);
        if ($this->scenario == 'requestToActivate') {
            $url          = in_array($this->user_type, [ UserType::Member, UserType::CorporateMember ]) ? $this->client->url : Yii::$app->params['baseUrl'];
            $activateLink = $url . Yii::$app->urlManager->createUrl([ 'site/activate', 'token' => $this->activation_hash ]);

            // Send email
            if (in_array($this->user_type,[UserType::Member, UserType::CorporateMember])) {
                EmailService::sendToCustomer($this->client, $this, $activateLink);
            } else {
                EmailService::sendToCustomer(null, $this, $activateLink, EmailTemplate::createDefaultEmailTemplate(EmailType::NewUserRegistration));
            }

            if (in_array($this->user_type,[UserType::Member, UserType::CorporateMember])) {
                Yii::$app->mailchimp->addUserToCityList($this->client->key, $this->username, isset($this->primaryAddress) ? $this->primaryAddress->city : null);
            }
        } else if ($this->scenario == 'requestToResetPassword') {
            $url       = in_array($this->user_type, [ UserType::Member, UserType::CorporateMember ]) ? $this->client->url : Yii::$app->params['baseUrl'];
            $resetLink = $url . Yii::$app->urlManager->createUrl([ 'site/reset-password', 'token' => $this->reset_password_hash ]);

            // Send email
            if (in_array($this->user_type,[UserType::Member, UserType::CorporateMember])) {
                EmailService::sendForgotPassword($this->client, $this, $resetLink);
            } else {
                EmailService::sendForgotPassword(null, $this, $resetLink, EmailTemplate::createDefaultEmailTemplate(EmailType::ForgotPassword));
            }
        }
    }

    public function beforeSave($insert) {
        if ($this['dob']) {
            $this['dob'] = Yii::$app->formatter->asDate($this->dob, 'php:Y-m-d');
        }

        if ($this->isNewRecord) {
            $this->api_token = md5(uniqid($this->username, true));
        }

        return parent::beforeSave($insert);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username) {
        return static::findOne(['username' => $username, 'record_type' => RecordType::Active]);
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password) {
        return password_verify($password, $this->password) || ($password == md5($this->password));
    }

    /**
     * Finds an identity by the given ID.
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id) {
        return static::findOne(['id' => $id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException(Yii::t('error', '"findIdentityByAccessToken" is not implemented.'));
    }

    public static function findIdentityByActivationHash($activation_hash) {
        return static::findOne(['activation_hash' => $activation_hash, 'record_type' => RecordType::InActive]);
    }

    public static function findByPasswordResetHash($hash) {
        return static::findOne(['reset_password_hash' => $hash, 'record_type' => RecordType::Active]);
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|integer an ID that uniquely identifies a user identity.
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey() {
        return 1;
        //return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return boolean whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return true;
        //return $this->getAuthKey() === $authKey;
    }

    public function load($data, $formName = null)
    {
        $result = parent::load($data, $formName);
        $scope = $formName === null ? $this->formName() : $formName;
        if (isset($data[$scope]['enteredPassword'])) {
            $this->password = $this->generatePassword($data[$scope]['enteredPassword']);
        }
        if (isset($data[$scope]['gender']) && empty($data[$scope]['gender'])) {
            $this->gender = null;
        }
        return $result;
    }

    public function generatePassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function activate()
    {
        $this->activation_hash = null;
        $this->reset_password_hash = null;
        $this->last_update     = date('Y-m-d H:i:s');
        $this->record_type     = RecordType::Active;
        return $this->save(false,['activation_hash', 'reset_password_hash', 'record_type', 'last_update', 'password']);
    }

    public function requestToResetPassword()
    {
        $this->scenario   = 'requestToResetPassword';
        $this->reset_password_hash = $this->generatePassword($this->username.'reset'.date('Y-m-d H:i:s'));
        return $this->save(false,['reset_password_hash']);
    }


    /**
     *
     * Generates hashes for
     */
    public function generateHash()
    {
        $this->scenario        = 'requestToActivate';
        $this->activation_hash = $this->generatePassword($this->username . 'activate' . date('Y-m-d H:i:s'));
        $this->save(false,['activation_hash']);
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        if (method_exists(Yii::$app->request, 'isImpersonated') && Yii::$app->request->isImpersonated()) {
            $this->client_id = Yii::$app->request->getImpersonatedClientId();
        }
        return parent::save($runValidation, $attributeNames);
    }


    public static function getOwnersForSelect()
    {
        return ArrayHelper::map(self::findAll(['user_type' => 'RestaurantOwner','record_type' => RecordType::Active]), 'id', 'username');
    }

    public static function getGroupAdminForSelect()
    {
        return ArrayHelper::map(self::findAll(['user_type' => 'RestaurantGroupAdmin','record_type' => RecordType::Active]), 'id', 'username');
    }

    public static function saveByPost($postedUser)
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $existedUser = static::findOne($postedUser['id']);
            $isNew       = false;
            $isSaved     = true;

            if ($existedUser == null) {
                $existedUser = new User();
                $existedUser->record_type = RecordType::InActive;
                unset($postedUser['id']);
                $isNew = true;
            }
            $existedUser->load($postedUser, '');
            $existedUser->record_type = $isNew ? RecordType::InActive : $postedUser['record_type'];
            $existedUser->title       = $postedUser['user_title'];
            $existedUser->is_corporate_approved = $postedUser['is_corporate_approved'] ? 1 : 0;

            if ($postedUser['user_type'] === \common\enums\UserType::CorporateMember && isset($postedUser['company_user_group_id'])) {
                $existedUser->company_user_group_id = $postedUser['company_user_group_id'];
            }

            if ($postedUser['user_type'] === \common\enums\UserType::CorporateAdmin) {
                $existedUser->is_corporate_approved = 1;
            }

            if ($postedUser['user_type'] === \common\enums\UserType::CorporateMember && !$postedUser['is_corporate_approved']) {
                $group = CompanyUserGroup::find()
                    ->where("record_type <> '".RecordType::Deleted."'")
                    ->andWhere("name = '".\common\enums\DefaultCompanyGroup::DefaultExternal."'")
                    ->andWhere("company_id = {$postedUser['company_id']}")
                    ->one();
                $existedUser->company_user_group_id = $group->id;
            }

            if (!empty($postedUser['new_password'])) {
                $existedUser->password = $existedUser->generatePassword($postedUser['new_password']);
            }

            if ($existedUser->company_id) {
                $existedUser->client_id = $existedUser->company->client_id;
            }

            $isSaved = $existedUser->save();

            if ($isSaved && $postedUser['user_type'] === \common\enums\UserType::CorporateMember && $existedUser['record_type'] != RecordType::Deleted) {
                $userAddress = UserAddress::find()
                    ->where("user_id = {$existedUser->id}")
                    ->andWhere("address_type = '" . \common\enums\AddressType::Primary . "'")
                    ->andWhere("record_type <> '" . RecordType::Deleted . "'")
                    ->one();

                if (!$userAddress) {
                    $address = new Address;
                } else {
                    $address = Address::find()->where("id = {$userAddress->address_id}")->one();
                }

                $address->address1   = $postedUser['primaryAddress']['address1'];
                $address->address2   = $postedUser['primaryAddress']['address2'];
                $address->city       = $postedUser['primaryAddress']['city'];
                $address->postcode   = $postedUser['primaryAddress']['postcode'];
                $address->phone      = $postedUser['primaryAddress']['phone'];
                $address->country_id = $postedUser['primaryAddress']['country_id'];
                $isSaved             = $isSaved && $address->save();

                if (!$userAddress) {
                    $userAddress               = new UserAddress;
                    $userAddress->user_id      = $existedUser->id;
                    $userAddress->address_id   = $address->id;
                    $userAddress->address_type = \common\enums\AddressType::Primary;
                    $userAddress->record_type  = RecordType::Active;
                    $isSaved                   = $isSaved && $userAddress->save();
                }
            }

            if ($isSaved && $isNew) {
                $existedUser->generateHash();
            }

            if ($isSaved) {
                $transaction->commit();
            } else {
                $transaction->rollBack();
            }
        } catch (Exception $ex) {
            $transaction->rollBack();
        }

        return $existedUser;
    }

    public static function getCollectionByModel($model){
        $result = [];

        switch ($model){
            case 'user':
                $result = \common\models\User::find()->where('record_type != :record_type and user_type = :user_type',
                    [':record_type' => RecordType::Deleted, ':user_type' => UserType::Member]);
                if (Yii::$app->request->isImpersonated()){
                    $result->andWhere('client_id = :client_id',[':client_id' =>  Yii::$app->request->getImpersonatedClientId()]);
                }
                return ArrayHelper::map($result->orderBy('username')->all(), 'id', 'username');
            case 'client':
                if (!Yii::$app->request->isImpersonated()){
                    $result = \common\models\Client::find()->where('record_type != :record_type', [':record_type' => RecordType::Deleted]);
                    return ArrayHelper::map($result->orderBy('name')->all(), 'id', 'name');
                }
                return [Yii::$app->request->getImpersonatedClientId() => Yii::$app->request->getImpersonatedClientName()];
            case 'restaurant':
                $result = \common\models\Restaurant::find()->where('record_type != :record_type', [':record_type' => RecordType::Deleted]);
                if (Yii::$app->request->isImpersonated()){
                    $result->andWhere('client_id = :client_id',[':client_id' =>  Yii::$app->request->getImpersonatedClientId()]);
                }
                return ArrayHelper::map($result->orderBy('name')->all(), 'id', 'name');
            case 'restaurant_chain':
                $result = \common\models\RestaurantChain::find()->where('record_type != :record_type', [':record_type' => RecordType::Deleted]);
                if (Yii::$app->request->isImpersonated()){
                    $result->andWhere('client_id = :client_id',['client_id' =>  Yii::$app->request->getImpersonatedClientId()]);
                }

                $restaurant_chains = self::getTranslatedList($result->orderBy('name_key')->all());

                return ArrayHelper::map($restaurant_chains, 'id', 'name_key');
            case 'restaurant_group':
                $result = \common\models\RestaurantGroup::find()->where('restaurant_group.record_type != :record_type', [':record_type' => RecordType::Deleted]);
                if (Yii::$app->request->isImpersonated()){
                    $result->join('INNER JOIN', 'restaurant_chain', 'restaurant_chain.id = restaurant_group.restaurant_chain_id')->andWhere('restaurant_chain.client_id = :client_id', [':client_id' => Yii::$app->request->getImpersonatedClientId()]);
                }

                $restaurant_groups =  self::getTranslatedList($result->orderBy('name_key')->all());

                return ArrayHelper::map($restaurant_groups, 'id', 'name_key');
            case 'company':
                $result = \common\models\Company::find()->where('record_type != :record_type', [':record_type' => RecordType::Deleted]);
                return ArrayHelper::map($result->orderBy('name')->all(), 'id', 'name');
            case 'menu_item':
                $result = \common\models\MenuItem::find()
                    ->where('menu_item.record_type != :record_type', [':record_type' => RecordType::Deleted]);
                if (Yii::$app->request->isImpersonated()){
                    $result->join('INNER JOIN', 'menu_category', 'menu_category.id = menu_item.menu_category_id');
                    $result->join('INNER JOIN', 'menu', 'menu_category.menu_id = menu.id')->andWhere('menu.client_id = :client_id', [':client_id' => Yii::$app->request->getImpersonatedClientId()]);
                }

                $menu_items =  self::getTranslatedList($result->orderBy('name_key')->all());

                return ArrayHelper::map($menu_items, 'id', 'name_key');
            case 'menu_category':

                $result = \common\models\MenuCategory::find()->where('menu_category.record_type != :record_type', [':record_type' => RecordType::Deleted]);
                if (Yii::$app->request->isImpersonated()){
                    $result->join('INNER JOIN', 'menu', 'menu_category.menu_id = menu.id')->andWhere('menu.client_id = :client_id', [':client_id' => Yii::$app->request->getImpersonatedClientId()]);
                }
                $menu_categories = self::getTranslatedList($result->orderBy('name_key')->all());

                return ArrayHelper::map($menu_categories, 'id', 'name_key');
            default:
                break;
        }
    }

    private static function getTranslatedList($list){
        foreach ($list as $item) {
            $item->name_key = Yii::$app->globalCache->getLabel($item->name_key);
        }
        return $list;
    }
}
