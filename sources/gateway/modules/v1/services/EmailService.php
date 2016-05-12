<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 5/4/2015
 * Time: 6:26 PM
 */

namespace gateway\modules\v1\services;

use common\enums\OrderExportType;
use common\enums\RecordType;
use common\enums\DeliveryType;
use common\models\OrderExport;
use common\models\RestaurantContactOrder;
use common\enums\RestaurantContactOrderType;
use Yii;
use common\enums\EmailType;
use common\models\EmailTemplate;
use yii\web\NotFoundHttpException;
use common\components\language\T;
use common\components\FormatHelper;


class EmailService extends \common\components\mail\Mailer {


    /**
     * Sends email with activation hash to customer.
     *
     * @param \common\models\Client $client           Client model whose service use customer with $user model
     * @param \common\models\User   $user             Customer model
     * @param string                $activateUserLink Link to activate user's account
     *
     * @return boolan whether email was sent or not
     *
     * @throws NotFoundHttpException
     */
    public static function sendToCustomer($client, $user, $activateUserLink, $emailTemplate = null)
    {
        $placeholders = [
            '{{logoImage}}'       => self::getClientLogo(),
            '{{activateUserUrl}}' => $activateUserLink,
            '{{userName}}'        => $user['username'],
            '{{siteDomain}}'      => $client['url']
        ];
        $emailTemplateModel = self::getEmailTemplate(EmailType::NewUserRegistration, $client, $placeholders, null, $emailTemplate);
        return self::sendOneFromTemplate($user->username, $emailTemplateModel);
    }


    /**
     * Sends email with token to restore ser's password.
     *
     * @param \common\models\Client $client           Client model whose service use customer with $user model
     * @param \common\models\User   $user             Customer model
     * @param string                $resetPasswordUrl Link with to token to restore password
     *
     * @return boolan whether email was sent or not
     *
     * @throws NotFoundHttpException
     */
    public static function sendForgotPassword($client, $user, $resetPasswordUrl, $emailTemplate = null)
    {
        $placeholders = [
            '{{logoImage}}'     => self::getClientLogo(),
            '{{resetLink}}'     => $resetPasswordUrl,
            '{{resetSiteLink}}' => $resetPasswordUrl,
            '{{userName}}'      => $user['first_name']
        ];
        $emailTemplateModel = self::getEmailTemplate(EmailType::ForgotPassword, $client, $placeholders, null, $emailTemplate);
        return self::sendOneFromTemplate($user->username, $emailTemplateModel);
    }

    /**
     * Sends email after success restaraunt signup
     *
     * @param \common\models\Client $client                                             Client model whose service use customer with $user model
     * @param \gateway\modules\v1\forms\common\ContactUsForm  $form                     Contacts form data
     *
     * @return boolan whether email was sent or not
     *
     * @throws NotFoundHttpException
     */
    public static function sendContactUs($client, $form, $language, $emailTemplate = null)
    {
        $placeholders = [
            '{{logoImage}}'       => self::getClientLogo(),
            '{{customerName}}'    => $form['first_name'].' '.$form['last_name'],
            '{{message}}'         => $form['message'],
            '{{phone}}'           => $form['phone'],
            '{{email}}'           => $form['email'],
            '{{orderNo}}'         => $form['order_number'],
            '{{siteDomain}}'      => $client['url']
        ];
        $emailTemplateModel = self::getEmailTemplate(EmailType::ContactUs, $client, $placeholders, $language, $emailTemplate);
        return self::sendOneFromTemplate($client['contact_email'], $emailTemplateModel);
    }

    /**
     * Sends email after success restaraunt signup
     *
     * @param \common\models\Client $client                                             Client model whose service use customer with $user model
     * @param \gateway\modules\v1\forms\common\SuggestRestaurantForm  $form             Restaurant suggest form data
     *
     * @return boolan whether email was sent or not
     *
     * @throws NotFoundHttpException
     */
    public static function sendRestarauntSuggest($client, $form, $language, $emailTemplate = null)
    {
        $languageModel = Yii::$app->globalCache->getLanguage($language);
        $cuisine = Yii::$app->globalCache->getCuisine($form['cuisine'],$languageModel['iso_code']);
        $location = Yii::$app->globalCache->getSeoArea($form['area'],$languageModel['iso_code']);
        $placeholders = [
            '{{logoImage}}'                 => self::getClientLogo(),
            '{{restaurant_name}}'           => $form['name'],
            '{{restaurant_cuisine}}'        => $cuisine ? $cuisine['name'] : '',
            '{{restaurant_location_area}}'  => $location ? $location['name'] : '',
            '{{restaurant_phone_number}}'   => $form['phone'],
            '{{restaurant_postcode}}'       => $form['postcode'],
            '{{email}}'                     => $form['email'],
            '{{siteDomain}}'                => $client['url']
        ];
        $emailTemplateModel = self::getEmailTemplate(EmailType::SuggestRestaurant, $client, $placeholders, $language, $emailTemplate);
        return self::sendOneFromTemplate($client['contact_email'], $emailTemplateModel);
    }

    /**
     * Sends email after success restaraunt signup
     *
     * @param \common\models\Client $client                                             Client model whose service use customer with $user model
     * @param \gateway\modules\v1\forms\common\SignUpRestaurantForm  $resetPasswordUrl  Link with to token to restore password
     *
     * @return boolan whether email was sent or not
     *
     * @throws NotFoundHttpException
     */
    public static function sendRestaurantSignUp($client, $form, $language, $emailTemplate = null)
    {
        $restarauntAddress = [];
        if($form['restaurant_address1']){
            $restarauntAddress[] = $form['restaurant_address1'];
        }
        if($form['restaurant_address2']){
            $restarauntAddress[] = $form['restaurant_address2'];
        }

        $restarauntCuisine = [];
        if($form['cuisine_1']){
            if($languageModel = Yii::$app->globalCache->getLanguage($language)){
                if($cuisine = Yii::$app->globalCache->getCuisine($form['cuisine_1'],$languageModel['iso_code'])){
                    $restarauntCuisine[] = $cuisine['name'];
                }
            }
        }
        if($form['cuisine_2']){
            if($languageModel = Yii::$app->globalCache->getLanguage($language)){
                if($cuisine = Yii::$app->globalCache->getCuisine($form['cuisine_2'],$languageModel['iso_code'])){
                    $restarauntCuisine[] = $cuisine['name'];
                }
            }
        }
        if($form['cuisine_3']){
            if($languageModel = Yii::$app->globalCache->getLanguage($language)){
                if($cuisine = Yii::$app->globalCache->getCuisine($form['cuisine_3'],$languageModel['iso_code'])){
                    $restarauntCuisine[] = $cuisine['name'];
                }
            }
        }

        $takeaways_count = $form['takeaways_count'] ? number_format($form['takeaways_count']) : '-';
        if(!$takeaways_count){
            $takeaways_count = '-';
        }

        $placeholders = [
            '{{offerDeliveryToday}}'  => $form['offer_delivery'] ? 'Yes' : 'No',
            '{{offerTakeAway}}'       => $form['takeaway_service'] ? 'Yes' : 'No',
            '{{offerHowMany}}'        => $takeaways_count,
            '{{restaurantName}}'      => $form['restaurant_name'],
            '{{restaurantAddress}}'   => implode(', ',$restarauntAddress),
            '{{restaurantCity}}'      => $form['restaurant_city'],
            '{{restaurantPostcode}}'  => $form['restaurant_postcode'],
            '{{restaurantPhone}}'     => $form['restaurant_phone'],
            //'{{restaurantEmail}}'   => $form['restaurant_email'],
            '{{restaurantCuisine}}'   => implode(', ',$restarauntCuisine),
            '{{contactFirstName}}'    => $form['first_name'],
            '{{contactLastName}}'     => $form['last_name'],
            '{{contactRole}}'         => $form['role'],
            '{{contactEmail}}'        => $form['email'],
            '{{contactPhone}}'        => $form['contact_phone'],
            '{{siteDomain}}'          => $client['url']
        ];

        $emailTemplateModel = self::getEmailTemplate(EmailType::RestarauntSignup, $client, $placeholders, $language, $emailTemplate);
        return self::sendOneFromTemplate($client['contact_email'], $emailTemplateModel);
    }

    /**
     * Sends email with order details.
     *
     * @param \common\models\Client $client       Client model whose service use customer with $user model
     * @param \common\models\User   $user         Customer model
     * @param string                $orderDetails Order details string with items table, order details, etc.
     *
     * @return boolan whether email was sent or not
     *
     * @throws NotFoundHttpException
     */
    public static function sendTransferringToRestaurantNotification($client, $user, $order, $orderDetails)
    {
        $billingData  = unserialize($order->billing_address_data);
        $deliveryData = unserialize($order->delivery_address_data);
        $voucherCode  = '---------';

        if ($order->voucher_data) {
            $voucherData = unserialize($order->voucher_data);
            $voucherCode = $voucherData['code'];
        }

        $placeholders = [
            '{{logoImage}}'                  => self::getClientLogo(),
            '{{dineinPhone}}'                => Yii::$app->params['supportPhone'],
            '{{dineinEmail}}'                => Yii::$app->params['supportEmail'],
            '{{serviceBaseUrl}}'             => $order['delivery_provider'],
            '{{UtensilsInfo}}'               => $order['is_utensils'] ? 'Utensils are needed' : 'No utensils needed',
            '{{AdditionalrequirementsInfo}}' => $order['member_comment'] ? $order['member_comment'] : '<b>None</b>',
            '{{BillingAddress}}'             => FormatHelper::formatAddress($billingData),
            '{{BillingMobile}}'              => $billingData['phone'],
            '{{BillingEmail}}'               => $billingData['email'],
            '{{DeliveryAddress}}'            => FormatHelper::formatAddress($deliveryData),
            '{{DeliveryMobile}}'             => $deliveryData['phone'],
            '{{DeliveryEmail}}'              => $deliveryData['email'],
            '{{restaurantAdress}}'           => ($order->restaurant) ? FormatHelper::formatRestaurantAddress($order->restaurant) : '',
            '{{restaurantPhone}}'            => ($order->restaurant && $order->restaurant->pickupAddress->phone) ? $order->restaurant->pickupAddress->phone : '',
            '{{restaurantVat}}'              => ($order->restaurant) ? $order->restaurant->vat_number : '',
            '{{promoDiscount}}'              => $voucherCode,
            '{{deliveryDetails}}'            => ($order->restaurant) ? $order->restaurant->name : '',
            '{{deliveryType}}'               => in_array($order['delivery_type'],[DeliveryType::DeliveryAsap,DeliveryType::CollectionAsap]) ? ' DELIVERED - ASAP ' : ' DELIVERED - LATER on '.date('d/m/Y', strtotime($order->later_date_from)).' between <b> '.date('H:i', strtotime($order->later_date_from)).' to '.date('H:i', strtotime($order->later_date_to)).'</b>',
            '{{deliveryTypeSubject}}'        => in_array($order['delivery_type'],[DeliveryType::DeliveryAsap,DeliveryType::CollectionAsap]) ? ' DELIVERED - ASAP ' : ' DELIVERED - LATER on '.date('d/m/Y', strtotime($order->later_date_from)).' between '.date('H:i', strtotime($order->later_date_from)).' to '.date('H:i', strtotime($order->later_date_to)),
            '{{orderDetails}}'               => $orderDetails,
            '{{orderNumber}}'                => $order['order_number'],
            '{{siteDomain}}'                 => $client['url']
        ];

        $emailTemplateModel = self::getEmailTemplate(EmailType::TransferringToRestaurant, $client, $placeholders);
        return self::sendOneFromTemplate($user->username, $emailTemplateModel);
    }

    /**
     * Sends email with information about customer order confirm.
     *
     * @param \common\models\Client $client   Client model whose service use customer with $user model
     * @param \common\models\User   $user     Customer model
     * @param \common\models\Order  $order    Customer's order model
     * @param string                $trackUrl Url for tracking customer's order status
     *
     * @return boolan whether email was sent or not
     *
     * @throws NotFoundHttpException
     */
    public static function sendOrderConfirmed($client, $user, $order, $trackUrl)
    {
        $placeholders = [
            '{{logoImage}}'   => self::getClientLogo(),
            '{{orderNumber}}' => $order['order_number'],
            '{{trackOrder}}'  => $trackUrl,
            '{{siteDomain}}'  => $client['url']
        ];
        $emailTemplateModel = self::getEmailTemplate(EmailType::OrderConfirmed, $client, $placeholders);
        return self::sendOneFromTemplate($user->username, $emailTemplateModel);
    }

    /**
     * Sends email with notifications that user's order is going to be delivered.
     *
     * @param \common\models\Client $client   Client model whose service use customer with $user model
     * @param \common\models\User   $user     Customer model
     * @param \common\models\Order  $order    Customer's order model
     * @param string                $trackUrl Url for tracking customer's order status
     *
     * @return boolan whether email was sent or not
     *
     * @throws NotFoundHttpException
     */
    public static function sendFoodEnRouteNotification($client, $user, $order, $trackUrl)
    {
        $placeholders = [
            '{{logoImage}}'   => self::getClientLogo(),
            '{{orderNumber}}' => $order['order_number'],
            '{{trackOrder}}'  => $trackUrl,
            '{{siteDomain}}'  => $client['url']
        ];
        $emailTemplateModel = self::getEmailTemplate(EmailType::FoodEnRoute, $client, $placeholders);
        return self::sendOneFromTemplate($user->username, $emailTemplateModel);
    }

    /**
     * Sends email with information that order has been delivered.
     *
     * @param \common\models\Client $client   Client model whose service use customer with $user model
     * @param \common\models\User   $user     Customer model
     * @param \common\models\Order  $order    Customer's order model
     * @param string                $trackUrl Url for tracking customer's order status
     *
     * @return boolan whether email was sent or not
     *
     * @throws NotFoundHttpException
     */
    public static function sendDeliveredOrder($client, $user, $order, $trackUrl)
    {
        $placeholders = [
            '{{logoImage}}'   => self::getClientLogo(),
            '{{orderNumber}}' => $order['order_number'],
            '{{trackOrder}}'  => $trackUrl,
            '{{siteDomain}}'  => $client['url']
        ];
        $emailTemplateModel = self::getEmailTemplate(EmailType::Delivered, $client, $placeholders);
        return self::sendOneFromTemplate($user->username, $emailTemplateModel);
    }

    /**
     * Sends email with information that order payment was received.
     *
     * @param \common\models\Client $client   Client model whose service use customer with $user model
     * @param \common\models\User   $user     Customer model
     * @param \common\models\Order  $order    Customer's order model
     * @param string                $trackUrl Url for tracking customer's order status
     *
     * @return boolan whether email was sent or not
     *
     * @throws NotFoundHttpException
     */
    public static function sendPaymentReceived($client, $user, $order, $trackUrl)
    {
        $placeholders = [
            '{{logoImage}}'   => self::getClientLogo(),
            '{{orderNumber}}' => $order['order_number'],
            '{{trackOrder}}'  => $trackUrl,
            '{{siteDomain}}'  => $client['url']
        ];
        $emailTemplateModel = self::getEmailTemplate(EmailType::PaymentReceived, $client, $placeholders);
        return self::sendOneFromTemplate($user->username, $emailTemplateModel);
    }

    /**
     * Sends email with information that order payment was received.
     *
     * @param \common\models\Client       $client      Client model whose service use customer with $user model
     * @param \common\models\Restaraunt   $restaraunt  Restaraunt model
     * @param \common\models\Order        $order       Customer's order model
     * @param string                      $trackUrl    Url for tracking customer's order status
     *
     * @return boolan whether email was sent or not
     *
     * @throws NotFoundHttpException
     */
    public static function sendReadyBy($client, $restaraunt, $order, $orderDetails)
    {
        $billingData  = unserialize($order->billing_address_data);
        $deliveryData = unserialize($order->delivery_address_data);
        $voucherCode  = '---------';

        if ($order->voucher_data) {
            $voucherData = unserialize($order->voucher_data);
            $voucherCode = $voucherData['code'];
        }

        $placeholders = [
            '{{logoImage}}'                  => self::getClientLogo(),
            '{{dineinPhone}}'                => Yii::$app->params['supportPhone'],
            '{{dineinEmail}}'                => Yii::$app->params['supportEmail'],
            //'{{serviceBaseUrl}}'             => $order['delivery_provider'],
            '{{orderReadyByTime}}'           => date("H:i", strtotime($order->ready_by)),
            '{{UtensilsInfo}}'               => $order['is_utensils'] ? 'Utensils are needed' : 'No utensils needed',
            '{{AdditionalrequirementsInfo}}' => $order['member_comment'] ? $order['member_comment'] : '<b>None</b>',
            '{{BillingAddress}}'             => FormatHelper::formatAddress($billingData),
            '{{BillingMobile}}'              => $billingData['phone'],
            '{{BillingEmail}}'               => $billingData['email'],
            '{{DeliveryAddress}}'            => FormatHelper::formatAddress($deliveryData),
            '{{DeliveryMobile}}'             => $deliveryData['phone'],
            '{{DeliveryEmail}}'              => $deliveryData['email'],
            '{{restaurantAdress}}'           => ($order->restaurant) ? FormatHelper::formatRestaurantAddress($order->restaurant) : '',
            '{{restaurantPhone}}'            => ($order->restaurant && $order->restaurant->pickupAddress->phone) ? $order->restaurant->pickupAddress->phone : '',
            '{{restaurantVat}}'              => ($order->restaurant) ? $order->restaurant->vat_number : '',
            '{{promoDiscount}}'              => $voucherCode,
            '{{restaurantName}}'             => ($order->restaurant) ? $order->restaurant->name : '',
            '{{deliveryType}}'               => in_array($order['delivery_type'],[DeliveryType::DeliveryAsap,DeliveryType::CollectionAsap]) ? ' DELIVERED - ASAP ' : ' DELIVERED - LATER on '.date('d/m/Y', strtotime($order->later_date_from)).' between <b> '.date('H:i', strtotime($order->later_date_from)).' to '.date('H:i', strtotime($order->later_date_to)).'</b>',
            '{{deliveryTypeSubject}}'        => in_array($order['delivery_type'],[DeliveryType::DeliveryAsap,DeliveryType::CollectionAsap]) ? ' DELIVERED - ASAP ' : ' DELIVERED - LATER on '.date('d/m/Y', strtotime($order->later_date_from)).' between '.date('H:i', strtotime($order->later_date_from)).' to '.date('H:i', strtotime($order->later_date_to)),
            '{{orderDetails}}'               => $orderDetails,
            '{{orderNumber}}'                => $order['order_number'],
            '{{siteDomain}}'                 => $restaraunt->client ? $restaraunt->client->url : ''
        ];

        if($restaraunt && $restarauntContact = RestaurantContactOrder::find()->where('restaurant_id=:restaurant_id AND type=:Email AND record_type=:Active',[
                ':restaurant_id'=>$restaraunt['id'],
                ':Active'=>RecordType::Active,
                ':Email'=>RestaurantContactOrderType::Email])
                ->one())
        {
            $emailTemplateModel = self::getEmailTemplate(EmailType::ReadyBy, $restaraunt->client, $placeholders);
            return self::sendOneFromTemplate($restarauntContact->email, $emailTemplateModel);
        }
        return false;
    }

    /**
     * Sends email with information that order has been cancelled.
     *
     * @param \common\models\Client $client   Client model whose service use customer with $user model
     * @param \common\models\User   $user     Customer model
     * @param \common\models\Order  $order    Customer's order model
     * @param string                $trackUrl Url for tracking customer's order status
     *
     * @return boolean whether email was sent or not
     *
     * @throws NotFoundHttpException
     */
    public static function sendCancellOrder($client, $user, $order)
    {
        $placeholders = [
            '{{logoImage}}'   => self::getClientLogo(),
            '{{orderNumber}}' => $order['order_number'],
            '{{siteDomain}}'  => $client['url']
        ];
        $emailTemplateModel = self::getEmailTemplate(EmailType::Cancellation, $client, $placeholders);
        return self::sendOneFromTemplate($user->username, $emailTemplateModel);
    }

    /**
     * Sends email with information that order has been cancelled.
     *
     * @param \common\models\Client      $client      Client model whose service use customer with $user model
     * @param \common\models\Restaraunt  $restaraunt  Restaraunt model
     * @param \common\models\Order       $order       Customer's order model
     * @param string                     $trackUrl    Url for tracking customer's order status
     *
     * @return boolan whether email was sent or not
     *
     * @throws NotFoundHttpException
     */
    public static function sendCancellOrderToRestaraunt($client, $restaraunt, $order)
    {
        $placeholders = [
            '{{logoImage}}'   => self::getClientLogo(),
            '{{orderNumber}}' => $order['order_number'],
            '{{siteDomain}}'  => $restaraunt->client ? $restaraunt->client->url : ''
        ];
        if($restaraunt && $restarauntContact = RestaurantContactOrder::find()->where('restaurant_id=:restaurant_id AND type=:Email AND record_type=:Active',[
                ':restaurant_id'=>$restaraunt['id'],
                ':Active'=>RecordType::Active,
                ':Email'=>RestaurantContactOrderType::Email])
                ->one())
        {
            $emailTemplateModel = self::getEmailTemplate(EmailType::CancellationToRestaraunt, $restaraunt->client, $placeholders);
            return self::sendOneFromTemplate($restarauntContact->email, $emailTemplateModel);
        }
        return false;
    }

    /**
     * Return email template for particular customer for choosed type and language.
     *
     * @param string                $emailType    Type of email. See \common\enums\EmailType
     * @param \common\models\Client $client       Client email template will be used for sending email to customer.
     * @param array                 $placeholders
     * @param mixed                 $language_id  Code of language which will be used for email
     *
     * @return \common\models\EmailTemplate
     *
     * @throws NotFoundHttpException
     */
    private static function getEmailTemplate($emailType, $client, $placeholders, $language_id = null, $emailTemplate = null)
    {
        if (!$language_id) {
            $language_id = Yii::$app->globalCache->getLanguageId(Yii::$app->translationLanguage->language);
        }

        if (!isset($emailTemplate)) {
            $emailTemplate = EmailTemplate::find()->where('email_type = :email_template AND client_id = :client_id AND language_id = :language_id', [
                ':email_template' => $emailType,
                ':client_id'      => $client['id'],
                ':language_id'    => $language_id
            ])->one();
        }

        if ($emailTemplate === null) {
            throw new NotFoundHttpException(T::e('Email template was not found.'));
        }

        $emailTemplate->content = str_replace(array_keys($placeholders), array_values($placeholders), $emailTemplate->content);
        $emailTemplate->title   = str_replace(array_keys($placeholders), array_values($placeholders), $emailTemplate->title);

        return $emailTemplate;
    }

    /**
     * Returns path to default client logo image.
     *
     * @return string
     */
    private static function getClientLogo()
    {
        return Yii::$app->params['images_base_url'] . '/images/email_client_logo_default.png';
    }

    /**
     * Send export status email
     *
     * @param OrderExport $export
     * @param string      $fileName
     * @param \DateTime   $start
     * @param \DateTime   $end
     * @return bool
     * @throws NotFoundHttpException
     */
    public static function sendOrderExported(OrderExport $export, $fileName, \DateTime $start,\DateTime $end)
    {
        $placeholders = [
            '{{exportTypeTitle}}' => $export->type == OrderExportType::NewOrders ? 'New Orders' : 'New Users',
            '{{fileName}}'        => $fileName,
            '{{end}}'             => $end->format('Y-m-d H:i:s'),
            '{{start}}'           => $start->format('Y-m-d H:i:s'),
        ];
        $model = new \common\models\EmailTemplate;
        $model->email_type = EmailType::OrdersExported;
        $model->title = \common\enums\EmailType::getEmailSubjects()[EmailType::OrdersExported];
        $model->from_email = 'admin@dinein.co.uk';
        $model->from_name = 'Admin';
        $model->bcc = '';
        $model->cc = '';
        $model->content = Yii::$app->controller->renderPartial('@common/mail/console/'.EmailType::OrdersExported);
        $model->language_id = Yii::$app->globalCache->getDefaultLanguageId();
        $emailTemplateModel = self::getEmailTemplate('', 0, $placeholders,null,$model);
        $emails = explode(',',$export->email);
        $emails = array_map('trim',$emails);
        foreach($emails as $email){
            self::sendOneFromTemplate($email, $emailTemplateModel);
        }
    }
}