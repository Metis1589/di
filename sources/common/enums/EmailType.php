<?php
namespace common\enums;

use \common\components\language\T;

class EmailType {

    const NewUserRegistration      = 'NewUserRegistration';
    const ForgotPassword           = 'ForgotPassword';
    const RestarauntSignup         = 'RestarauntSignup';
    const ReadyBy                  = 'ReadyBy';
    const TransferringToRestaurant = 'TransferringToRestaurant';
    const OrderConfirmed           = 'OrderConfirmed';
    const FoodEnRoute              = 'FoodEnRoute';
    const Delivered                = 'Delivered';
    const Cancellation             = 'Cancellation';
    const CancellationToRestaraunt = 'CancellationToRestaraunt';
    //const PaymentReceived          = 'PaymentReceived';
    const ContactUs                = 'ContactUs';
    const SuggestRestaurant        = 'SuggestRestaurant';
    const OrdersExported           = 'OrdersExported';

    public static function getLabels() {
        return [
            self::NewUserRegistration      => T::l('New user registration'),
            self::ForgotPassword           => T::l('Reminder'),
            self::RestarauntSignup         => T::l('Restaraunt signup'),
            self::ReadyBy                  => T::l('Ready by'),
            self::TransferringToRestaurant => T::l('Transferring to Restaurant'),
            self::OrderConfirmed           => T::l('Order Confirmed'),
            self::FoodEnRoute              => T::l('Food en route'),
            self::Delivered                => T::l('Delivered'),
            self::Cancellation             => T::l('Cancellation'),
            self::CancellationToRestaraunt => T::l('Cancellation to restaraunt'),
            self::ContactUs                => T::l('Contact us'),
            self::SuggestRestaurant        => T::l('Suggest restaurant'),
            self::OrdersExported           => T::l('Orders exported'),
        ];
    }

    public static function getEmailSubjects() {
        return [
            self::NewUserRegistration      => 'Activate your {{siteDomain}} account',
            self::ForgotPassword           => '{{siteDomain}} Login Reminder',
            self::RestarauntSignup         => 'Restaurant signup information',
            self::ReadyBy                  => 'New Order ({{orderNumber}}) - {{deliveryTypeSubject}} - {{siteDomain}}',
            self::TransferringToRestaurant => '{{siteDomain}} Order: {{orderNumber}} Status: Transferring to {{deliveryTypeSubject}}',
            self::OrderConfirmed           => '{{siteDomain}} Order {{orderNumber}} Status: Order Confirmed',
            self::FoodEnRoute              => '{{siteDomain}} Order {{orderNumber}} Status: Food en route',
            self::Delivered                => '{{siteDomain}} Order {{orderNumber}} Status: Delivered',
            self::Cancellation             => 'Cancellation of order {{orderNumber}} {{siteDomain}}',
            self::CancellationToRestaraunt => 'Order cancellation {{orderNumber}} from {{siteDomain}}',
           // self::PaymentReceived          => '{{siteDomain}} Order {{orderNumber}} Status: Payment received',
            self::ContactUs                => 'Customer submited contact form',
            self::SuggestRestaurant        => 'New restaurant was suggested',
            self::OrdersExported           => '{{exportTypeTitle}} were exported',
        ];
    }
}
