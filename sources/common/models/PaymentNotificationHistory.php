<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "payment_notification_history".
 *
 * @property string $psp_reference
 * @property string $merchant_reference
 * @property string $original_reference
 * @property string $event_code
 * @property string $merchant_account_code
 * @property string $event_date
 * @property integer $success
 * @property string $operations
 * @property string $reason
 * @property double $amount
 * @property double $value
 * @property integer $live
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 */
class PaymentNotificationHistory extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payment_notification_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['psp_reference', 'event_code', 'event_date', 'success', 'live'], 'required'],
            [['event_date', 'create_on', 'last_update'], 'safe'],
            [['success', 'live'], 'integer'],
            [['amount', 'value'], 'number'],
            [['record_type'], 'string'],
            [['psp_reference', 'merchant_account_code'], 'string', 'max' => 100],
            [['merchant_reference', 'original_reference', 'operations', 'reason'], 'string', 'max' => 250],
            [['event_code'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'psp_reference' => Yii::t('label', 'Psp Reference'),
            'merchant_reference' => Yii::t('label', 'Merchant Reference'),
            'original_reference' => Yii::t('label', 'Original Reference'),
            'event_code' => Yii::t('label', 'Event Code'),
            'merchant_account_code' => Yii::t('label', 'Merchant Account Code'),
            'event_date' => Yii::t('label', 'Event Date'),
            'success' => Yii::t('label', 'Success'),
            'operations' => Yii::t('label', 'Operations'),
            'reason' => Yii::t('label', 'Reason'),
            'amount' => Yii::t('label', 'Amount'),
            'value' => Yii::t('label', 'Value'),
            'live' => Yii::t('label', 'Live'),
            'record_type' => Yii::t('label', 'Record Type'),
            'create_on' => Yii::t('label', 'Create On'),
            'last_update' => Yii::t('label', 'Last Update'),
        ];
    }
}
