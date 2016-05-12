<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rating".
 *
 * @property string $review_id
 * @property integer $factor
 * @property integer $rating
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Feedback $review
 */
class Rating extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rating';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['review_id'], 'required'],
            [['review_id', 'factor', 'rating'], 'integer'],
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
            'review_id' => Yii::t('app', 'Review ID'),
            'factor' => Yii::t('app', 'Factor'),
            'rating' => Yii::t('app', 'Rating'),
            'record_type' => Yii::t('app', 'Record Type'),
            'create_on' => Yii::t('app', 'Create On'),
            'last_update' => Yii::t('app', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReview()
    {
        return $this->hasOne(Feedback::className(), ['id' => 'review_id']);
    }
}
