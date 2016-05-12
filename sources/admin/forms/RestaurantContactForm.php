<?php
namespace admin\forms;

use Yii;
use yii\base\Model;
use common\models\RestaurantContact;
use common\models\Contact;
use common\models\Person;
use common\models\Phone;
use common\enums\RecordType;

/**
 * Login form
 */
class RestaurantContactForm extends Model
{
    public $id;
    public $restaurant_id;
    public $contact_id;
    public $role;
    public $is_opt_in;
    public $first_name;
    public $last_name;
    public $middle_name;
    public $title;
    public $email;
    public $phone;
    public $phone_type;
    public $record_type;
    public $isNewRecord;
    public $create_on;
    public $last_update;
    
    public $restaurant_contact;
    private $_contact;
    private $_person;
    private $_phone;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            ['restaurant_id', 'required', 'message' => Yii::t('error', 'Restaurant is missing')],
            ['restaurant_id', 'exist', 'targetClass' => '\common\models\Restaurant', 'targetAttribute' => 'id', 'filter' => "record_type <> '".RecordType::Deleted."'", 'message' => Yii::t('label', 'Invalid restaurant')],
            ['role', 'required', 'message' => Yii::t('error', 'Role is missing')],
            ['first_name', 'required', 'message' => Yii::t('error', 'First Name is missing')],
            ['last_name', 'required', 'message' => Yii::t('error', 'Last Name is missing')],
            ['email', 'required', 'message' => Yii::t('error', 'Email is missing')],
            ['phone', 'required', 'message' => Yii::t('error', 'Phone is missing')],
            ['phone_type', 'required', 'message' => Yii::t('error', 'Phone Type is missing')],
            ['record_type', 'required',  'message' => Yii::t('error','Record Type is missing')],
            ['email', 'email', 'message' => Yii::t('error', 'Email is invalid')],
            [['middle_name','title','is_opt_in'], 'safe']
        ];
    }
    
      public function attributeLabels()
    {
        return [
            'restaurant_id' => Yii::t('label', 'Restaurant Name'),
            'role' => Yii::t('label', 'Role'),
            'first_name' => Yii::t('label', 'First Name'),
            'last_name' => Yii::t('label', 'Last Name'),
            'middle_name' => Yii::t('label', 'Middle Name'),
            'title' => Yii::t('label', 'Title'),
            'email' => Yii::t('label', 'Email'),
            'phone' => Yii::t('label', 'Phone'),
            'phone_type' => Yii::t('label', 'Phone Type'),
            'is_opt_in' => Yii::t('label', 'Is Opt In'),
            'record_type' => Yii::t('label', 'Record Type'),
            'create_on' => Yii::t('label', 'Create On'),
            'last_update' => Yii::t('label', 'Last Update'),
        ];
    }
    
    public function getByRestaurantContact(RestaurantContact $restaurantContact = null) {
        if (!isset($restaurantContact)) {
            $restaurantContact = new RestaurantContact();
        }
        $this->restaurant_id = $restaurantContact->restaurant_id;
        
        $this->role = $restaurantContact->role;
        
        $this->id = $restaurantContact->id;
        $this->isNewRecord = $restaurantContact->isNewRecord;
        $this->create_on = $restaurantContact->create_on;
        $this->last_update = $restaurantContact->last_update;
        $this->record_type = $restaurantContact->record_type;
        
        $contact = $restaurantContact->contact;
        $person = new Person();
        $phone = new Phone();
        
        if (isset($contact)) {
            $this->contact_id = $contact->id;
            if (isset($contact->is_opt_in)) {
                $this->is_opt_in = $contact->is_opt_in;
            }
            $this->email = $contact->email;
            
            $person = $contact->person;
            $this->first_name = $person->first_name;
            $this->last_name = $person->last_name;
            $this->middle_name = $person->middle_name;
            $this->title = $person->title;
            
            $phone = $contact->phone;
            $this->phone = $phone->number;
            $this->phone_type = $phone->type;
        } else {
            $contact = new Contact();
        }
        $this->restaurant_contact = $restaurantContact;
        $this->_contact = $contact;
        $this->_person = $person;
        $this->_phone = $phone;
    }
    
    public function save() {
       if (!$this->validate()) {
           return false;
       }
        $transaction = Yii::$app->db->beginTransaction();
        $isSaved = true;
        try {
            $isSaved = $this->savePerson();
            if ($isSaved) {
                $isSaved = $isSaved && $this->savePhone();
                if ($isSaved) {
                    $isSaved = $isSaved && $this->saveContact();
                    if ($isSaved) {
                        $isSaved = $isSaved && $this->saveRestaurantContact();
                    }
                }
            }
            if ($isSaved) {
                $transaction->commit();
            } else {
                $transaction->rollBack();
            }
        } catch (Exception $ex) {
            $transaction->rollBack();
        }
        return $isSaved;
    }
    
    private function savePerson(){
        $this->_person->first_name = $this->first_name;
        $this->_person->last_name = $this->last_name;
        $this->_person->middle_name = $this->middle_name;
        $this->_person->title = !empty($this->title) ? $this->title : null;
        if (isset($this->record_type)) {
            $this->_person->record_type = $this->record_type;
        }
        return $this->_person->save();
    }
    
    private function savePhone(){
        $this->_phone->number = $this->phone;
        $this->_phone->type = $this->phone_type;
        if (isset($this->record_type)) {
            $this->_phone->record_type = $this->record_type;
        }
        return $this->_phone->save();
    }
    
    private function saveContact(){
        $this->_contact->phone_id = $this->_phone->id;
        $this->_contact->person_id = $this->_person->id;
        $this->_contact->email = $this->email;
        $this->_contact->is_opt_in = $this->is_opt_in;
        if (isset($this->record_type)) {
            $this->_contact->record_type = $this->record_type;
        }
        return $this->_contact->save();
    }
    
    private function saveRestaurantContact(){
        $this->restaurant_contact->contact_id = $this->_contact->id;
        $this->restaurant_contact->restaurant_id = $this->restaurant_id;
        $this->restaurant_contact->role = $this->role;
        if (isset($this->record_type)) {
            $this->restaurant_contact->record_type = $this->record_type;
        }
        return $this->restaurant_contact->save();
    }
}

