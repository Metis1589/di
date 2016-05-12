<?php

namespace common\models;

use common\components\language\T;
use common\enums\AddressType;
use common\enums\RestaurantAddressType;
use common\enums\RestaurantScheduleType;
use Yii;
use common\enums\RecordType;
use admin\common\ArrayHelper;
use common\components\ImageHelper;
use common\components\IOHelper;
use yii\web\UploadedFile;

/**
 * Class Restaurant
 *
 * @package common\models
 *
 * @property Address $physicalAddress
 * @property RestaurantDelivery $restaurantDelivery
 */
class Restaurant extends RestaurantBase
{
     public $logo_file;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'restaurant';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'required','message' => T::e('Name is missing')],
            ['trading_name', 'required','message' => T::e('Trading name is missing')],
            ['slug', 'required','message' => T::e('Slug is missing')],
            ['seo_title', 'required', 'message' => T::e('SEO title is missing')],
            ['meta_text', 'required', 'message' => T::e('Meta text is missing')],
            ['meta_description', 'required', 'message' => T::e('Meta description is missing')],
            ['vat_number', 'required', 'message' => T::e('Vat number is missing')],
            ['default_cook_time', 'required', 'message' => T::e('Default cook time is missing')],
            ['default_preparation_time', 'required', 'message' => T::e('Default preparation time is missing')],
            ['meta_text', 'required', 'message' => T::e('Meta text is missing')],
            ['price_range', 'required','message' => T::e('Price range is missing')],
            [['opening_day', 'create_on', 'last_update'], 'safe'],
            [['address_base_id'], 'required'],
            [['have_app', 'address_base_id', 'restaurant_group_id'], 'integer'],
            [['price_range'], 'number'],
            [['is_newest', 'is_featured', 'is_from_signup'], 'boolean'],
            [['name', 'trading_name', 'slug', 'seo_title', 'meta_description'], 'string', 'max' => 255, 'message' => T::e('Too long')],
            [['meta_text'], 'string', 'max' => 1000,  'message' => T::e('Meta text is too long'), 'tooLong'=>T::e('Meta text is too long')],
            [['description'], 'string', 'message' => T::e('Description is too long'), 'tooLong'=>T::e('Description is too long')],
            [['have_app'], 'default', 'value' => 0],
            [['opening_day'], 'date', 'message' => T::e('The format of date is invalid.')],
            ['seo_area_id', 'required','message' => T::e('Seo area is missing')],
            ['currency_id', 'required','message' => T::e('Currency is missing')],
            ['restaurant_group_id', 'common\validators\CustomExistValidator', 'targetClass' => '\common\models\RestaurantGroup', 'targetAttribute' => 'id',  'message' => T::e('Invalid restaurant group')],
            ['address_base_id', 'common\validators\CustomExistValidator', 'targetClass' => '\common\models\AddressBase', 'targetAttribute' => 'id',  'message' => T::e('Invalid address base')],
            ['seo_area_id', 'common\validators\CustomExistValidator', 'targetClass' => '\common\models\SeoArea', 'targetAttribute' => 'id',  'message' => T::e('Invalid seo area')],
            ['currency_id', 'common\validators\CustomExistValidator', 'targetClass' => '\common\models\Currency', 'targetAttribute' => 'id',  'message' => T::e('Invalid currency')],
            //[['default_preparation_time'], 'number' ,'min' => 0, 'max' => 100000, 'message' => T::e('Invalid value'), 'tooSmall' => T::e('Value is too small'), 'tooBig' => T::e('Value is too big')],
            [['default_preparation_time'], 'date', 'format' => 'H:m:s',  'message' => T::e('Invalid default preparation time')],

//            [['default_preparation_time'], function($attribute){
//                if($this->{$attribute} == \common\components\FormatHelper::formatTime(mktime(0, 0, 0))){
//                    $this->addError('default_preparation_time',T::e('Invalid default preparation time'));
//                }
//            }],
            [['default_cook_time'], 'date', 'format' => 'H:m:s',  'message' => T::e('Invalid default cook time')],
//            [['default_cook_time'], function($attribute){
//                if($this->{$attribute} == \common\components\FormatHelper::formatTime(mktime(0, 0, 0))){
//                    $this->addError('default_cook_time',T::e('Invalid default cook time'));
//                }
//            }],
            [['logo_file_name'], 'string', 'max' => 255],
            [['logo_file'], 'file', 'skipOnEmpty' => false, 'on' => 'create'/*, 'extensions' => 'jpg, png, jpeg', 'wrongExtension' => Yii::t('error', 'Upload Image file')*/],
            [['logo_file'], 'file', 'skipOnEmpty' => true, 'on' => 'update'/*, 'extensions' => 'jpg, png, jpeg', 'wrongExtension' => Yii::t('error', 'Upload Image file')*/],
            [['logo_file'], function($attribute) {
                if ($this->logo_file && $this->logo_file->name){
                    $file_parts = explode('.',$this->logo_file->name);
                    $ext = strtolower($file_parts[count($file_parts)-1]);
                    if(!in_array($ext,['jpg','png','jpeg'])){
                        $this->addError('image_name',T::e('Wrong file type. Only jpg,png,jpeg supported.'));
                    }
                }
            }],        
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => T::l('ID'),
            'name' => T::l('Name'),
            'description' => T::l('Description'),
            'trading_name' => T::l('Trading name'),
            'price_range' => T::l('Price range'),
            'default_cook_time' => T::l('Cook time'),
            'logo_file_name'=>T::l('Logo file name'),
            'slug' => T::l('Slug'),
            'vat_number' => T::l('Vat number'),
            'currency_id' => T::l('Currency'),
            'opening_day' => T::l('Opening Day'),
            'avg_prepare_time' => T::l('Avg Prepare Time'),
            'is_newest' => T::l('Is Newest'),
            'seo_title' => T::l('Seo Title'),
            'meta_text' => T::l('Meta Text'),
            'meta_description' => T::l('Meta Description'),
            'seo_area_id' => T::l('Seo Area'),
            'default_cook_time' => T::l('Default Cook Time'),
            'default_preparation_time' => T::l('Default Food Prep Time'),
            'have_app' => T::l('Have App'),
            'is_featured' => T::l('Is Featured'),
            'is_from_signup' => T::l('Is From Signup'),
            'address_base_id' => T::l('Address Base ID'),
            'restaurant_group_id' => T::l('Restaurant Group ID'),
            'record_type' => T::l('Record Type'),
            'create_on' => T::l('Create On'),
            'last_update' => T::l('Last Update'),
        ];
    }

    public function translatedProperties() {
        return ['description'];
    }

    public static function getRestaurantsForSelect(){}
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFeedbacks()
    {
        return $this->hasMany(Feedback::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenus()
    {
        return $this->hasMany(Menu::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantGroup()
    {
        return $this->hasOne(RestaurantGroup::className(), ['id' => 'restaurant_group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddressBase()
    {
        return $this->hasOne(AddressBase::className(), ['id' => 'address_base_id']);
    }

    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantBestForItems()
    {
        return $this->hasMany(RestaurantBestForItem::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBestForItems()
    {
        return $this->hasMany(BestForItem::className(), ['id' => 'best_for_item_id'])->viaTable('restaurant_best_for_item', ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantContacts()
    {
        return $this->hasMany(RestaurantContact::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContacts()
    {
        return $this->hasMany(Contact::className(), ['id' => 'contact_id'])->viaTable('restaurant_contact', ['restaurant_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContact()
    {
        return $this->hasOne(RestaurantContact::className(), ['restaurant_id' => 'id'])->andOnCondition(['restaurant_contact.role' => 'Contact']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBilling()
    {
        return $this->hasOne(RestaurantContact::className(), ['restaurant_id' => 'id'])->andOnCondition(['restaurant_contact.role' => 'Billing']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhoto()
    {
        return $this->hasOne(RestaurantPhoto::className(), ['restaurant_id' => 'id'])->andOnCondition(['restaurant_photo.is_default' => 1]);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
//    public function getAddresses()
//    {
//        return $this->hasMany(Address::className(), ['id' => 'address_id'])->viaTable('restaurant_address', ['restaurant_id' => 'id'], function($query) {
//            $query->onCondition("address_type IN ('".RestaurantAddressType::Physical."','".RestaurantAddressType::Pickup."')");
//        });
//    }

    public function getPhysicalAddress()
    {
        return $this->hasOne(Address::className(), ['id' => 'address_id'])->viaTable('restaurant_address', ['restaurant_id' => 'id'], function($query) {
            $query->onCondition(['address_type' =>  RestaurantAddressType::Physical, 'restaurant_address.record_type' => RecordType::Active]);
        });
    }

    public function getPickupAddress()
    {
        return $this->hasOne(Address::className(), ['id' => 'address_id'])->viaTable('restaurant_address', ['restaurant_id' => 'id'], function($query) {
            $query->onCondition(['address_type' =>  RestaurantAddressType::Pickup, 'restaurant_address.record_type' => RecordType::Active]);
        });
    }

    public function getRestaurantChain()
    {
        return $this->hasOne(RestaurantChain::className(), ['id' => 'restaurant_chain_id'])->viaTable('restaurant_group', ['id' => 'restaurant_group_id']);
    }

    public function getRestaurantDelivery()
    {
        return $this->hasOne(RestaurantDelivery::className(), ['restaurant_id' => 'id']);
    }

    public function getRestaurantProperties()
    {
        return $this->hasOne(PropertyAssignment::className(), ['restaurant_id' => 'id']);
    }

    public function getRestaurantSchedules()
    {
        return $this->hasMany(RestaurantSchedule::className(), ['restaurant_id' => 'id'])->andOnCondition(['restaurant_schedule.record_type' => RecordType::Active]);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantContactOrders()
    {
        return ArrayHelper::map(self::find()->where("record_type <> '".RecordType::Deleted."'")->all(), 'id', 'name');
    }
    
    public static function getById($id) {
        return self::findOne(['id' => $id, "record_type <> '".RecordType::Deleted."'"]);
    }
    
    public function afterFind() {
        if (!empty($this->opening_day)) {
            $this->opening_day = \common\helpers\FormatHelper::convertFromMySql($this->opening_day);
        }
        
        parent::afterFind();
    }

    /**
     * get parent groups, chain and client
     * @return array
     */
    public function getParents() {

        $restaurant_group_ids = [];

        /** @var RestaurantGroup $group */
        $group = $this->restaurantGroup;
        $chain = null;

        while ($group != null) {
            $restaurant_group_ids[] = $group->id;
            $chain = $group->restaurantChain;
            $group = $group->parent;
        };

        return [
            'restaurant_group_ids' => $restaurant_group_ids,
            'restaurant_chain_id' => isset($chain) ? $chain->id : null,
            'client_id' => $this->client_id
        ];
    }

    /**
     * Is restaurant in group
     * @param $restaurant_group_id
     * @return bool
     */
    public function isInGroup($restaurant_group_id) {
        $group_ids = $this->getParents()['restaurant_group_ids'];
        return in_array($restaurant_group_id, $group_ids);
    }

    /**
     * is restaurant in chain
     * @param $restaurant_chain_id
     * @return bool
     */
    public function isInChain($restaurant_chain_id) {
        return $this->getParents()['restaurant_chain_id'] == $restaurant_chain_id;
    }

    /**
     * is restaurant belongs to client
     * @param $client_id
     * @return bool
     */
    public function isInClient($client_id)
    {
        return $this->getParents()['client_id'] == $client_id;
    }

    /**
     * 
     */
    public function saveRestaurantDetails($postData){
        $transaction = Yii::$app->db->beginTransaction();
        try 
        {
            $this->load($postData);
            if ( Yii::$app->request->isImpersonated() && $this->isNewRecord) {
                $this->client_id = Yii::$app->request->getImpersonatedClientId();
            }
            if(!$this->getIsNewRecord() && !$this->getAttribute('logo_file_name') && $this->getOldAttribute('logo_file_name')){
                $this->logo_file_name = $this->getOldAttribute('logo_file_name');
            }
            $isSaved = Yii::$app->translationLanguage->saveModelWithoutTransaction($this, $this->client_id);
            // Saving restaurant image model
            $isSaved = $isSaved && $this->savePhotoModel();
            // Saving billing model
            $isSaved = $isSaved && $this->saveBillingModel($postData);
            $isSaved = $isSaved && $this->saveBillingEmails($postData);
            $isSaved = $isSaved && $this->saveContactModel($postData);
            $isSaved = $isSaved && $this->saveAddressModel($postData['Address']['physical'], RestaurantAddressType::Physical);
            $isSaved = $isSaved && $this->saveAddressModel($postData['Address']['pickup'], RestaurantAddressType::Pickup);
            if($isSaved)
            {
                $transaction->commit();
            }
            else
            {
                $transaction->rollBack();
            }

        } 
        catch (Exception $ex) {
            $transaction->rollBack();
        }

        return $isSaved;
    }
    
    private function savePhotoModel(){
        $this->saveRestaurantPhoto($this->photo);
        $this->saveRestaurantLogo();
        $this->photo->restaurant_id = $this->id;
        return $this->photo->save();
    }
    
    private function saveBillingModel($postData){
        $this->billing->attributes = $postData['Billing'];
        $this->billing->restaurant_id = $this->id;
        return $this->billing->save();
    }
    
    private function saveBillingEmails($postData){
        $isSaved = true;
        // Saving contact email models
        if(isset($postData['Billing']['emails']) && $postData['Billing']['emails']){
            $billingEmails = $this->billing->getEmails()->all();
            foreach($postData['Billing']['emails'] as $k=>$singleBillingData){
                if(isset($singleBillingData['id']) && $singleBillingData['id']){
                    $finded = false;
                    foreach($billingEmails as $emailModel){
                        if($emailModel->id==$singleBillingData['id']){
                            $finded = true;
                            break;
                        }
                    }
                    if(!$finded){
                        $emailModel = new RestaurantContactEmail;
                    }
                }
                else{
                    $emailModel = new RestaurantContactEmail;
                }
                $emailModel->setAttributes($postData['Billing']['emails'][$k]);
                $emailModel->restaurant_contact_id = $this->billing->id;
                $isSaved = $isSaved && $emailModel->save();
            }
        }
        else{
            $isSaved = false;
            $this->billing->emails[0]->addError('email', T::e('At least one email must be specified for billing contact.'));
        }
        if(isset($postData['Billing']['deletedEmails']) && $postData['Billing']['deletedEmails']){
            $deletedEmails = $postData['Billing']['deletedEmails'];
            $deletedArray = split(',', trim($deletedEmails));
            if (count($deletedArray) > 0) {
                foreach ($deletedArray as $email) {
                    if (!empty($email)) {
                        $emailModel = RestaurantContactEmail::findOne(['id' => $email]);
                        $emailModel->record_type = RecordType::Deleted;
                        $isSaved = $isSaved && $emailModel->save(false,['record_type']);
                    }
                }
            }    
        }
        return $isSaved;
    }
    
    private function saveContactModel($postData){
        $isSaved = true;
        $this->contact->attributes = $postData['Contact'];
        $this->contact->restaurant_id = $this->id;
        $isSaved = $isSaved && $this->contact->save();
        // Saving contact email models
        if($this->contact->emails){
            foreach($this->contact->emails as $emailModel){
                $emailModel->restaurant_contact_id = $this->contact->id;
                $isSaved = $isSaved && $emailModel->save();
            }
        }
        return $isSaved;
    }
    
    private function saveAddressModel($postData, $type){
        $isSaved = true;
        $addressModel = null;
        if ($type == RestaurantAddressType::Physical) {
            $addressModel = $this->physicalAddress;
        } else if ($type == RestaurantAddressType::Pickup) {
            $addressModel = $this->pickupAddress;
        }
        if (!isset($addressModel)) {
            $addressModel = new Address();
        }
        $addressModel->attributes = $postData;
        $address_is_new = $addressModel->getIsNewRecord();
        $isSaved = $isSaved && $addressModel->save();
        if($address_is_new){
            $restaurantAddress = new RestaurantAddress;
            $restaurantAddress->address_id =  $addressModel->id;
            $restaurantAddress->restaurant_id = $this->id;
            $restaurantAddress->address_type = $type;
            $isSaved = $isSaved && $restaurantAddress->save();
        }
        return $isSaved;
    }
    
    public function saveRestaurantPhoto(\common\models\RestaurantPhoto &$model){
        $model->file = UploadedFile::getInstance($model, 'image_name');
        if (!empty($model->file) && $model->validate(['file'])) {
            $filename = IOHelper::getRestaurantImagesPath() . $model->file->baseName . '.' . $model->file->extension;
            $model->file->saveAs(Yii::$app->params['images_upload_path'] . $filename);
            ImageHelper::createThumb(Yii::$app->params['images_upload_path'] . $filename, Yii::$app->params['restaurant_thumb_width']);
            $model->image_name = $model->file->baseName . '.' . $model->file->extension;
        }
    }
    
    public function saveRestaurantLogo(){
        $this->logo_file = UploadedFile::getInstance($this, 'logo_file_name');
        if (!empty($this->logo_file) && $this->validate(['logo_file'])) {
            $filename = IOHelper::getRestaurantLogoPath() . $this->logo_file->baseName . '.' . $this->logo_file->extension;
            $this->logo_file->saveAs(Yii::$app->params['images_upload_path'] . $filename);
            ImageHelper::createThumb(Yii::$app->params['images_upload_path'] . $filename, Yii::$app->params['restaurant_thumb_width']);
            $this->logo_file_name = $this->logo_file->baseName . '.' . $this->logo_file->extension;
            $this->save('logo_file_name');
        }
    }
    
    public function prepareRelationRecords($postData){
        if (empty($this->billing))
        {
            $billing = new RestaurantContact();
            $billing->role = 'Billing';
            $this->populateRelation('billing', $billing);
        }
        if(isset($postData['Billing']) && $postData['Billing']){
            $this->billing->setAttributes($postData['Billing']);
        }
        if(empty($this->billing->emails)){
            $this->billing->populateRelation('emails', [new RestaurantContactEmail()]);
        }
        if(isset($postData['Billing']['emails']) && $postData['Billing']['emails']){
            $oldRelations = $this->billing->emails;
            $relations = [];
            $this->billing->populateRelation('emails', []);
            foreach($postData['Billing']['emails'] as $k=>$billingEmail){
                $finded = false;
                if(isset($billingEmail['id']) && $billingEmail['id'] && $oldRelations){
                    foreach($oldRelations as $oldRelation){
                        if($oldRelation->id==$billingEmail['id']){
                            $oldRelation->setAttributes($billingEmail);
                            $relations[] = $oldRelation;
                            $finded = true;
                            break;
                        }
                    }
                }
                if(!$finded){
                    $oldRelation = new RestaurantContactEmail;
                    $oldRelation->setAttributes($billingEmail);
                    $relations[] = $oldRelation;
                }
            }
            $this->billing->populateRelation('emails', $relations);
        }
        if (empty($this->contact))
        {
            $contact = new RestaurantContact;
            $contact->role = 'Contact'; 
            $this->populateRelation('contact', $contact);
        }
        if(isset($postData['Contact']) && $postData['Contact']){
            $this->contact->setAttributes($postData['Contact']);
        }
        if(empty($this->contact->emails)){
            $this->contact->populateRelation('emails', [new RestaurantContactEmail()]);
        }
        if(isset($postData['Contact']['emails']) && $postData['Contact']['emails']){
            $oldRelations = $this->contact->emails;
            $relations = [];
            foreach($postData['Contact']['emails'] as $k=>$contactEmail){
                $finded = false;
                if(isset($contactEmail['id']) && $contactEmail['id'] && $oldRelations){
                    foreach($oldRelations as $oldRelation){
                        if($oldRelation->id==$contactEmail['id']){
                            $oldRelation->setAttributes($contactEmail);
                            $relations[] = $oldRelation;
                            $finded = true;
                            break;
                        }
                    }
                }
                if(!$finded){
                    $oldRelation = new RestaurantContactEmail;
                    $oldRelation->setAttributes($contactEmail);
                    $relations[] = $oldRelation;
                }
            }
            $this->contact->populateRelation('emails', $relations);
        }
        if (empty($this->physicalAddress)) {
            $this->populateRelation('physicalAddress', new Address());
        }
        if (empty($this->pickupAddress)) {
            $this->populateRelation('pickupAddress', new Address());
        }
        if(isset($postData['Address']['physical'])){
            $this->physicalAddress->setAttributes($postData['Address']['physical']);
        }
        if(isset($postData['Address']['pickup'])){
            $this->pickupAddress->setAttributes($postData['Address']['pickup']);
        }
        if (empty($this->photo))
        {
            $restaurantPhoto = new RestaurantPhoto();
            $restaurantPhoto->is_default = 1;
            $restaurantPhoto->scenario = 'create';
            $this->populateRelation('photo', $restaurantPhoto);
        }
        else{
            $this->photo->scenario = 'update';
        }
    }

    private function getAssignedPropertiesByGroup($restaurant_group_id, &$result) {
        $propertyAssigment = PropertyAssignment::find()->where(['restaurant_group_id' => $restaurant_group_id])->andWhere('record_type != "'.\common\enums\RecordType::Deleted.'"')->one();

        if (isset($propertyAssigment)) {
            foreach($propertyAssigment->attributes as $key => $value) {
                if (empty($result[$key])) {
                    $result[$key] = $value;
                }
            }
        }

        $group = RestaurantGroup::find()->where(['id' => $restaurant_group_id])->andWhere('record_type != "'.\common\enums\RecordType::Deleted.'"')->one();

        if ($group != null && $group->parent_id != null) {
            $this->getAssignedPropertiesByGroup($group->parent_id, $result);
        }
    }

    /**
     * get assigned property
     */
    public function getAssignedProperties() {
        $result = [
            'max_delivery_order_value' => 0,
            'min_delivery_order_value' => 0,
            'max_delivery_order_amount' => 0,
            'min_delivery_order_amount' => 0,
            'max_collection_order_value' => 0,
            'min_collection_order_value' => 0,
            'max_collection_order_amount' => 0,
            'min_collection_order_amount' => 0,
        ];

        $propertyAssigment = PropertyAssignment::find()->where(['restaurant_id' => $this->id])->andWhere('record_type != "'.\common\enums\RecordType::Deleted.'"')->one();

        if (isset($propertyAssigment)) {
            foreach($propertyAssigment->attributes as $key => $value) {
                $result[$key] = $value;
            }
        }

        if (isset($this->restaurant_group_id)) {
            // load assigned to group
            $this->getAssignedPropertiesByGroup($this->restaurant_group_id, $result);

            // load assigned to chain
            $propertyAssigment = PropertyAssignment::find()->where(['restaurant_chain_id' => $this->restaurantGroup->restaurant_chain_id])->andWhere('record_type != "' . \common\enums\RecordType::Deleted . '"')->one();

            if (isset($propertyAssigment)) {
                foreach($propertyAssigment->attributes as $key => $value) {
                    if (empty($result[$key])) {
                        $result[$key] = $value;
                    }
                }
            }
        }

        // load assigned to client
        $propertyAssigment = PropertyAssignment::find()->where(['client_id' => $this->client_id])->andWhere('record_type != "'.\common\enums\RecordType::Deleted.'"')->one();

        if (isset($propertyAssigment)) {
            foreach($propertyAssigment->attributes as $key => $value) {
                if (empty($result[$key])) {
                    $result[$key] = $value;
                }
            }
        }

        return $result;
    }

    private function getAssignedMenusByGroup($restaurant_group_id, &$resultMenus) {
        $menuAssignments = MenuAssignment::find()->where(['restaurant_group_id' => $restaurant_group_id])->andWhere('record_type != :record_type', ['record_type' => RecordType::Deleted])->all();

        /** @var MenuAssignment $assignment */
        foreach ($menuAssignments as $assignment) {
            if (!array_key_exists($assignment->menu_id, $resultMenus)) {
                $resultMenus[$assignment->menu_id] = $assignment;
            }
            $group = RestaurantGroup::find()->where(['id' => $restaurant_group_id])->andWhere('record_type != :record_type', ['record_type' => RecordType::Deleted])->one();

            if ($group != null && $group->parent_id != null) {
                $this->getAssignedMenusByGroup($group->parent_id, $resultMenus);
            }
        }
    }

    /**
     * get assigned menus
     */
    public function getAssignedMenus() {
        $resultMenus = [];

        $menuAssignments = MenuAssignment::find()->where(['restaurant_id' => $this->id])->andWhere('record_type != :record_type', ['record_type' => RecordType::Deleted])->all();

        /** @var MenuAssignment $assignment */
        foreach ($menuAssignments as $assignment) {
            if (!array_key_exists($assignment->menu_id, $resultMenus)) {
                $resultMenus[$assignment->menu_id] = $assignment;
            }
        }

        if (isset($this->restaurant_group_id)) {
            // load assigned to group
            $this->getAssignedMenusByGroup($this->restaurant_group_id, $resultMenus);

            // load assigned to chain
            $menuAssignments = MenuAssignment::find()->where(['restaurant_chain_id' => $this->restaurantGroup->restaurant_chain_id])->andWhere('record_type != :record_type', ['record_type' => RecordType::Deleted])->all();

            /** @var MenuAssignment $assignment */
            foreach ($menuAssignments as $assignment) {
                if (!array_key_exists($assignment->menu_id, $resultMenus)) {
                    $resultMenus[$assignment->menu_id] = $assignment;
                }
            }
        }

        // load assigned to client
        $menuAssignments = MenuAssignment::find()->where(['client_id' => $this->client_id])->andWhere('record_type != :record_type', ['record_type' => RecordType::Deleted])->all();

        /** @var MenuAssignment $assignment */
        foreach ($menuAssignments as $assignment) {
            if (!array_key_exists($assignment->menu_id, $resultMenus)) {
                $resultMenus[$assignment->menu_id] = $assignment;
            }
        }
        
        $result = [];
        foreach ($resultMenus as $assigned_menu) {
            if ($assigned_menu['record_type'] == RecordType::Active){
                $result[$assigned_menu->id] = $assigned_menu->menu;
            }
        }

        return $result;
    }

    public static function getDeliveryService($restaurant_id) {
        return RestaurantDelivery::find()->joinWith(['restaurantDeliveryCharges'])->where(['restaurant_id' => $restaurant_id])->andWhere(['restaurant_delivery.record_type' => RecordType::Active])
            ->orderBy('restaurant_delivery_charge.distance_in_miles')->one();
    }

    /**
     * get assigned delivery service
     */
    public static function getAssignedDeliveryService($restaurant_id) {

        $restaurant = Restaurant::findOne($restaurant_id);

        $delivery = static::getDeliveryService($restaurant_id);

        if (isset($delivery)) {
            return $delivery;
        }

        if (isset($restaurant->restaurant_group_id)) {
            // load assigned to group
            return RestaurantGroup::getAssignedDeliveryService($restaurant->restaurant_group_id);
        }

        // load assigned to client
        return Client::getDeliveryService($restaurant->client_id);
    }

    /**
     * get assigned schedule
     */
    public function getAssignedSchedules() {
        $resultMenus = [];

        $schedules = RestaurantSchedule::find()->where(['restaurant_id' => $this->id])->andWhere(['restaurant_schedule.record_type' => RecordType::Active])->orderBy('day')->all();

        if ($this->containsOneActiveSchedule($schedules)) {
            return $schedules;
        }

        if (isset($this->restaurant_group_id)) {
            // load assigned to group
            $delivery = $this->getAssignedSchedulesByGroup($this->restaurant_group_id, $resultMenus);

            if (isset($delivery)) {
                return $delivery;
            }

            // load assigned to chain
            $schedules = RestaurantSchedule::find()->where(['restaurant_chain_id' => $this->restaurantGroup->restaurant_chain_id])->andWhere(['restaurant_schedule.record_type' => RecordType::Active])->orderBy('day')->all();

            if ($this->containsOneActiveSchedule($schedules)) {
                return $schedules;
            }
        }

        // load assigned to client
        $schedules = RestaurantSchedule::find()->where(['client_id' => $this->client_id])->andWhere(['restaurant_schedule.record_type' => RecordType::Active])->orderBy('day')->all();

        return $schedules;
    }

    /**
     * @param $field_key
     * @return string
     */
    public function getRestaurantCustomFieldValue($field_key) {
        $customFieldValue = CustomFieldValue::find()->joinWith('customField')->where([
            'custom_field.key' => $field_key,
            'custom_field_value.restaurant_id' => $this->id,
            'client_id' => $this->client_id,
            'custom_field.record_type' => RecordType::Active,
            'custom_field_value.record_type' => RecordType::Active
        ])->one();

        if (isset($customFieldValue)) {
            return $customFieldValue->value;
        }

        return null;
    }

    private function containsOneActiveSchedule($schedules) {
        foreach($schedules as $schedule) {
            if ($schedule->record_type == RecordType::Active) {
                return true;
            }
        }
        return false;
    }

    private function getAssignedSchedulesByGroup($restaurant_group_id) {

        $schedules = RestaurantSchedule::find()->where(['restaurant_group_id' => $restaurant_group_id])->andWhere(['restaurant_schedule.record_type' => RecordType::Active])->orderBy('day')->all();

        if ($this->containsOneActiveSchedule($schedules)) {
            return $schedules;
        }

        $group = RestaurantGroup::find()->where(['id' => $restaurant_group_id])->andWhere('record_type != "'.\common\enums\RecordType::Deleted.'"')->one();

        if ($group != null && $group->parent_id != null) {
            return $this->getAssignedSchedulesByGroup($group->parent_id);
        }

        return null;
    }

    public static function getParentsByRestaurantId($restaurant_id) {
        $restaurant = Restaurant::findOne(['id' => $restaurant_id]);

        if ($restaurant == null) {
            return [];
        }

        return $restaurant->getParents();
    }
}
