<?php 

namespace app\components;

use Yii;

class MailComponent
{
    
    //  create view for email letter  
    public static function view_include($fileName, $vars = array()) {
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
        if(!empty($data)){
            $message = Yii::$app->mailer->compose(['html' => $mail_template], ['data' => $data]) // a view rendering result becomes the message body here
                ->setFrom('noreply@vagonka.com')
                ->setTo($to)
                ->setSubject($subject)
                ->send();

            if($message):
                return ['response' => 'good', 'message' => $message];
            else:
                return ['response' => 'bad', 'message' => $message];
            endif;

        } else {
            return ['response' => 'bad', 'message' => 'data is empty!'];
        }
    }

}