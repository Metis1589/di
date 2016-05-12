<?php
namespace common\enums;

class IvrMessageType extends BaseEnum {
    const MainMenuCallEnd                   = 'MainMenuCallEnd';
    const CallStartNotificator              = 'CallStartNotificator';
    const ThanksForWorkingWithDinein        = 'ThanksForWorkingWithDinein';
    // Main menu
    const MainMenuDineinDeliveryAsap        = 'MainMenuDineinDeliveryAsap';
    const MainMenuDineinDeliveryLater       = 'MainMenuDineinDeliveryLater';
    const MainMenuDineinCollectionAsap      = 'MainMenuDineinCollectionAsap';
    const MainMenuDineinCollectionLater     = 'MainMenuDineinCollectionLater';
    const MainMenuRestaurantDeliveryAsap    = 'MainMenuRestaurantDeliveryAsap';
    const MainMenuRestaurantDeliveryLater   = 'MainMenuRestaurantDeliveryLater';
    const MainMenuRestaurantCollectionAsap  = 'MainMenuRestaurantCollectionAsap';
    const MainMenuRestaurantCollectionLater = 'MainMenuRestaurantCollectionLater';
    // Menu 1
    const Menu1DineinDeliveryAsap           = 'Menu1DineinDeliveryAsap';
    const Menu1DineinDeliveryLater          = 'Menu1DineinDeliveryLater';
    const Menu1DineinCollectionAsap         = 'Menu1DineinCollectionAsap';
    const Menu1DineinCollectionLater        = 'Menu1DineinCollectionLater';
    const Menu1RestaurantDeliveryAsap       = 'Menu1RestaurantDeliveryAsap';
    const Menu1RestaurantDeliveryLater      = 'Menu1RestaurantDeliveryLater';
    const Menu1RestaurantCollectionAsap     = 'Menu1RestaurantCollectionAsap';
    const Menu1RestaurantCollectionLater    = 'Menu1RestaurantCollectionLater';
    // Menu 3
    const Router3SubText1Dinein             = 'Router3SubText1Dinein';
    const Router3SubText2Dinein             = 'Router3SubText2Dinein';
    const Router3SubText1Restaurant         = 'Router3SubText1Restaurant';
    const Router3SubText2Restaurant         = 'Router3SubText2Restaurant';
    const Router3SubText1Collection         = 'Router3SubText1Collection';
    const Router3SubText2Collection         = 'Router3SubText2Collection';
    const Router3WrongFormat                = 'Router3WrongFormat';
    const Router3TimeLessThanOrderTime      = 'Router3TimeLessThanOrderTime';
    const ConfirmReenterTimePressStar       = 'ConfirmReenterTimePressStar';
    const Menu3DineinDeliveryAsap           = 'Menu3DineinDeliveryAsap';
    const Menu3DineinDeliveryLater          = 'Menu3DineinDeliveryLater';
    const Menu3DineinCollectionAsap         = 'Menu3DineinCollectionAsap';
    const Menu3DineinCollectionLater        = 'Menu3DineinCollectionLater';
    const Menu3RestaurantDeliveryAsap       = 'Menu3RestaurantDeliveryAsap';
    const Menu3RestaurantDeliveryLater      = 'Menu3RestaurantDeliveryLater';
    const Menu3RestaurantCollectionAsap     = 'Menu3RestaurantCollectionAsap';
    const Menu3RestaurantCollectionLater    = 'Menu3RestaurantCollectionLater';
    const Menu3EnterTime24HFormat           = 'Menu3EnterTime24HFormat';
    const Menu3EnterExample                 = 'Menu3EnterExample';
    // Menu 5
    const Menu5DineinDeliveryAsap           = 'Menu5DineinDeliveryAsap';
    const Menu5DineinDeliveryLater          = 'Menu5DineinDeliveryLater';
    const Menu5DineinCollectionAsap         = 'Menu5DineinCollectionAsap';
    const Menu5DineinCollectionLater        = 'Menu5DineinCollectionLater';
    const Menu5RestaurantDeliveryAsap       = 'Menu5RestaurantDeliveryAsap';
    const Menu5RestaurantDeliveryLater      = 'Menu5RestaurantDeliveryLater';
    const Menu5RestaurantCollectionAsap     = 'Menu5RestaurantCollectionAsap';
    const Menu5RestaurantCollectionLater    = 'Menu5RestaurantCollectionLater';
    const Router5TotalPrice                 = 'Router5TotalPrice';
    const Router5AdditionalMessage1         = 'Router5AdditionalMessage1';
    const Router5AdditionalMessage2         = 'Router5AdditionalMessage2';
    const Router5AdditionalMessage3         = 'Router5AdditionalMessage3';
    const Router5OrderAddress               = 'Router5OrderAddress';
    const Router5OrderId                    = 'Router5OrderId';
    const Router5CustomerName               = 'Router5CustomerName';
    // Menu 7
    const Router7OrderCancelled             = 'RouterOrderCancelled';
    const Router7AreYouSureCancelOrder      = 'Router7AreYouSureCancelOrder';
    const Router7OrderCancelledCallEnd      = 'Router7OrderCancelledCallEnd';
    // Menu 3 confirmation
    const RouterConfirmationDinein          = 'RouterConfirmationDinein';
    const RouterConfirmationRestaurant      = 'RouterConfirmationRestaurant';
    const RouterConfirmationCollection      = 'RouterConfirmationCollection';
    const RouterDigitConfirmationThanks     = 'RouterDigitConfirmationThanks';
    const GoodBye                           = 'GoodBye';

    public static function getContents() {
        return [
            self::MainMenuCallEnd                   => 'press 5 to hear the order details .  press 7 to reject the order .  Press 9 to hear this message again',
            self::CallStartNotificator              => 'Hello this is dine In dot co dot uk. We have sent you an order I D . {{orderId}} , with {{itemCount}} {{subMessage}} for a total of {{pound}} pounds and {{pence}} pence. . ,,',
            self::ThanksForWorkingWithDinein        => '. . Thank you for working with dine in dot co dot uk. Good bye.',
            // Main menu
            self::MainMenuDineinDeliveryAsap        => 'This order is for delivery as soon as possible. . Please have the food ready at {{readyTime}}. . Please press 1 to confirm that our driver can pick up the food at {{readyTime}} . press 3 to set a later time for when the food can be picked up by the driver.',
            self::MainMenuDineinDeliveryLater       => 'This order is for delivery on {{readyDate}} at {{readyTime}}. . Please have the food ready on {{readyDate}} at {{readyTime}}. . Please press 1 to confirm that our driver can pick up the food on {{readyDate}} at {{readyTime}}. . press 3 to set a later time for when the food can be picked by up by our driver.',
            self::MainMenuDineinCollectionAsap      => 'This order is for collection as soon as possible.  Please have the food ready at {{readyTime}}. . Please press 1 to confirm that the customer can collect the food at {{readyTime}} . press 3 to set a later time for when the food can be collected by the customer.',
            self::MainMenuDineinCollectionLater     => 'This order is for collection on {{readyDate}} at {{readyTime}}. . The customer will collect the food on {{readyDate}} at {{readyTime}}. . Please press 1 to confirm that the customer can collect the food on {{readyDate}} at {{readyTime}} . .  press 3 to set a later time for when the food can be collected by the customer.',
            self::MainMenuRestaurantDeliveryAsap    => 'This order is for delivery as soon as possible. . The customer\'s address is {{address}}. . ..  {{city}} .. Post code is {{postal_code}} .. Please press 1 to agree you will deliver the food at {{readyTime}} . . Please press 3 to set the estimated delivery time.',
            self::MainMenuRestaurantDeliveryLater   => 'This order is for delivery to {{address}} ..  {{city}} .. Post code is {{postal_code}} on {{readyDate}} at {{readyTime}}. . Please press 1 to agree you will deliver the food on {{readyDate}} at {{readyTime}} . . press 3 to set a later time for when the food will be delivered.',
            self::MainMenuRestaurantCollectionAsap  => 'This order is for collection as soon as possible.  Please have the food ready at {{readyTime}}. . Please press 1 to confirm that the customer can collect the food at {{readyTime}} . press 3 to set a later time for when the food can be collected by the customer.',
            self::MainMenuRestaurantCollectionLater => 'This order is for collection on {{readyDate}} at {{readyTime}}. . The customer will collect the food on {{readyDate}} at {{readyTime}}. . Please press 1 to confirm that the customer can collect the food on {{readyDate}} at {{readyTime}} . .  press 3 to set a later time for when the food can be collected by the customer.',
            // Menu 1
            self::Menu1DineinDeliveryAsap           => 'Thank you, order confirmed. Our driver will see you at {{readyTime}} to pick up the food.',
            self::Menu1DineinDeliveryLater          => 'Thank you, order confirmed. Our driver will see you on {{readyDate}} at {{readyTime}} to pick up the food.',
            self::Menu1DineinCollectionAsap         => 'Thank you, order confirmed. The customer {{customerName}} will collect food at {{readyTime}}.',
            self::Menu1DineinCollectionLater        => 'Thank you, order confirmed. The customer {{customerName}} will collect food on {{readyDate}} at {{readyTime}}.',
            self::Menu1RestaurantDeliveryAsap       => 'Thank you for confirming you will deliver the food as soon as possible.',
            self::Menu1RestaurantDeliveryLater      => 'Thank you for confirming you will deliver the food on {{readyDate}} at {{readyTime}} .',
            self::Menu1RestaurantCollectionAsap     => 'Thank you, order confirmed. The customer {{customerName}} will collect food at {{readyTime}}.',
            self::Menu1RestaurantCollectionLater    => 'Thank you, order confirmed. The customer {{customerName}} will collect food on {{readyDate}} at {{readyTime}}.',
            // Menu 3
            self::Router3SubText1Dinein             => 'when our driver can pick up the food after {{readyTime}}',
            self::Router3SubText2Dinein             => 'ready for our driver to pick up',
            self::Router3SubText1Restaurant         => 'when you will deliver the food after {{readyTime}}',
            self::Router3SubText2Restaurant         => 'delivered',
            self::Router3SubText1Collection         => 'when the customer can collect the food after {{readyTime}}',
            self::Router3SubText2Collection         => 'ready for customer to collect',
            self::Router3WrongFormat                => 'I am sorry we did not understand that time.  Please try again and enter the time , in 24 hour format , followed by the hash key, {{message}} . .  press 9 to go back to the previous menu . . press star to go back to the main menu.',
            self::Router3TimeLessThanOrderTime      => 'You entered {{time}}.  This is earlier than the customer\'s order time.  Please try again and enter the time , in 24 hour format , followed by the hash key, {{message}} . . press 9 to go back to the previous menu. . press star to go back to the main menu.',
            self::ConfirmReenterTimePressStar       => 'You entered {{time}}. Press 1 to confirm that order will be {{message}} at {{time}} . . press 3 to change the time. . press 9 to repeat this message . . press star to go back to the main menu.',
            self::Menu3DineinDeliveryAsap           => 'ready for our driver to pick up after {{readyTime}}',
            self::Menu3DineinDeliveryLater          => 'ready for our driver to pick up after {{readyTime}} on {{readyDate}}',
            self::Menu3DineinCollectionAsap         => 'ready for the customer to collect after {{readyTime}}',
            self::Menu3DineinCollectionLater        => 'ready for the customer to collect after {{readyTime}} on {{readyDate}}',
            self::Menu3RestaurantDeliveryAsap       => 'delivered',
            self::Menu3RestaurantDeliveryLater      => 'delivered after {{readyTime}} on {{readyDate}}',
            self::Menu3RestaurantCollectionAsap     => 'ready for the customer to collect after {{readyTime}}',
            self::Menu3RestaurantCollectionLater    => 'ready for the customer to collect after {{readyTime}} on {{readyDate}}',
            self::Menu3EnterTime24HFormat           => 'Please enter the time , in 24 hour format , followed by hash , when the food will be {{message}} .',
            self::Menu3EnterExample                 => 'For example , for 7 p m please enter one nine zero zero followed by hash. . press 9 to repeat. . press star to go back to the main menu.',
            // Menu 5
            self::Menu5DineinDeliveryAsap           => 'Order ID {{orderId}}  , to be delivered as soon as possible. There are {{itemCount}} {{subMessage}} for a total of {{pound}} pounds and {{pence}} pence . .  The order is for {{orderItems}} . .{{additional_request}} . Please press 1 to confirm that food will be ready to be picked up by our driver at {{readyTime}} . . press 2 to repeat the order . . press 3 to hear the order I D . . press 6 for the order details . . press 9 to repeat this message . . press star to go back to the main menu.',
            self::Menu5DineinDeliveryLater          => 'Order ID {{orderId}}  , to be delivered on {{readyDate}} at {{readyTime}}. There are {{itemCount}} {{subMessage}} for a total of {{pound}} pounds and {{pence}} pence . . The order is for {{orderItems}} .  .{{additional_request}} .. Please press 1 to confirm that food will be ready on {{readyDate}} at {{readyTime}} . . press 2 to repeat to the order . . press 3 to hear the order I D . . press 6 for the order details . . press 9 to repeat this message . . press star to go back to the main menu.',
            self::Menu5DineinCollectionAsap         => 'Order ID {{orderId}}  , to be collected by the customer as soon as possible.  There are {{itemCount}} {{subMessage}} for a total of {{pound}} pounds and {{pence}} pence . . The order is for {{orderItems}}. .{{additional_request}}.  Please press 1 to confirm that food will be ready for the customer to collect at {{readyTime}} . . press 2 to repeat the order. . press 3 to hear the order I D . . press 4 for the customer\'s name . .  press 6 for the order details . . press 9 to repeat this message . . press star to go back to the main menu.',
            self::Menu5DineinCollectionLater        => 'Order ID {{orderId}}  , to be collected by the customer on {{readyDate}} at {{readyTime}}.  There are {itemCount}} {{subMessage}} for a total of {{pound}} pounds and {pence}} pence. . The order is for {{orderItems}}. . {{additional_request}} ..  Please press 1 to confirm that food will be ready for the customer to collect on {{readyDate}} at {{readyTime}} . . press 2 to repeat the order. . press 3 to hear the order I D . . press 4 for the customer\'s name . .  press 6 for the order details . . press 9 to repeat this message . . press star to go back to the main menu.',
            self::Menu5RestaurantDeliveryAsap       => 'Order ID {{orderId}}  , to be delivered as soon as possible to {{customerName}} at {{address}} ..  {{city}} .. Post code is {{postal_code}} .  The customer\'s mobile number is {{customerPhone}}.  . Their e mail is {{customerEmail}}.  . There are {{itemCount}} {{subMessage}} for a total of {{pound}} pounds and {{pence}} pence. . The order is for {{orderItems}}. . {{additional_request}} .. Press 1 to set the estimated delivery time . . press 2 to repeat the order . . press 3 to hear the order I D . . press 4 for the customer\'s name . . press 5 for the customer\'s address . . press 6 for the order details . . press 9 to hear this message again . . press star to go back to the main menu.',
            self::Menu5RestaurantDeliveryLater      => 'Order ID {{orderId}}  , to be delivered on {{readyDate}} at {{readyTime}}. . There are {{itemCount}} {{subMessage}} for a total of {{pound}} pounds and {{pence}}  . .{{additional_request}} pence. . The order is for {{orderItems}}. . Please press 1 to confirm that food will be delivered on {{readyDate}} at {{readyTime}}. . press 2 to repeat the order . . press 3 to hear the order I D . . press 4 for the customer\'s name . . press 5 for the customer\'s address . . press 6 for the order details . . press 9 to hear this message again . . press star to go back to the main menu.',
            self::Menu5RestaurantCollectionAsap     => 'Order ID {{orderId}}  , to be collected by the customer as soon as possible.  There are {{itemCount}} {{subMessage}} for a total of {{pound}} pounds and {{pence}} pence. . The order is for {{orderItems}}.  .{{additional_request}}  Please press 1 to confirm that food will be ready for the customer to collect at {{readyTime}} . . press 2 to repeat the order. . press 3 to hear the order I D . . press 4 for the customer\'s name . .  press 6 for the order details . . press 9 to hear this message again . . press star to go back to the main menu.',
            self::Menu5RestaurantCollectionLater    => 'Order ID {{orderId}}  , to be collected on {{readyDate}} at {{readyTime}}. .  There are {{itemCount}} {{subMessage}} for a total of {{pound}} pounds and {{pence}}  . .{{additional_request}} pence. . The order is for {{orderItems}}.   Please press 1 to confirm that food will be ready for the customer to collect at {{readyTime}} . . press 2 to repeat the order. . press 3 to hear the order I D. . press 4 for the customer\'s name . .  press 6 for the order details . . press 9 to hear this message again . . press star to go back to the main menu.',
            self::Router5TotalPrice                 => 'The order has {{itemCount}} items for a total of {{pound}} pounds and {{pence}} pence. . The order is for {{orderItems}}.  {{message}}',
            self::Router5OrderAddress               => 'The address is {{address}} ..  {{city}} .. Post code is {{postal_code}} . {{message}}',
            self::Router5OrderId                    => 'The order I D is . {{orderId}}, {{message}}',
            self::Router5CustomerName               => "The customer's name is . . {{customerFirstName}} {{customerLastName}}. The first name is spelled {{firstNameSpaces}}. The second name is spelled {{lastNameSpaces}} . . {{message}}",
            self::Router5AdditionalMessage1         => 'Please press 1 to confirm that food will be ready to be picked up by our driver at {{readyTime}} . . press 2 to repeat the order . . press 3 to hear the order I D . . press 6 for the order details . . press 9 to repeat this message . . press star to go back to the main menu.',
            self::Router5AdditionalMessage2         => 'Press 1 to set the estimated delivery time . . press 2 to repeat the order . . press 3 to hear the order I D . . press 4 for the customer\'s name . . press 5 for the customer\'s address . . press 6 for the order details . . press 9 to hear this message again . . press star to go back to the main menu.',
            self::Router5AdditionalMessage3         => 'Please press 1 to confirm that food will be ready for the customer to collect at {{readyTime}} . . press 2 to repeat the order. . press 3 to hear the order I D. . press 4 for the customer\'s name . .  press 6 for the order details . . press 9 to hear this message again . . press star to go back to the main menu.',
            // Menu 7
            self::Router7OrderCancelled             => 'Order I D {{orderId}} has been cancelled. ',
            self::Router7AreYouSureCancelOrder      => 'Are you sure you want to cancel order I D {{orderId}} ? Please press 7 again to cancel. . Press 9 to hear this message again . . Press star to go back to the main menu.',
            self::Router7OrderCancelledCallEnd      => 'Thank you for working with dine in dot co dot uk. Good bye.',
            // Menu 3 confirmation
            self::RouterConfirmationDinein          => 'ready for our driver to pick up',
            self::RouterConfirmationRestaurant      => 'delivered',
            self::RouterConfirmationCollection      => 'ready for the customer to collect',
            self::RouterDigitConfirmationThanks     => 'Thank you for confirming that you will have the order I D {{orderId}} {{message}} at {{deliveryTime}}.  Thank you for working with dine in dot co dot u k .',
            self::GoodBye                           => 'Good bye.'
        ];
    }
}