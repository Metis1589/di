<?php

namespace common\models;

use Yii;
use yii\helpers\Inflector;
use dosamigos\transliterator\TransliteratorHelper;
use common\enums\RecordType;
use common\components\language\T;

/**
 * This is the model class for table "page".
 *
 * @property string $id
 * @property integer $language_id
 * @property integer $translation_id
 * @property integer $client_id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property string $description
 * @property string $robots
 * @property string $metakey
 * @property string $metadesc
 * @property string $open_from
 * @property string $open_to
 * @property integer $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Navigation[] $navigations
 * @property Language $language
 */
class Page extends \common\models\BaseModel
{
    public $is_main;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'page';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['language_id', 'required','message' => T::e('Language is missing')],
            ['title', 'required','message' => T::e('Title is missing')],
            ['content', 'required','message' => T::e('Content is missing')],
            ['open_from', 'required','message' => T::e('Open from is missing')],
            ['open_to', 'required','message' => T::e('Open to is missing')],

            [['title'], 'string', 'max' => 150,  'message' => T::e('Title is too long'), 'tooLong'=>T::e('Title is too long')],
            [['slug'], 'string', 'max' => 100,  'message' => T::e('Slug is too long'), 'tooLong'=>T::e('Slug is too long')],
            [['robots'], 'string', 'max' => 30,  'message' => T::e('Robots is too long'), 'tooLong'=>T::e('Robots is too long')],
            [['description'], 'string', 'max' => 150,  'message' => T::e('Description is too long'), 'tooLong'=>T::e('Description is too long')],
            
            [['language_id'], 'number', 'message' => T::e('Language is wrong')],
            [['client_id'], 'number', 'message' => T::e('Client is wrong')],
    
            [['record_type'], 'string'],
            [['content'], 'string'],
            [['create_on', 'last_update'], 'safe'],
            ['open_from', 'date', 'format' => 'php:Y-m-d H:i', 'message' => T::e('Open from format is wrong')],
            ['open_to', 'date', 'format' => 'php:Y-m-d H:i', 'message' => T::e('Open to format is wrong')],
            [['slug'], 'filter', 'filter' => 'trim'],
            [['slug'], 'filter', 'filter' => function($value){
                    return Inflector::slug($value);  
            }],
            [['open_from'], function($attribute) {
                if(!array_intersect(array_keys($this->getErrors()),['open_from','open_to'])){
                    $start_date = new \DateTime($this->open_from);
                    $end_date = new \DateTime($this->open_to);
                    if($start_date>=$end_date){
                        $this->addError('open_from',T::e('Open from must be earlier than open to'));
                    }
                }
            }],
            [['description', 'robots'], 'default', 'value' => null],
            [['slug'], 'unique', 'targetAttribute' => ['language_id', 'slug', 'client_id'], 'filter' => function($query){
                /** @var $query \yii\db\ActiveQuery */
                $query->andWhere('record_type<>:deleted',[':deleted'=>RecordType::Deleted]);
            }, 'message' => T::e('Slug is already taken'), 'message' => T::e('Slug doesn\'t unique for current language.')]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => T::l('ID'),
            'language_id' => T::l('Language'),
            'title' => T::l('Title'),
            'slug' => T::l('Slug'),
            'content' => T::l('Content'),
            'client_id' => T::l('Client'),
            'description' => T::l('Description'),
            'robots' => T::l('Robots'),
            'metakey' => T::l('Meta Keywords'),
            'metadesc' => T::l('Meta Description'),
            'open_from' => T::l('Open From'),
            'open_to' => T::l('Open To'),
            'record_type' => T::l('Record type'),
            'create_on' => T::l('Created On'),
            'last_update' => T::l('Last Update')
        ];
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
    public function getNavigations()
    {
        return $this->hasMany(Navigation::className(), ['page_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['id' => 'language_id']);
    } 
    
    /**
     * Returns main page of the site
     */
    public function getMainPage($lang=null){
        $lang = $lang ? $lang : Yii::$app->globalCache->getLanguageId(Yii::$app->translationLanguage->language); 
        return Page::find()->where("slug='' AND language_id=:language_id",[':language_id'=>$lang])->one();
    }

}
