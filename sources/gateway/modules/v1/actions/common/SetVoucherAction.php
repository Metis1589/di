<?php
namespace gateway\modules\v1\actions\common;

use common\components\voucherServices\EagleEyeValidationService;
use common\enums\Day;
use common\enums\DeliveryType;
use common\enums\RecordType;
use common\models\VoucherUseHistory;
use DateInterval;
use DateTime;
use ErrorException;
use gateway\models\SessionUser;
use gateway\modules\v1\components\OrderHelper;
use gateway\modules\v1\forms\common\SetVoucherForm;
use gateway\modules\v1\components\PostApiAction;
use Exception;
use gateway\modules\v1\services\VoucherCalculator;
use Yii;

class SetVoucherAction extends PostApiAction
{
	/**
	 * Creates request form used to validate request parameters.
	 *
	 * @return SetVoucherForm
	 */
	protected function createRequestForm()
	{
		return new SetVoucherForm();
	}

	/**
	 * Set voucher.
	 *
	 * @param SetVoucherForm $requestForm Request form class instance.
	 *
	 * @return string|boolean
	 */
	protected function getResponseData($requestForm)
	{
		try {
            /** @var SessionUser $session_user */
            $session_user = Yii::$app->userCache->getUser();

            $restaurant = Yii::$app->globalCache->getRestaurant($requestForm->client_key, $session_user->restaurant_id);

            if ($requestForm->code == null) {
                VoucherCalculator::clearVoucher($session_user);
            }
            else {
                $voucher = Yii::$app->globalCache->getVoucher($requestForm->client_key, $requestForm->code);

                if ($voucher == null || $voucher['record_type'] != RecordType::Active) {
                    VoucherCalculator::clearVoucher($session_user);
                    Yii::$app->userCache->setUser($session_user);
                    throw new ErrorException('Invalid Voucher Code');
                }

                // validate voucher assignment
                if ($voucher['user_id']) {
                    if (Yii::$app->user->isGuest || Yii::$app->user->identity->id != $voucher['user_id']) {
                        VoucherCalculator::clearVoucher($session_user);
                        Yii::$app->userCache->setUser($session_user);
                        throw new ErrorException('Voucher is not available for user');
                    }
                }
                else if ($voucher['restaurant_id']) {
                    if ($session_user->restaurant_id != $voucher['restaurant_id']) {
                        VoucherCalculator::clearVoucher($session_user);
                        Yii::$app->userCache->setUser($session_user);
                        throw new ErrorException('Voucher is not available for restaurant');
                    }
                }
                else if ($voucher['restaurant_group_id']) {
                    if (!in_array($voucher['restaurant_group_id'], $restaurant['parents']['restaurant_group_ids'])) {
                        VoucherCalculator::clearVoucher($session_user);
                        Yii::$app->userCache->setUser($session_user);
                        throw new ErrorException('Voucher is not available for group');
                    }
                }
                else if ($voucher['restaurant_chain_id']) {
                    if ($restaurant['parents']['restaurant_chain_id'] != $voucher['restaurant_chain_id']) {
                        VoucherCalculator::clearVoucher($session_user);
                        Yii::$app->userCache->setUser($session_user);
                        throw new ErrorException('Voucher is not available for chain');
                    }
                }

                // validate schedule
                if (!$this->isVoucherAvailableForTime($voucher)) {
                    VoucherCalculator::clearVoucher($session_user);
                    Yii::$app->userCache->setUser($session_user);
                    throw new ErrorException('Voucher is not currently available');
                }

                $voucher_use_history_count = VoucherUseHistory::find()->where(['voucher_id' => $voucher['id']])->count();
                if ($voucher_use_history_count >= $voucher['max_times_per_user']) {
                    VoucherCalculator::clearVoucher($session_user);
                    Yii::$app->userCache->setUser($session_user);
                    throw new ErrorException('Invalid Voucher Code');
                }

                if (isset($voucher['validation_service'])) {
                    if (!EagleEyeValidationService::verify(Yii::$app->globalCache->getClient($requestForm->client_key), $requestForm->code)) {
                        VoucherCalculator::clearVoucher($session_user);
                        Yii::$app->userCache->setUser($session_user);
                        throw new ErrorException('Invalid Voucher Code');
                    }
                }

                $session_user->voucher = $voucher;
                $session_user->voucher_code = $requestForm->code;
            }

            $voucher_error = null;
            if (!VoucherCalculator::calculateDiscountBySessionUser($session_user, $voucher_error)) {
                throw new ErrorException($voucher_error);
            }

            Yii::$app->userCache->setUser($session_user);

            return OrderHelper::getOrderResponse($requestForm->client_key);
		}
		catch (Exception $ex) {
			return $ex;
		}
	}

    /**
     * Is voucher available for current time
     *
     * @param $voucher
     * @return bool
     */
    private function isVoucherAvailableForTime($voucher) {

        $now = new DateTime();

        $from = new DateTime($voucher['start_date']);
        $to = (new DateTime($voucher['end_date']))->add(new DateInterval('P1D'));

        if ($now < $from || $now > $to) {
            return false;
        }

        if ($voucher['voucherSchedules']) {
            $schedules = $this->getScheduleByCurrentDate($voucher);

            foreach ($schedules as $schedule) {
                $from = $schedule['from'];
                $to = $schedule['to'];
                if ($to < $from) {
                    $to = $schedule['to']->add(new DateInterval('P1D'));
                }

                if ($now >= $from && $now < $to) {
                    return true;
                }
            }
            return false;
        }

        return true;
    }

    /**
     * Get voucher schedule for current day
     *
     * @param $voucher
     * @return array
     */
    private function getScheduleByCurrentDate($voucher) {
        $todayDay = (new DateTime('today'));
        $yesterdayDay = (new DateTime($todayDay->format('Y-m-d')))->sub(new DateInterval('P1D'));
        $schedules = $voucher['voucherSchedules'];

        $result =[];

        foreach($schedules as $schedule) {
            $scheduleDay = Day::getDay($schedule['day']);
            if ($scheduleDay == $todayDay->format('w')) {
                $schedule['from'] = new DateTime($todayDay->format('Y-m-d') . ' ' . $schedule['from']);
                $schedule['to'] = new DateTime($todayDay->format('Y-m-d') . ' ' . $schedule['to']);
                $result[] = $schedule;
            } else if ($scheduleDay == $yesterdayDay->format('w')) {
                $schedule['from'] = new DateTime($yesterdayDay->format('Y-m-d') . ' ' . $schedule['from']);
                $schedule['to'] = new DateTime($yesterdayDay->format('Y-m-d') . ' ' . $schedule['to']);
                $result[] = $schedule;
            }
        }

        return $result;
    }
}