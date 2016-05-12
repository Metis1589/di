<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 5/4/2015
 * Time: 7:05 PM
 */

namespace gateway\modules\v1\services;

use common\enums\RecordType;
use common\enums\VoucherGenerateBy;
use common\models\Client;
use common\models\Order;
use common\models\Restaurant;
use common\models\User;
use common\models\Voucher;
use Yii;
use yii\base\Exception;

class LoyaltyService {

    /**
     * @param Order $order
     * @return mixed
     */
    public static function calculateOrderLoyaltyPoints($order) {
        return (int)round($order->total * $order->restaurant->client->loyalty_points_per_currency, 0);
    }

    /**
     * Generate loyalty points voucher
     *
     * @param $user_id
     * @return bool
     */
    public static function generateVoucherByUser($user_id) {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $user = User::findOne($user_id);
            $client = $user->client;
            $voucher = $client->voucher;
            if ($user->loyalty_points < $client->loyalty_points_per_voucher) {
                throw new Exception('Can not generate voucher: not enough loyality points');
            }

            if (!isset($voucher)) {
                throw new Exception('Client has no voucher');
            }

            $user_voucher = new Voucher();
            $user_voucher->user_id = $user->id;
            $user_voucher->code = $voucher->code;
            $user_voucher->category = $voucher->category;
            $user_voucher->discount_value = $voucher->discount_value;
            $user_voucher->discount_type = $voucher->discount_type;
            $user_voucher->promotion_type = $voucher->promotion_type;
            $user_voucher->start_date = $voucher->end_date;
            $user_voucher->value_type = $voucher->value_type;
            $user_voucher->price_value = $voucher->price_value;
            $user_voucher->item_quantity = $voucher->item_quantity;
            $user_voucher->description = $voucher->description;
            $user_voucher->order_after = $voucher->order_after;
            $user_voucher->max_times_per_user = $voucher->max_times_per_user;
            $user_voucher->generate_by = VoucherGenerateBy::M;

            if (!$user_voucher->save()) {
                throw new Exception('API_GENERATE_VOUCHER__VOUCHER_SAVE_ERROR');
            }

            $user->loyalty_points -= $client->loyalty_points_per_voucher;
            if (!$user->save()) {
                throw new Exception('API_GENERATE_VOUCHER__USER_SAVE_ERROR');
            }

            $transaction->commit();
            return true;
        }
        catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }

    }

}