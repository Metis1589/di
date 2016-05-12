<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_export".
 *
 * @property string $id
 * @property integer $client_id
 * @property string $restaurant_chain_id
 * @property string $restaurant_group_id
 * @property integer $restaurant_id
 * @property string $email
 * @property string $filename
 * @property string $type
 * @property string $ssh_host
 * @property integer $ssh_port
 * @property string $ssh_user
 * @property string $ssh_password
 * @property string $ssh_public_key
 * @property string $ssh_private_key
 * @property string $ssh_key_passpharse
 * @property string $host_dir
 * @property string $record_type
 * @property string $create_on
 * @property string $last_update
 *
 * @property Client $client
 * @property RestaurantChain $restaurantChain
 * @property Restaurant $restaurant
 * @property RestaurantGroup $restaurantGroup
 */
class OrderExport extends \common\models\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_export';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client_id', 'restaurant_chain_id', 'restaurant_group_id', 'restaurant_id', 'ssh_port'], 'integer'],
            [['filename', 'ssh_host', 'ssh_user'], 'required'],
            [['type', 'record_type'], 'string'],
            [['create_on', 'last_update'], 'safe'],
            [['email', 'filename', 'ssh_host', 'ssh_password', 'ssh_key_passpharse'], 'string', 'max' => 100],
            [['ssh_user'], 'string', 'max' => 45],
            [['ssh_public_key', 'ssh_private_key'], 'string'],
            [['host_dir'], 'string', 'max' => 255],
            [['ssh_port'], 'default', 'value' => 22],
            [['ssh_port'], 'integer', 'max' => 65535,'min'=>0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'client_id' => 'Client ID',
            'restaurant_chain_id' => 'Restaurant Chain ID',
            'restaurant_group_id' => 'Restaurant Group ID',
            'restaurant_id' => 'Restaurant ID',
            'email' => 'Email',
            'filename' => 'Filename',
            'type' => 'Type',
            'ssh_host' => 'Ssh Host',
            'ssh_port' => 'Ssh Port',
            'ssh_user' => 'Ssh User',
            'ssh_password' => 'Ssh Password',
            'ssh_public_key' => 'Ssh Public Key',
            'ssh_private_key' => 'Ssh Private Key',
            'ssh_key_passpharse' => 'Ssh Key Passpharse',
            'host_dir' => 'Host Dir',
            'record_type' => 'Record Type',
            'create_on' => 'Create On',
            'last_update' => 'Last Update',
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
    public function getRestaurantChain()
    {
        return $this->hasOne(RestaurantChain::className(), ['id' => 'restaurant_chain_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant()
    {
        return $this->hasOne(Restaurant::className(), ['id' => 'restaurant_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantGroup()
    {
        return $this->hasOne(RestaurantGroup::className(), ['id' => 'restaurant_group_id']);
    }
}
