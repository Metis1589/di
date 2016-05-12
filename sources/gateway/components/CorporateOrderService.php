<?php

namespace gateway\components;

use common\enums\Day;
use common\enums\DefaultCompanyGroup;
use common\enums\RecordType;
use common\models\Company;
use common\models\CompanyUserGroup;
use common\models\CorporateOrder;
use common\models\ExpenseType;
use common\models\User;
use DateInterval;
use DateTime;
use gateway\models\SessionUser;
use Yii;
use yii\base\Component;

class CorporateOrderService extends Component {

    public function getActiveCompany($companyId) {
        $company = Yii::$app->globalCache->getCompany($companyId);
        $now = new DateTime();
        if (isset($company['min_order_morning_time_from']) && $company['min_order_morning_time_to']) {
            $morningSchedule = $this->getSchedule($company['min_order_morning_time_from'], $company['min_order_morning_time_to']);
            if ($now >= $morningSchedule['from'] && $now <= $morningSchedule['to']) {
                return $company;
            }
        }
        if (isset($company['min_order_evening_time_from']) && $company['min_order_evening_time_to']) {
            $eveningSchedule = $this->getSchedule($company['min_order_evening_time_from'], $company['min_order_evening_time_to']);
            if ($now >= $eveningSchedule['from'] && $now <= $eveningSchedule['to']) {
                return $company;
            }
        }
        return null;
    }

    public function getCompanyMinAmount($companyId) {
        $company = Yii::$app->globalCache->getCompany($companyId);
        $morningSchedule = $this->getSchedule($company['min_order_morning_time_from'], $company['min_order_morning_time_to']);
        $eveningSchedule = $this->getSchedule($company['min_order_evening_time_from'], $company['min_order_evening_time_to']);
        $now = new DateTime();
        if ($now >= $morningSchedule['from'] && $now <= $morningSchedule['to']) {
            return $company['min_order_morning_amount'];
        }
        if ($now >= $eveningSchedule['from'] && $now <= $eveningSchedule['to']) {
            return $company['min_order_evening_amount'];
        }
        return null;
    }

    public function getUserGroup($companyId, $userGroupId) {

        $company = Yii::$app->globalCache->getCompany($companyId);

        foreach($company['companyUserGroups'] as  $group) {
            if ($group['id'] == $userGroupId) {
                $this->filterGroupExpenseTypes($group);
                return $group;
            }
        }
        return null;
    }

    public function getUserGroupByName($companyId, $userGroupName) {

        $company = Yii::$app->globalCache->getCompany($companyId);

        foreach($company['companyUserGroups'] as  $group) {
            if ($group['name'] == $userGroupName) {
                $this->filterGroupExpenseTypes($userGroup);
                return $group;
            }
        }
        return null;
    }

    public function getUserGroupForCorpUser($email, $clientId) {
        $user = User::find()->where(['username' => $email, 'client_id' => $clientId, 'record_type' => RecordType::Active])->one();

        $userGroup = null;
        if (isset($user) && isset($user->companyUserGroup)) {
            return $this->getUserGroup(Yii::$app->user->identity->companyUserGroup->company_id, $user->company_user_group_id);
        }
        return null;
    }

    public function setCurrentUserAsCorp($expense_type_id) {
        $userGroup = null;

      //  $company = Yii::$app->globalCache->getCompany(Yii::$app->user->identity->companyUserGroup->company_id);

        /** @var SessionUser $session_user */
        $session_user = Yii::$app->userCache->getUser();

        $userGroup = $this->getUserGroup(Yii::$app->user->identity->companyUserGroup->company_id, Yii::$app->user->identity->company_user_group_id);
        if (empty($expense_type_id)) {
            $session_user->expense_type = $userGroup['activeExpenseTypes'][0];
        } else {
            foreach($userGroup['activeExpenseTypes'] as $e) {
                if ($e['id'] == $expense_type_id) {
                    $session_user->expense_type = $e;
                    break;
                }
            }
        }

        $session_user->corp_users = [];
        $corporateOrder = new CorporateOrder();
        $session_user->corp_users[] = &$corporateOrder;
        $currentUser = Yii::$app->user->identity;

        $corporateOrder->first_name = $currentUser->first_name;
        $corporateOrder->last_name = $currentUser->last_name;
        $corporateOrder->email = $currentUser->username;
        $corporateOrder->company = Yii::$app->user->identity->company->name;
        $corporateOrder->user_id = $currentUser->id;
        $corporateOrder->expense_type_data = serialize($session_user->expense_type);

        $this->filterGroupExpenseTypes($userGroup);

        $corporateOrder->company_user_group_data = serialize($userGroup);

        Yii::$app->userCache->setUser($session_user);
    }

    public function setCorporateOrderUser($clientKey, $firstName, $lastName, $email, $company, $index) {
        $client = Yii::$app->globalCache->getClient($clientKey);

        /** @var SessionUser $session_user */
        $session_user = Yii::$app->userCache->getUser();

        if ($index == -1) {
            $corporateOrder = new CorporateOrder();
            $session_user->corp_users[] = &$corporateOrder;
        } else {
            $corporateOrder = &$session_user->corp_users[$index];
        }

        $corporateOrder->first_name = $firstName;
        $corporateOrder->last_name = $lastName;
        $corporateOrder->email = $email;
        $corporateOrder->company = $company;
        $user = User::find()->where(['username' => $email, 'client_id' => $client['id'], 'record_type' => RecordType::Active])->one();
        if (isset($user)) {
            $corporateOrder->user_id = $user->id;
        }

        $userGroup = $this->getUserGroupForCorpUser($email, $client['id']);

        if (!isset($userGroup)) {
            $userGroup = $this->getUserGroupByName(Yii::$app->user->identity->companyUserGroup->company_id,DefaultCompanyGroup::DefaultExternal);
            $corporateOrder->expense_type_data = serialize($userGroup['activeExpenseTypes'][0]);
        } else {
            $corporateOrder->expense_type_data = serialize($session_user->expense_type);
        }

        $corporateOrder->company_user_group_data = serialize($userGroup);

        Yii::$app->userCache->setUser($session_user);
    }

    public function getApiResponse() {

        /** @var SessionUser $session_user */
        $session_user = Yii::$app->userCache->getUser();

        $company = Yii::$app->globalCache->getCompany(Yii::$app->user->identity->companyUserGroup->company_id);

        foreach($session_user->corp_users as &$corpUser) {
            $userGroup = null;
           // unset($corpUser->company_data);
            $userGroup = unserialize($corpUser->company_user_group_data);
            $userGroup = $this->getUserGroup($userGroup['company_id'], $userGroup['id']);
            $corpUser->company_user_group_data = serialize($userGroup);

          //  unset($corpUser->company_user_group_data);

            if (!empty($corpUser['code_data'])) {
                $code = unserialize($corpUser['code_data']);
            }

            $expense_type_id = unserialize($corpUser->expense_type_data)['id'];

            $expense_type = null;

            foreach($userGroup['activeExpenseTypes'] as $et) {
                if ($et['id'] == $expense_type_id) {
                    $expense_type = $et;
                    break;
                }
            }

            $users[] = [
                'corp_user' => $corpUser,
                'user_group' => $userGroup,
                'expense_type' => $expense_type,
                'code_id' => isset($code) ? $code['id'] : null
            ];
        }

        Yii::$app->userCache->setUser($session_user);

        unset($company['companyUserGroups']);

        return [
            'company' => $company,
            'users' => $users,
            'has_inntouch' => (!Yii::$app->user->isGuest && isset(Yii::$app->user->identity->client->has_inntouch)) ? Yii::$app->user->identity->client->has_inntouch : false,
            'selectedExpenseType' => $session_user->expense_type,
            'userGroup' => $this->getUserGroup(Yii::$app->user->identity->companyUserGroup->company_id, Yii::$app->user->identity->company_user_group_id),
            'currencySymbol' => Yii::$app->globalCache->getRestaurant($session_user->client_id, $session_user->restaurant_id)['currency']['symbol']
        ];
    }

    private function filterGroupExpenseTypes(&$userGroup) {
        if (isset($userGroup['activeExpenseTypes'])) {
            foreach($userGroup['activeExpenseTypes'] as $key => $expenseType) {
                if (!$this->isExpenseTypeAvaiable($expenseType)) {
                    unset($userGroup['activeExpenseTypes'][$key]);
                }
            }
        }

    }

    private function isCompanyAvaiable($company) {
        $schedules = $this->getScheduleByCurrentDate($expenseType);
        foreach($schedules as $schedule) {
            $from = $schedule['from'];
            $to = $schedule['to'];
            if ($to < $from) {
                $to = $schedule['to']->add(new DateInterval('P1D'));
            }
            $now = new DateTime();
            if ($now >= $from && $now <= $to) {
                return true;
            }
        }
        return false;
    }

    private function isExpenseTypeAvaiable($expenseType) {
        $schedules = $this->getScheduleByCurrentDate($expenseType);
        foreach($schedules as $schedule) {
            $from = $schedule['from'];
            $to = $schedule['to'];
            if ($to < $from) {
                $to = $schedule['to']->add(new DateInterval('P1D'));
            }
            $now = new DateTime();
            if ($now >= $from && $now <= $to) {
                return true;
            }
        }
        return false;
    }

    private function getScheduleByCurrentDate($expenseType) {
        $todayDay = (new DateTime('today'))->format('w');
        $yesterdayDay = (new DateTime('yesterday'))->format('w');
        $result = [];
        foreach($expenseType['activeExpenseTypeSchedules'] as $schedule) {
            $scheduleDay = Day::getDay($schedule['day']);
            if ($scheduleDay == $todayDay) {
                $schedule['from'] = new DateTime('today ' . $schedule['from']);
                $schedule['to'] = new DateTime('today ' . $schedule['to']);
                $result[] = $schedule;
            } else if ($scheduleDay == $yesterdayDay) {
                $schedule['from'] = new DateTime('yesterday ' . $schedule['from']);
                $schedule['to'] = new DateTime('yesterday ' . $schedule['to']);
                $result[] = $schedule;
            }
        }
        return $result;
    }

    private function getSchedule($from, $to) {
        $schedule =[];
        $schedule['from'] = new DateTime('today ' . $from);
        $schedule['to'] = new DateTime('today ' . $to);

        if ($schedule['from'] > $schedule['to']) {
            $schedule['to'] = new DateTime('yesterday ' . $to);
        }

        return $schedule;
    }

}
