<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sms_cost".
 *
 * @property string $id
 * @property double $cost
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 */
class SmsCost extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sms_cost';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cost'], 'required'],
            [['cost'], 'number'],
            [['record_type'], 'string'],
            [['create_on', 'last_update'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'cost' => Yii::t('app', 'Cost'),
            'record_type' => Yii::t('app', 'Record Type'),
            'create_on' => Yii::t('app', 'Create On'),
            'last_update' => Yii::t('app', 'Last Update'),
        ];
    }
}
