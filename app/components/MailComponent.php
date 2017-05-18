<?php 

namespace app\components;

use Yii;

class MailComponent
{
    
    //  create view for email letter  
    public static function view_include($fileName, $vars = []) 
    {
	// Устанавливаем переменные
	foreach($vars as $key => $value)
            $$key = $value;

	// Генерация HTML в строку.
	ob_start();
	include $fileName;
	return ob_get_clean();	
    }
    
    
    public static function mailsend($data, $mail_template, $to, $subject)
    {
        if(!empty($data))
        {
            $message = Yii::$app->mailer->compose(['html' => $mail_template], ['data' => $data]) // a view rendering result becomes the message body here
                ->setFrom('admin@oh.ua')
                ->setTo($to)
                ->setSubject($subject)
                ->send();
            if($message)
            {
                return ['response' => 'good', 'message' => $message];
            }
            else
            {
                return ['response' => 'bad', 'message' => $message];
            }
        } 
        else 
        {
            return ['response' => 'bad', 'message' => 'data is empty!'];
        }
    }
    
    public static function unisenderMailsend($topic, $email_subject = '', $email_to = '', $data = [])
    {
        $api_key    = "6xr69wgc5ryjoc5yuyd1gn6qttsc9qhxjk6mco3o";
        $dir        = Yii::getAlias('@app'.DIRECTORY_SEPARATOR.'mail');

        $file       = Yii::getAlias($dir) . DIRECTORY_SEPARATOR . $topic.".php"; 
        $body       = self::view_include($file, $data);
        /*
         *Preparing email for sending by Unisender sendEmail method
         */
        $email_from_name        = 'VUSO';
        $email_from_email       = 'admin@oh.ua';
        $list_id                = 8182574;

        $request = [
           'api_key'               => $api_key,
           'sender'                => 'VUSO',
           'email'                 => $email_to,
           'sender_name'           => $email_from_name,
           'sender_email'          => $email_from_email,
           'subject'               => $email_subject,
           'body'                  => $body,
           'list_id'               => $list_id
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_URL, 'https://api.unisender.com/ru/api/sendEmail?format=json');
        $result = curl_exec($ch);

        if ($result)
        {
            $jsonObj = json_decode($result);
            if (null === $jsonObj)
            {
                return 'Invalid JSON';
            }
            elseif (!empty($jsonObj->error))
            {
                return sprintf('An error occured %s (code: %s)', $jsonObj->error, $jsonObj->code);
            }
            else
            {
                return 'Email message is sent. Message id ' . $jsonObj->result->email_id;
            }
        }
        else
        { 
            return 'API access error';
        }
    }
}