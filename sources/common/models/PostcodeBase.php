<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "postcode".
 *
 * @property string $id
 * @property string $postcode
 * @property double $latitude
 * @property double $longitude
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property PostcodeBlacklist[] $postcodeBlacklists
 */
class PostcodeBase extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'postcode';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['postcode', 'latitude', 'longitude'], 'required'],
            [['latitude', 'longitude'], 'number'],
            [['record_type'], 'string'],
            [['create_on', 'last_update'], 'safe'],
            [['postcode'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('label', 'ID'),
            'postcode' => Yii::t('label', 'Postcode'),
            'latitude' => Yii::t('label', 'Latitude'),
            'longitude' => Yii::t('label', 'Longitude'),
            'record_type' => Yii::t('label', 'Record Type'),
            'create_on' => Yii::t('label', 'Create On'),
            'last_update' => Yii::t('label', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostcodeBlacklists()
    {
        return $this->hasMany(PostcodeBlacklist::className(), ['postcode_id' => 'id']);
    }
}
