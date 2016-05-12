<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "corporate_order".
 *
 * @property string $id
 * @property integer $order_id
 * @property string $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $company
 * @property string $code_data
 * @property string $expense_type_data
 * @property string $company_user_group_data
 * @property double $allocation
 * @property string $comment
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Order $order
 * @property User $user
 * @property Company $company0
 */
class CorporateOrderBase extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'corporate_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'first_name', 'last_name', 'email', 'company_user_group_data', 'expense_type_data', 'allocation', 'company'], 'required'],
            [['order_id', 'user_id'], 'integer'],
            [['code_data', 'company_user_group_data', 'record_type'], 'string'],
            [['allocation'], 'number'],
            [['create_on', 'last_update'], 'safe'],
            [['first_name', 'last_name', 'email'], 'string', 'max' => 255],
            [['comment'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('label', 'ID'),
            'order_id' => Yii::t('label', 'Order ID'),
            'user_id' => Yii::t('label', 'User ID'),
            'first_name' => Yii::t('label', 'First Name'),
            'last_name' => Yii::t('label', 'Last Name'),
            'email' => Yii::t('label', 'Email'),
            'code_data' => Yii::t('label', 'Code Data'),
            'company_user_group_data' => Yii::t('label', 'Company User Group Data'),
            'allocation' => Yii::t('label', 'Allocation'),
            'comment' => Yii::t('label', 'Comment'),
            'record_type' => Yii::t('label', 'Record Type'),
            'create_on' => Yii::t('label', 'Create On'),
            'last_update' => Yii::t('label', 'Last Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany0()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }
}
