<?php

namespace common\models;

use Yii;
use yii\helpers\Inflector;
use common\enums\RecordType;
use common\components\language\T;

/**
 * This is the model class for table "email_template".
 *
 * @property string $id
 * @property integer $language_id
 * @property integer $translation_id
 * @property integer $client_id
 * @property string $title
 * @property string $content
 * @property string $email_type
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Navigation[] $navigations
 * @property Language $language
 */
class EmailTemplate extends \common\models\BaseModel
{
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'email_template';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['language_id', 'required','message' => T::e('Language is missing')],
            ['title', 'required','message' => T::e('Subject is missing')],
            ['from_email', 'required','message' => T::e('From email is missing')],
            ['from_name', 'required','message' => T::e('From name is missing')],
            ['content', 'required','message' => T::e('Content is missing')],
            ['email_type', 'required','message' => T::e('Email type is missing')],

            [['title'], 'string', 'max' => 255,  'message' => T::e('Subject is too long'), 'tooLong'=>T::e('Subject is too long')],
            [['from_email'], 'string', 'max' => 255,  'message' => T::e('From email is too long'), 'tooLong'=>T::e('From email is too long')],
            [['from_name'], 'string', 'max' => 255,  'message' => T::e('From name is too long'), 'tooLong'=>T::e('From name is too long')],
            [['bcc'], 'string', 'max' => 255,  'message' => T::e('BCC is too long'), 'tooLong'=>T::e('BCC is too long')],
            [['cc'], 'string', 'max' => 255,  'message' => T::e('CC is too long'), 'tooLong'=>T::e('CC is too long')],
            
            ['language_id', 'number', 'message' => T::e('Language is wrong')],
            ['client_id', 'number', 'message' => T::e('Client is wrong')],
            
            [['language_id'], 'unique', 'targetAttribute' => ['language_id', 'email_type','client_id'], 'filter' => function($query){
                /** @var $query \yii\db\ActiveQuery */
                $query->andWhere('record_type<>:deleted',[':deleted'=>RecordType::Deleted]);
            }, 'message' => T::e('Email is already exists for choosen language')],
    
            [['record_type','email_type'], 'string'],
            [['content'], 'string'],
            [['create_on', 'last_update'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => T::l('ID'),
            'client_id' => T::l('Client'),
            'language_id' => T::l('Language'),
            'title' => T::l('Subject'),
            'from_email' => T::l('From email'),
            'from_name' => T::l('From name'),
            'bcc' => T::l('BCC'),
            'cc' => T::l('CC'),
            'content' => T::l('Content'),
            'email_type' => T::l('Email type'),
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
    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['id' => 'language_id']);
    }

    /**
     *
     * Creates default email templates for newly created client.
     *
     * @param integer $client_id client for whom emails will be created.
     *
     * @return bool
     */
    public static function fillDefaultEmailTemplates($client_id){
        $isSaved = true;
        $tempaltes = array_keys(\common\enums\EmailType::getLabels());
        foreach($tempaltes as $template){
            $model = static::createDefaultEmailTemplate($template);
            if($model !== false){
                $model->client_id = $client_id;
                $model->record_type = \common\enums\RecordType::Active;
                $isSaved = $isSaved && $model->save();
            }
        }
        return $isSaved;
    }

    /**
     * Get default template model
     * @param string $emailType
     * @return bool|\common\models\EmailTemplate
     * if view file found return model otherwise false
     */
    public static function createDefaultEmailTemplate($emailType) {
        $templatePath = '@common/mail/client/' . $emailType;
        $model = false;
        if(file_exists(Yii::getAlias($templatePath).'.php')){
            $model = new \common\models\EmailTemplate;
            $model->email_type = $emailType;
            $model->title = \common\enums\EmailType::getEmailSubjects()[$emailType];
            $model->from_email = 'admin@dinein.co.uk';
            $model->from_name = 'Admin';
            $model->bcc = '';
            $model->cc = '';
            $model->content = Yii::$app->controller->renderPartial($templatePath);
            $model->language_id = Yii::$app->globalCache->getDefaultLanguageId();
        }else{
            Yii::error('Email template not found '.$templatePath);
        }
        return $model;
    }


}
