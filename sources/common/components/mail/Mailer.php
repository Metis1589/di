<?php
namespace common\components\mail;
 
use Yii;
 
class Mailer extends \yii\swiftmailer\Mailer
{
    public $from;

    public $redirectTo;

    public function sendOne($to, $subject, $mailPath, $params = null)
    {
        if (isset(Yii::$app->mailer->redirectTo) && !empty(Yii::$app->mailer->redirectTo)) {
            $subject .= ' !!! Original recipient: '. $to;
            $to = Yii::$app->mailer->redirectTo;
        }
        Yii::$app->mailer->compose($mailPath, $params)
            ->setFrom($this->from)
            ->setTo($to)
            ->setSubject($subject)
            ->send();
    }

    public function sendOneFromTemplate($to, $emailTemplateModel)
    {
        $subject = $emailTemplateModel->title;
        if (isset(Yii::$app->mailer->redirectTo) && !empty(Yii::$app->mailer->redirectTo)) {
            $subject .= ' !!! Original recipient: '. $to;
            $to = Yii::$app->mailer->redirectTo;
        }

        $message = Yii::$app->mailer->prepareTemplate($emailTemplateModel->content)
            ->setFrom($emailTemplateModel->from_email)
            ->setTo($to)
            ->setSubject($subject);

        if($emailTemplateModel->cc){
            $message->setCc($emailTemplateModel->cc);
        }
        if($emailTemplateModel->bcc){
            $message->setBcc($emailTemplateModel->bcc);
        }
        return $message->send();
    }

    protected function prepareTemplate($content){
        $message = $this->createMessage();
        $message->setHtmlBody($content);
        // remove style and script
        $html = preg_replace('~<((style|script))[^>]*>(.*?)</\1>~is', '', $content);
        // strip all HTML tags and decoded HTML entities
        // $text = html_entity_decode(strip_tags($content), ENT_QUOTES | ENT_HTML5, Yii::$app ? Yii::$app->charset : 'UTF-8');
        $text = $content;
        // improve whitespace
        $text = preg_replace("~^[ \t]+~m", '', trim($text));
        $text = preg_replace('~\R\R+~mu', "\n\n", $text);
        $message->setTextBody($text);
        return $message;
    }
}