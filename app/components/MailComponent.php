<?php 

namespace app\components;

use Yii;
use app\models\MailNotice;

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
    
    public static function unisenderMailsend($template_name = '', $email_to = '', $subject = null, $data = [])
    {
        $api_key    = "6pgjgyo3tec6xdosusk1mhpa7kcbo8cs7bxpppio";
		
        $template = MailNotice::find()->where([MailNotice::tableName().'.key' => $template_name])->limit(1)->one();
        if(is_null($template))
        {
            return 'Unknown template: '.$template_name;
        }
        
        $body = $template->body_1;
        if(is_array($data) && !empty($data))
        {
            foreach($data as $key => $value)
            {
                $body = str_replace('{'.$key.'}', $value, $body);
            }
        }
        
        if(is_null($subject) || empty($subject))
        {
            $subject = $template->theme_1;
        }
      
        /*
         *Preparing email for sending by Unisender sendEmail method
         */
        $email_from_name        = 'Oh.UA';
        $email_from_email       = 'sales@oh.ua';
        $list_id                = 5034770;
        
        $request = [
           'api_key'               => $api_key,
           'email'                 => $email_to,
           'sender_name'           => $email_from_name,
           'sender_email'          => $email_from_email,
           'subject'               => $subject,
           'body'                  => $body,
           'list_id'               => $list_id
        ];
        
        // проверяем, если пришло несколько емейлов (перечисленных через запятую)
        // тогда отдельно генерим параметры для UniSender
        if(mb_stripos($email_to, ',')) {
            $emails = explode(',', $email_to);
            foreach ($emails as $key => $email)
            {
                $request['email['.$key.']'] = $email;
            }
        } else {
            $request['email'] = $email_to;
        }
        
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
//                return 'Email message is sent. Message id ' . $jsonObj->result->email_id;
                return 'Email message is sent.';
            }
        }
        else
        { 
            return 'API access error';
        }
    }
}