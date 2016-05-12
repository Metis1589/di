<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cache_queue".
 *
 * @property string $id
 * @property string $action
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 */
class CacheQueue extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cache_queue';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['action'], 'required'],
            [['action', 'record_type'], 'string'],
            [['create_on', 'last_update'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('label', 'ID'),
            'action' => Yii::t('label', 'Action'),
            'record_type' => Yii::t('label', 'Record Type'),
            'create_on' => Yii::t('label', 'Create On'),
            'last_update' => Yii::t('label', 'Last Update'),
        ];
    }
}
