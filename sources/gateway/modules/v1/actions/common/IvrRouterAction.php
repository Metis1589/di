<?php

namespace gateway\modules\v1\actions\common;

use common\enums\DeliveryProvider;
use gateway\modules\v1\forms\common\IvrRouterForm;
use gateway\modules\v1\components\PostApiAction;
use common\enums\IvrMessageType;
use console\models\TwilioService;
use common\enums\DeliveryType;
use common\enums\UserType;
use common\enums\RestaurantContactOrderType;
use common\models\OrderContactHistory;
use common\enums\OrderStatus;
use Yii;

class IvrRouterAction extends PostApiAction {
    private $_twiml;
    private $_session;
    private $_digits;
    private $_sid;

    protected static $_defaultOutputFormat = 'xml';

    protected function createRequestForm()
    {
        return new IvrRouterForm;
    }

    private function getVar($varName, $default = false)
    {
        return $this->_session->get($varName) ?: $default;
    }

    protected function getResponseData($requestForm)
    {
        $get            = $_GET;
        $this->_twiml   = Yii::$app->twilio->getResponse();
        $this->_session = Yii::$app->session;
        $this->_sid     = isset($requestForm->CallSid) ? $requestForm->CallSid : (isset($requestForm->Sid) ? $requestForm->Sid : isset($get['CallSid']) ? $get['CallSid'] : '');
        $this->_session->setId($this->_sid);
        Yii::$app->globalCache->setIvrSession($this->_sid, $requestForm->attributes);
        Yii::$app->globalCache->setIvrSession($this->_sid, $get);

        $dataSource    = Yii::$app->globalCache->getIvrSession($this->_sid);
        $this->_initSessionData($dataSource);
        $this->_digits = $this->getVar('Digits');
        $press_button  = Yii::$app->request->get('press_button');

        if ($this->_sid && $this->getVar('orderId')) {
            OrderContactHistory::addRecord(
                $this->getVar('orderId'),
                RestaurantContactOrderType::Ivr,
                Yii::$app->request->get('Status') ?: $this->getVar('CallStatus', 'received-callback'),
                $this->getVar('firstName', UserType::UNAUTHORIZED),
                $this->getVar('To'),
                $this->getVar('CallSid', $this->getVar('Sid', 0)),
                1,
                UserType::UNAUTHORIZED,
                $this->getVar('Duration', 0),
                $this->getVar('Price', 0),
                $this->getVar('PriceUnit', $this->getVar('unit_price', '$'))
            );
        }

        if (Yii::$app->request->isPost) {
            $this->_digits = Yii::$app->request->post('Digits');

            if (isset($this->_digits)) {
                if (isset($press_button) && is_numeric($press_button) ) {
                    $this->_router($press_button);
                } else {
                    if ($press_button === 'default') {
                        $this->_menu($this->_digits);
                    } else {
                        $value = $press_button == 'confirmation' ? $press_button : $this->_digits;
                        $this->_router($value);
                    }
                }
            } else {
                $this->_mainMenu();
            }
        } else {
            $this->_menu($press_button);
        }
    }

    /**
     * Call IVR router
     *
     * @param integer|boolean $buttonId Router button id (/api/asapR..ry/press{$id}/menuRouter.php at old version)
     */
    private function _router($buttonId = false)
    {
        switch ($buttonId) {
            case '1': $this->_routeDigit1(); break;
            case '3': $this->_routeDigit3(); break;
            case '5': $this->_routeDigit5(); break;
            case '7': $this->_routeDigit7(); break;
            case 'confirmation': $this->_routeDigitConfirmation(); break;
            default : $this->_defaultRoute();
        }
        header('Content-Type: text/xml');
        print $this->_twiml;
        die;
    }

    /**
     * Call menu
     *
     * @param integer $press_button Router button id (/api/asapR..ry/press{$id}/menu.php at old version)
     */
    private function _menu($press_button)
    {
        switch ($press_button) {
            case '1': $this->_menuButton1(); break;
            case '3': $this->_menuButton3(); break;
            case '5': $this->_menuButton5(); break;
            case '7': $this->_menuButton7(); break;
            default : $this->_mainMenu();
        }
    }

    /**
     * Root menu router
     *
     */
    private function _defaultRoute()
    {
        switch ($this->_digits) {
            case '1':
                $serviceType  = $this->getVar('serviceType');
                $deliveryType = $this->getVar('deliveryType');
                if ($serviceType == 'restaurant' && $deliveryType == DeliveryType::DeliveryAsap) {
                    $this->_pressButton(3);
                } else {
                    $this->_pressButton(1);
                }
                break;
            case '3': $this->_pressButton(3);         break;
            case '5': $this->_pressButton(5);         break;
            case '7': $this->_pressButton(7);         break;
            default : $this->_pressButton('default');
        }
    }

    /**
     * Root menu router
     *
     */
    private function _routeDigit1()
    {
        switch ($this->_digits) {
            case '*': $this->_pressButton('default'); break;
            default : $this->_pressButton(1);
        }
    }

    /**
     * Menu button 3 router
     *
     */
    private function _routeDigit3()
    {
        $readyTime         = self::militaryToHumanTime($this->getVar('readyByTime'));
        $militaryTimeRegex = '/([01][0-9]|2[0-3])[0-5][0-9]/';

        switch ($this->_digits) {
            case '3':
                $this->_pressButton(3);
                header('Content-Type: text/xml');
                print $this->_twiml;
                exit;
                break;
            case '9':
                $this->_pressButton(3);
                header('Content-Type: text/xml');
                print $this->_twiml;
                exit;
                break;
            case '*':
                $this->_pressButton('default');
                header('Content-Type: text/xml');
                print $this->_twiml;
                exit;
                break;
        }

        // dinein
        if ($this->getVar('serviceType') == DeliveryProvider::Client) {
            $subtext_1 = TwilioService::getMessage(
                IvrMessageType::Router3SubText1Dinein,
                [ '{{readyTime}}' => $readyTime ]
            );
            $subtext_2 = TwilioService::getMessage(
                IvrMessageType::Router3SubText2Dinein,
                [  ]
            );
        }

        // restaurant
        if ($this->getVar('serviceType') == DeliveryProvider::Restaurant) {
            $subtext_1 = TwilioService::getMessage(
                IvrMessageType::Router3SubText1Restaurant,
                [ '{{readyTime}}' => $readyTime ]
            );
            $subtext_2 = TwilioService::getMessage(
                IvrMessageType::Router3SubText2Restaurant,
                [  ]
            );
        }

        // collection
        if (in_array($this->getVar('delivery_type'), [ DeliveryType::CollectionAsap, DeliveryType::CollectionLater ])) {
            $subtext_1 = TwilioService::getMessage(
                IvrMessageType::Router3SubText1Collection,
                [ '{{readyTime}}' => $readyTime ]
            );
            $subtext_2 = TwilioService::getMessage(
                IvrMessageType::Router3SubText2Collection,
                [  ]
            );
        }

        if (!preg_match($militaryTimeRegex, $this->_digits)) {
            // time format is wrong
            $message = TwilioService::getMessage(
                IvrMessageType::Router3WrongFormat,
                [ '{{message}}' => $subtext_1 ]
            );
            TwilioService::say($this->_twiml, [ $message ], 5, '#' );

        } else if (strtotime($this->_digits) < strtotime($readyTime)) {
            // time entered < order time
            $message = TwilioService::getMessage(
                IvrMessageType::Router3TimeLessThanOrderTime,
                [
                    '{{time}}'    => $this->militaryToHumanTime($this->_digits),
                    '{{message}}' => $subtext_1
                ]
            );
            TwilioService::say($this->_twiml, [ $message ]);

        } else {
            Yii::info('Digits: ' . $this->_digits);
            // confirm, reenter time, or press star
            $this->_session->set('updatedReadyByTime', date('Y-m-d', time()) . ' ' . date('H:i:s', strtotime($this->_digits)));
            $this->_session->set('readyByTime', $this->getVar('updatedReadyByTime'));
            Yii::$app->globalCache->setIvrSession($this->_sid, $this->_session);
            Yii::info('readyByTime: ' . $this->getVar('readyByTime'));
            $message = TwilioService::getMessage(
                IvrMessageType::ConfirmReenterTimePressStar,
                [
                    '{{message}}' => $subtext_2,
                    '{{time}}'    => $this->militaryToHumanTime($this->_digits)
                ]
            );

            // Send IVR text to twilio and callBack url to router
            TwilioService::constructResponse( $this->_twiml, $this->_getUrl('confirmation'), [ $message ] );
        }
    }

    /**
     * Menu button 5 router
     *
     */
    private function _routeDigit5()
    {
        $readyTime = self::militaryToHumanTime($this->getVar('readyByTime'));
        $postCode  = $this->getVar('postcode');
        $est       = true;

        // dinein
        if ($this->getVar('serviceType') == DeliveryProvider::Client) {
            $est     = false;
            $message = TwilioService::getMessage(
                IvrMessageType::Router5AdditionalMessage1,
                [ '{{readyTime}}' => $readyTime ]
            );
        }

        // restaurant
        if ($this->getVar('serviceType') == DeliveryProvider::Restaurant) {
            $message = TwilioService::getMessage(
                IvrMessageType::Router5AdditionalMessage2,
                [ ]
            );
        }

        // collection
        if (in_array($this->getVar('delivery_type'), [ DeliveryType::CollectionAsap, DeliveryType::CollectionLater ])) {
            $message = TwilioService::getMessage(
                IvrMessageType::Router5AdditionalMessage3,
                [ '{{readyTime}}' => $readyTime ]
            );
        }

        switch ($this->_digits) {
            case '1':
                $this->_pressButton(!$est ? 1 : 3);
                break;
            case '2':
                $this->_pressButton(5);
                break;
            case '3':
                $message = TwilioService::getMessage(
                    IvrMessageType::Router5OrderId,
                    [
                        '{{orderId}}' => $this->getVar('orderId'),
                        '{{message}}' => $message
                    ]
                );
                TwilioService::constructResponse( $this->_twiml, $this->_getUrl(5), [ $message ] );
                break;
            case '4':
                $firstNameSpaces = self::spacesBetweenCharacters($this->getVar('firstName'));
                $lastNameSpaces  = self::spacesBetweenCharacters($this->getVar('lastName'));
                $message         = TwilioService::getMessage(
                    IvrMessageType::Router5CustomerName,
                    [
                        '{{customerFirstName}}' => $this->getVar('firstName'),
                        '{{customerLastName}}'  => $this->getVar('lastName'),
                        '{{firstNameSpaces}}'   => $firstNameSpaces,
                        '{{lastNameSpaces}}'    => $lastNameSpaces,
                        '{{message}}'           => $message
                    ]
                );
                TwilioService::constructResponse( $this->_twiml, $this->_getUrl(5), [ $message ] );
                break;
            case '5':
                $message = TwilioService::getMessage(
                    IvrMessageType::Router5OrderAddress,
                    [
                        '{{address}}'     => $this->getVar('address'),
                        '{{city}}'        => $this->getVar('city'),
                        '{{postal_code}}' => $postCode,
                        '{{message}}'     => $message
                    ]
                );
                TwilioService::constructResponse( $this->_twiml, $this->_getUrl(5), [ $message ] );
                break;
            case '6':
                $itemsList = $this->getVar('orderItems');
                $message   = TwilioService::getMessage(
                    IvrMessageType::Router5TotalPrice,
                    [
                        '{{readyTime}}'  => $readyTime,
                        '{{pound}}'      => $this->getVar('pound'),
                        '{{pence}}'      => $this->getVar('pence'),
                        '{{itemCount}}'  => $this->getVar('itemCount'),
                        '{{orderItems}}' => $itemsList,
                        '{{message}}'    => $message
                    ]
                );
                TwilioService::constructResponse( $this->_twiml, $this->_getUrl(5), [ $message ] );
                break;
            case '*':
                $this->_pressButton('default');
                break;
            default :
                $this->_pressButton(5);
                break;
        }
    }

    /**
     * Menu button 7 router
     *
     */
    private function _routeDigit7()
    {
        switch ($this->_digits) {
            case '7':
                $order = \common\models\Order::findOne($this->getVar('orderId'));
                \gateway\modules\v1\services\OrderService::changeOrderStatus(
                    $order,
                    OrderStatus::OrderCancelled,
                    UserType::Admin
                );

                OrderContactHistory::addRecord(
                    $this->getVar('orderId'),
                    RestaurantContactOrderType::Ivr,
                    OrderStatus::OrderCancelled,
                    $this->getVar('firstName', UserType::UNAUTHORIZED),
                    $this->getVar('To'),
                    $this->getVar('CallSid', $this->getVar('Sid', 0)),
                    1,
                    UserType::UNAUTHORIZED,
                    $this->getVar('Duration', 0),
                    $this->getVar('Price', 0),
                    $this->getVar('PriceUnit', $this->getVar('unit_price', '$'))
                );

                $message = TwilioService::getMessage(
                    IvrMessageType::Router7OrderCancelled,
                    [
                        '{{orderId}}' => $this->spacesBetweenCharacters($this->getVar('orderId'))
                    ]
                );
                $this->_twiml->say($message);
                $this->_twiml->pause([ 'length' => 1 ]);
                $this->_twiml->say(TwilioService::getMessage(IvrMessageType::Router7OrderCancelledCallEnd, []));
                break;
            case '*':
                $this->_pressButton('default');
                break;
            default:
                $this->_pressButton(7);
                break;
        }
    }

    /**
     * Menu button 3 confirmation
     *
     */
    private function _routeDigitConfirmation()
    {
        $est = false;
        $readyDateTime = $this->getVar('updatedReadyByTime');

        // dinein
        if ($this->getVar('serviceType') == DeliveryProvider::Client) {
            $message = TwilioService::getMessage(
                IvrMessageType::RouterConfirmationDinein,
                [  ]
            );
        }

        // restaurant
        if ($this->getVar('serviceType') == DeliveryProvider::Restaurant) {
            if (in_array($this->getVar('delivery_type'), [ DeliveryType::DeliveryAsap, DeliveryType::DeliveryLater ])) {
                $est = true;
            }

            $message = TwilioService::getMessage(
                IvrMessageType::RouterConfirmationRestaurant,
                [  ]
            );
        }

        // collection
        if (in_array($this->getVar('delivery_type'), [ DeliveryType::CollectionAsap, DeliveryType::CollectionLater ])) {
            $est     = true;
            $message = TwilioService::getMessage(
                IvrMessageType::RouterConfirmationCollection,
                [  ]
            );
        }

        switch ($this->_digits) {
            case '1':
                $order = \common\models\Order::findOne($this->getVar('orderId'));

                if ($est) {
                    if ($order->status != OrderStatus::EstimatedDeliveryTime) {
                        Yii::info('~$readyDateTime 1: ' . $readyDateTime);
                        \gateway\modules\v1\services\OrderService::changeOrderStatus(
                            $order,
                            OrderStatus::EstimatedDeliveryTime,
                            UserType::Admin,
                            null,
                            $readyDateTime
                        );

                        OrderContactHistory::addRecord(
                            $this->getVar('orderId'),
                            RestaurantContactOrderType::Ivr,
                            OrderStatus::EstimatedDeliveryTime,
                            $this->getVar('firstName', UserType::UNAUTHORIZED),
                            $this->getVar('To'),
                            $this->getVar('CallSid', $this->getVar('Sid', 0)),
                            1,
                            UserType::UNAUTHORIZED,
                            $this->getVar('Duration', 0),
                            $this->getVar('Price', 0),
                            $this->getVar('PriceUnit', $this->getVar('unit_price', '$'))
                        );
                    }
                } else {
                    Yii::info('~$readyDateTime 2: ' . $readyDateTime);
                    \gateway\modules\v1\services\OrderService::changeOrderStatus(
                        $order,
                        OrderStatus::OrderConfirmed,
                        UserType::Admin,
                        null,
                        $readyDateTime
                    );

                    OrderContactHistory::addRecord(
                        $this->getVar('orderId'),
                        RestaurantContactOrderType::Ivr,
                        OrderStatus::OrderConfirmed,
                        $this->getVar('firstName', UserType::UNAUTHORIZED),
                        $this->getVar('To'),
                        $this->getVar('CallSid', $this->getVar('Sid', 0)),
                        1,
                        UserType::UNAUTHORIZED,
                        $this->getVar('Duration', 0),
                        $this->getVar('Price', 0),
                        $this->getVar('PriceUnit', $this->getVar('unit_price', '$'))
                    );
                }

                $message = TwilioService::getMessage(
                    IvrMessageType::RouterDigitConfirmationThanks,
                    [
                        '{{orderId}}'      => $this->spacesBetweenCharacters($this->getVar('orderId')),
                        '{{message}}'      => $message,
                        '{{deliveryTime}}' => self::militaryToHumanTime($readyDateTime) // todo calculate delivery time
                    ]
                );
                $this->_twiml->say($message);
                $this->_twiml->pause([ 'length' => 1 ]);
                $this->_twiml->say(TwilioService::getMessage(IvrMessageType::GoodBye, []));
                break;
            case '3':
                $this->_pressButton(3);
                break;
            case '9':
                $this->_pressButton(3);
                header('Content-Type: text/xml');
                print $this->_twiml;
                exit;
                break;
            case '*':
                $this->_pressButton('default');
                break;
            default:
                $this->_pressButton(3);
                break;
        }
    }

    /**
     * Default menu for IVR response.
     * First callback link that twilio visits after call start.
     *
     * @return mixed text/xml Twiml
     */
    private function _mainMenu()
    {
        $orderId   = self::spacesBetweenCharacters($this->getVar('orderId'));
        $readyTime = self::militaryToHumanTime($this->getVar('readyByTime'));
        $readyDate = self::militaryToHumanDate($this->getVar('readyByTime'));
        $postCode  = self::spacesBetweenCharacters($this->getVar('postcode'));
        $message   = '';
        $callStart = TwilioService::getMessage(
            IvrMessageType::CallStartNotificator,
            [
                '{{orderId}}'    => $orderId,
                '{{itemCount}}'  => $this->getVar('itemCount'),
                '{{subMessage}}' => $this->getVar('itemCount') > 1 ? 'items' : 'item',
                '{{pound}}'      => $this->getVar('pound'),
                '{{pence}}'      => $this->getVar('pence')
            ]
        );

        // DineIn
        if ($this->getVar('serviceType') == DeliveryProvider::Client) {
            switch ($this->getVar('delivery_type')) {
                case DeliveryType::CollectionAsap:
                    $message = TwilioService::getMessage(
                        IvrMessageType::MainMenuDineinCollectionAsap,
                        [ '{{readyTime}}' => $readyTime ]
                    );
                    break;
                case DeliveryType::CollectionLater:
                    $message = TwilioService::getMessage(
                        IvrMessageType::MainMenuDineinCollectionLater,
                        [
                            '{{readyTime}}' => $readyTime,
                            '{{readyDate}}' => $readyDate
                        ]
                    );
                    break;
                case DeliveryType::DeliveryAsap:
                    $message = TwilioService::getMessage(
                        IvrMessageType::MainMenuDineinDeliveryAsap,
                        [ '{{readyTime}}' => $readyTime ]
                    );
                    break;
                case DeliveryType::DeliveryLater:
                    $message = TwilioService::getMessage(
                        IvrMessageType::MainMenuDineinDeliveryLater,
                        [
                            '{{readyTime}}' => $readyTime,
                            '{{readyDate}}' => $readyDate
                        ]
                    );
                    break;
            }
        // Restaurant
        } else {
            switch ($this->getVar('delivery_type')) {
                case DeliveryType::CollectionAsap:
                    $message = TwilioService::getMessage(
                        IvrMessageType::MainMenuRestaurantCollectionAsap,
                        [ '{{readyTime}}' => $readyTime ]
                    );
                    break;
                case DeliveryType::CollectionLater:
                    $message = TwilioService::getMessage(
                        IvrMessageType::MainMenuRestaurantCollectionLater,
                        [
                            '{{readyTime}}' => $readyTime,
                            '{{readyDate}}' => $readyDate
                        ]
                    );
                    break;
                case DeliveryType::DeliveryAsap:
                    $message = TwilioService::getMessage(
                        IvrMessageType::MainMenuRestaurantDeliveryAsap,
                        [
                            '{{readyTime}}'   => $readyTime,
                            '{{address}}'     => $this->getVar('address'),
                            '{{city}}'        => $this->getVar('city'),
                            '{{postal_code}}' => $postCode
                        ]
                    );
                    break;
                case DeliveryType::DeliveryLater:
                    $message = TwilioService::getMessage(
                        IvrMessageType::MainMenuRestaurantDeliveryLater,
                        [
                            '{{address}}'     => $this->getVar('address'),
                            '{{city}}'        => $this->getVar('city'),
                            '{{postal_code}}' => $postCode,
                            '{{readyTime}}'   => $readyTime,
                            '{{readyDate}}'   => $readyDate
                        ]
                    );
                    break;
            }
        }

        // Send IVR text to twilio and callBack url to router
        TwilioService::constructResponse(
            $this->_twiml,
            $this->_getUrl('default'),
            [
                $callStart,
                $message,
                TwilioService::getMessage(IvrMessageType::MainMenuCallEnd, [ ])
            ]
        );
    }

    /**
     * Menu 1 for IVR response.
     *
     * @return mixed text/xml Twiml
     */
    private function _menuButton1()
    {
        Yii::info('~$readyDateTime 0: ' . $this->getVar('readyByTime'));
        $readyTime = self::militaryToHumanTime($this->getVar('readyByTime'));
        $readyDate = self::militaryToHumanDate($this->getVar('readyByTime'));
        $customer  = $this->getVar('firstName') . ' ' . $this->getVar('lastName');

        // DineIn
        if ($this->getVar('serviceType') == DeliveryProvider::Client) {
            switch ($this->getVar('delivery_type')) {
                case DeliveryType::CollectionAsap:
                    $message = TwilioService::getMessage(
                        IvrMessageType::Menu1DineinCollectionAsap,
                        [
                            '{{customerName}}' => $customer,
                            '{{readyTime}}'    => $readyTime
                        ]
                    );
                    break;
                case DeliveryType::CollectionLater:
                    $message = TwilioService::getMessage(
                        IvrMessageType::Menu1DineinCollectionLater,
                        [
                            '{{customerName}}' => $customer,
                            '{{readyTime}}'    => $readyTime,
                            '{{readyDate}}'    => $readyDate
                        ]
                    );
                    break;
                case DeliveryType::DeliveryAsap:
                    $message = TwilioService::getMessage(
                        IvrMessageType::Menu1DineinDeliveryAsap,
                        [ '{{readyTime}}' => $readyTime ]
                    );
                    break;
                case DeliveryType::DeliveryLater:
                    $message = TwilioService::getMessage(
                        IvrMessageType::Menu1DineinDeliveryLater,
                        [
                            '{{readyTime}}' => $readyTime,
                            '{{readyDate}}' => $readyDate
                        ]
                    );
                    break;
            }
        // Restaurant
        } else {
            switch ($this->getVar('delivery_type')) {
                case DeliveryType::CollectionAsap:
                    $message = TwilioService::getMessage(
                        IvrMessageType::Menu1RestaurantCollectionAsap,
                        [
                            '{{customerName}}' => $customer,
                            '{{readyTime}}'    => $readyTime
                        ]
                    );
                    break;
                case DeliveryType::CollectionLater:
                    $message = TwilioService::getMessage(
                        IvrMessageType::Menu1RestaurantCollectionLater,
                        [
                            '{{customerName}}' => $customer,
                            '{{readyTime}}'    => $readyTime,
                            '{{readyDate}}'    => $readyDate
                        ]
                    );
                    break;
                case DeliveryType::DeliveryAsap:
                    $message = TwilioService::getMessage(
                        IvrMessageType::Menu1RestaurantDeliveryAsap,
                        [ ]
                    );
                    break;
                case DeliveryType::DeliveryLater:
                    $message = TwilioService::getMessage(
                        IvrMessageType::Menu1RestaurantDeliveryLater,
                        [
                            '{{readyTime}}' => $readyTime,
                            '{{readyDate}}' => $readyDate
                        ]
                    );
                    break;
            }
        }

        $order = \common\models\Order::findOne($this->getVar('orderId'));

        Yii::info('~$readyDateTime 3: ' . $this->getVar('readyByTime'));
        \gateway\modules\v1\services\OrderService::changeOrderStatus(
            $order,
            OrderStatus::OrderConfirmed,
            UserType::Admin,
            null,
            $this->getVar('readyByTime')
        );

        OrderContactHistory::addRecord(
            $this->getVar('orderId'),
            RestaurantContactOrderType::Ivr,
            OrderStatus::OrderConfirmed,
            $this->getVar('firstName', UserType::UNAUTHORIZED),
            $this->getVar('To'),
            $this->getVar('CallSid', $this->getVar('Sid', 0)),
            1,
            UserType::UNAUTHORIZED,
            $this->getVar('Duration', 0),
            $this->getVar('Price', 0),
            $this->getVar('PriceUnit', $this->getVar('unit_price', '$'))
        );

        $message .= TwilioService::getMessage(IvrMessageType::ThanksForWorkingWithDinein, []);
        $this->_twiml->pause([ 'length' => 1 ]);
        $this->_twiml->say($message);

        header('Content-Type: text/xml');
        print $this->_twiml;
        die;
    }

    /**
     * Menu 3 for IVR response.
     *
     * @return mixed text/xml Twiml
     */
    private function _menuButton3()
    {
        $readyTime = self::militaryToHumanTime($this->getVar('readyByTime'));
        $readyDate = self::militaryToHumanDate($this->getVar('readyByTime'));

        // DineIn
        if ($this->getVar('serviceType') == DeliveryProvider::Client) {
            switch ($this->getVar('delivery_type')) {
                case DeliveryType::CollectionAsap:
                    $message = TwilioService::getMessage(
                        IvrMessageType::Menu3DineinCollectionAsap,
                        [ '{{readyTime}}' => $readyTime ]);
                    break;
                case DeliveryType::CollectionLater:
                    $message = TwilioService::getMessage(
                        IvrMessageType::Menu3DineinCollectionLater,
                        [
                            '{{readyTime}}' => $readyTime,
                            '{{readyDate}}' => $readyDate
                        ]
                    );
                    break;
                case DeliveryType::DeliveryAsap:
                    $message = TwilioService::getMessage(
                        IvrMessageType::Menu3DineinDeliveryAsap,
                        [
                            '{{readyTime}}' => $readyTime
                        ]
                    );
                    break;
                case DeliveryType::DeliveryLater:
                    $message = TwilioService::getMessage(
                        IvrMessageType::Menu3DineinDeliveryLater,
                        [
                            '{{readyTime}}' => $readyTime,
                            '{{readyDate}}' => $readyDate
                        ]
                    );
                    break;
            }
        // Restaurant
        } else {
            switch ($this->getVar('delivery_type')) {
                case DeliveryType::CollectionAsap:
                    $message = TwilioService::getMessage(
                        IvrMessageType::Menu3RestaurantCollectionAsap,
                        [ '{{readyTime}}' => $readyTime ]
                    );
                    break;
                case DeliveryType::CollectionLater:
                    $message = TwilioService::getMessage(
                        IvrMessageType::Menu3RestaurantCollectionLater,
                        [
                            '{{readyTime}}' => $readyTime,
                            '{{readyDate}}' => $readyDate
                        ]
                    );
                    break;
                case DeliveryType::DeliveryAsap:
                    $message = TwilioService::getMessage(
                        IvrMessageType::Menu3RestaurantDeliveryAsap,
                        [ ]
                    );
                    break;
                case DeliveryType::DeliveryLater:
                    $message = TwilioService::getMessage(
                        IvrMessageType::Menu3RestaurantDeliveryLater,
                        [
                            '{{readyTime}}' => $readyTime,
                            '{{readyDate}}' => $readyDate
                        ]
                    );
                    break;
            }
        }

        // Send IVR text to twilio and callBack url to router
        TwilioService::constructResponse(
            $this->_twiml,
            $this->_getUrl(3),
            [
                TwilioService::getMessage(
                    IvrMessageType::Menu3EnterTime24HFormat,
                    [ '{{message}}' => $message ]
                ),
                TwilioService::getMessage(
                    IvrMessageType::Menu3EnterExample,
                    [ ]
                ),
            ],
            'man', 'en', 5, Yii::$app->params['timeout'], '#'
        );
    }

    /**
     * Menu 5 for IVR response.
     *
     * @return mixed text/xml Twiml
     */
    private function _menuButton5()
    {
        $postCode      = self::spacesBetweenCharacters($this->getVar('postcode'));
        $orderId       = $this->getVar('orderId');
        $readyTime     = self::militaryToHumanTime($this->getVar('readyByTime'));
        $readyDate     = self::militaryToHumanDate($this->getVar('readyByTime'));
        $customerEmail = self::spacesBetweenCharacters($this->getVar('email'));
        $customerEmail = str_replace('.', 'dot', $customerEmail);
        $customerPhone = self::spacesBetweenCharacters($this->getVar('phone'));
        $customerName  = $this->getVar('firstName') . ' ' . $this->getVar('lastName');
        $subMessage    = $this->getVar('itemsCount') > 1 ? 'items' : 'item';
        $addRequest    = $this->getVar('addrequest');
        $itemsList     = $this->getVar('orderItems');

        // DineIn
        if ($this->getVar('serviceType') == DeliveryProvider::Client) {
            switch ($this->getVar('delivery_type')) {
                case DeliveryType::CollectionAsap:
                    $message = TwilioService::getMessage(
                        IvrMessageType::Menu5DineinCollectionAsap,
                        [
                            '{{orderId}}'            => $orderId,
                            '{{readyTime}}'          => $readyTime,
                            '{{subMessage}}'         => $subMessage,
                            '{{itemCount}}'          => $this->getVar('itemCount'),
                            '{{orderItems}}'         => $itemsList,
                            '{{additional_request}}' => $addRequest
                        ]
                    );
                    break;
                case DeliveryType::CollectionLater:
                    $message = TwilioService::getMessage(
                        IvrMessageType::Menu5DineinCollectionLater,
                        [
                            '{{orderId}}'            => $orderId,
                            '{{readyTime}}'          => $readyTime,
                            '{{readyDate}}'          => $readyDate,
                            '{{subMessage}}'         => $subMessage,
                            '{{pound}}'              => $this->getVar('pound'),
                            '{{pence}}'              => $this->getVar('pence'),
                            '{{itemCount}}'          => $this->getVar('itemCount'),
                            '{{orderItems}}'         => $itemsList,
                            '{{additional_request}}' => $addRequest
                        ]
                    );
                    break;
                case DeliveryType::DeliveryAsap:
                    $message = TwilioService::getMessage(
                        IvrMessageType::Menu5DineinDeliveryAsap,
                        [
                            '{{orderId}}'            => $orderId,
                            '{{readyTime}}'          => $readyTime,
                            '{{subMessage}}'         => $subMessage,
                            '{{pound}}'              => $this->getVar('pound'),
                            '{{pence}}'              => $this->getVar('pence'),
                            '{{itemCount}}'          => $this->getVar('itemCount'),
                            '{{orderItems}}'         => $itemsList,
                            '{{additional_request}}' => $addRequest
                        ]
                    );
                    break;
                case DeliveryType::DeliveryLater:
                    $message = TwilioService::getMessage(
                        IvrMessageType::Menu5DineinDeliveryLater,
                        [
                            '{{orderId}}'            => $orderId,
                            '{{readyTime}}'          => $readyTime,
                            '{{readyDate}}'          => $readyDate,
                            '{{subMessage}}'         => $subMessage,
                            '{{pound}}'              => $this->getVar('pound'),
                            '{{pence}}'              => $this->getVar('pence'),
                            '{{itemCount}}'          => $this->getVar('itemCount'),
                            '{{orderItems}}'         => $itemsList,
                            '{{additional_request}}' => $addRequest
                        ]
                    );
                    break;
            }
        // Restaurant
        } else {
            switch ($this->getVar('delivery_type')) {
                case DeliveryType::CollectionAsap:
                    $message = TwilioService::getMessage(
                        IvrMessageType::Menu5RestaurantCollectionAsap,
                        [
                            '{{orderId}}'            => $orderId,
                            '{{readyTime}}'          => $readyTime,
                            '{{subMessage}}'         => $subMessage,
                            '{{pound}}'              => $this->getVar('pound'),
                            '{{pence}}'              => $this->getVar('pence'),
                            '{{itemCount}}'          => $this->getVar('itemCount'),
                            '{{orderItems}}'         => $itemsList,
                            '{{additional_request}}' => $addRequest
                        ]
                    );
                    break;
                case DeliveryType::CollectionLater:
                    $message = TwilioService::getMessage(
                        IvrMessageType::Menu5RestaurantCollectionLater,
                        [
                            '{{orderId}}'            => $orderId,
                            '{{readyTime}}'          => $readyTime,
                            '{{readyDate}}'          => $readyDate,
                            '{{subMessage}}'         => $subMessage,
                            '{{pound}}'              => $this->getVar('pound'),
                            '{{pence}}'              => $this->getVar('pence'),
                            '{{itemCount}}'          => $this->getVar('itemCount'),
                            '{{orderItems}}'         => $itemsList,
                            '{{additional_request}}' => $addRequest
                        ]
                    );
                    break;
                case DeliveryType::DeliveryAsap:
                    $message = TwilioService::getMessage(
                        IvrMessageType::Menu5RestaurantDeliveryAsap,
                        [
                            '{{orderId}}'            => $orderId,
                            '{{postal_code}}'        => $postCode,
                            '{{subMessage}}'         => $subMessage,
                            '{{customerName}}'       => $customerName,
                            '{{customerPhone}}'      => $customerPhone,
                            '{{customerEmail}}'      => $customerEmail,
                            '{{city}}'               => $this->getVar('city'),
                            '{{pound}}'              => $this->getVar('pound'),
                            '{{pence}}'              => $this->getVar('pence'),
                            '{{address}}'            => $this->getVar('address'),
                            '{{itemCount}}'          => $this->getVar('itemCount'),
                            '{{orderItems}}'         => $itemsList,
                            '{{additional_request}}' => $addRequest
                        ]
                    );
                    break;
                case DeliveryType::DeliveryLater:
                    $message = TwilioService::getMessage(
                        IvrMessageType::Menu5RestaurantDeliveryLater,
                        [
                            '{{orderId}}'            => $orderId,
                            '{{readyTime}}'          => $readyTime,
                            '{{readyDate}}'          => $readyDate,
                            '{{subMessage}}'         => $subMessage,
                            '{{pound}}'              => $this->getVar('pound'),
                            '{{pence}}'              => $this->getVar('pence'),
                            '{{itemCount}}'          => $this->getVar('itemCount'),
                            '{{orderItems}}'         => $itemsList,
                            '{{additional_request}}' => $addRequest
                        ]
                    );
                    break;
            }
        }

        // Send IVR text to twilio and callBack url to router
        TwilioService::constructResponse( $this->_twiml, $this->_getUrl(5), [ $message ] );
    }


    /**
     * Menu 7 for IVR response.
     *
     * @return mixed text/xml Twiml
     */
    private function _menuButton7()
    {
        $message = TwilioService::getMessage(
            IvrMessageType::Router7AreYouSureCancelOrder,
            [ '{{orderId}}' => $this->getVar('orderId') ]
        );

        // Send IVR text to twilio and callBack url to router
        TwilioService::constructResponse( $this->_twiml, $this->_getUrl(7), [ $message ] );
    }

    /**
     * Load all data from request form into session
     *
     * @param array $requestForm
     */
    private function _initSessionData($requestForm)
    {
        $tmp = [];
        $sessionData = Yii::$app->globalCache->getIvrSession($this->_sid) ?: [];
        foreach ($sessionData as $key => $value) {
            if (isset($value)) {
                $tmp[$key] = $value;
                $this->_session->set($key, $value);
            }
        }
        foreach ($requestForm as $key => $value) {
            if (isset($value)) {
                $tmp[$key] = $value;
                $this->_session->set($key, $value);
            }
        }
    }

    /**
     * Redirect twilio to next url
     *
     * @param integer $buttonId
     */
    private function _pressButton($buttonId)
    {
        $this->_digits = false;
        $this->_twiml->redirect(
            $this->_getUrl($buttonId, 'menu'),
            [ 'method' => 'GET' ]
        );
    }

    /**
     * Convert military time to a time that sounds better with text to speech
     * eg: 1700 -> 5:00 pm
     *
     * @param string $militaryTime
     *
     * @return string
     */
    public static function militaryToHumanTime($militaryTime)
    {
        $time = strtotime($militaryTime);
        return date('g:i a', $time);
    }

    /**
     * @param string $date
     *
     * @return string
     */
    public static function militaryToHumanDate($date)
    {
        $date_voice = strtotime($date);
        return date('jS F', $date_voice);
    }

    /**
     * Add spaces in between letters of a string, so text to speech
     * will pronunciate each character.
     * eg: 1000 -> 1 0 0 0
     *
     * @param string $string
     * @return string
     */
    public static function spacesBetweenCharacters($string)
    {
        $string = wordwrap($string, 1, " , ,", true);
        return strtoupper($string);
    }

    /**
     * Generate url for Twilio callback
     *
     * @param string $id   Route id or title
     * @param string $type Router or button url
     *
     * @return string | boolean
     */
    private function _getUrl($id, $type = 'router')
    {
        return Yii::$app->params['ivrUrl'] . "?action={$type}&press_button={$id}";
    }
}