<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "affiliate".
 *
 * @property string $id
 * @property string $name
 * @property string $code
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Order[] $orders
 * @property User[] $users
 */
class Affiliate extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'affiliate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code'], 'required'],
            [['record_type'], 'string'],
            [['create_on', 'last_update'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'code' => Yii::t('app', 'Code'),
            'record_type' => Yii::t('app', 'Record Type'),
            'create_on' => Yii::t('app', 'Create On'),
            'last_update' => Yii::t('app', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['affiliate_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['affiliate_id' => 'id']);
    }
}
