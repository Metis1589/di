<?php

namespace common\models;

use Yii;
use common\components\language\T;

/**
 * This is the model class for table "restaurant_photo".
 *
 * @property string $id
 * @property string $image_name
 * @property string $title
 * @property integer $order
 * @property integer $restaurant_id
 * @property boolean $is_default
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Restaurant $restaurant
 */
class RestaurantPhoto extends \common\models\BaseModel
{
    public $file;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'restaurant_photo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image_name'], 'required', 'on' => 'create'],
            [['order', 'restaurant_id'], 'integer'],
            [['is_default'], 'boolean'],
            [['record_type'], 'string'],
            [['create_on', 'last_update'], 'safe'],
            [['image_name', 'title'], 'string', 'max' => 255],
            [['file'], 'file', 'skipOnEmpty' => false, 'on' => 'create'/*, 'extensions' => 'jpg, png, jpeg', 'wrongExtension' => Yii::t('error', 'Upload Image file')*/],
            [['file'], 'file', 'skipOnEmpty' => true, 'on' => 'update'/*, 'extensions' => 'jpg, png, jpeg', 'wrongExtension' => Yii::t('error', 'Upload Image file')*/],
            [['file'], function($attribute) {
                if ($this->file && $this->file->name){
                    $file_parts = explode('.',$this->file->name);
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
            'image_name' => T::l('Image Name'),
            'title' => T::l('Title'),
            'order' => T::l('Order'),
            'restaurant_id' => T::l('Restaurant ID'),
            'is_default' => T::l('Is Default'),
            'record_type' => T::l('Record Type'),
            'create_on' => T::l('Create On'),
            'last_update' => T::l('Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant()
    {
        return $this->hasOne(Restaurant::className(), ['id' => 'restaurant_id']);
    }
}
