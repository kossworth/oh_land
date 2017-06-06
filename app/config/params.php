<?php

$params = [
    'site_id' => 'default',
    'ewa' => [
        'host' => 'https://ewa.oh.ua/ewa/api/v3/',
        'login' => 'sales@oh.ua',
        'password' => 'br&544Dkl0#a',
        'cookie_file' => realpath(__DIR__.'/..').'/runtime/ewa.cookie.otp.txt'
    ],
    
    //	Параметры для LiqPay
    'liqpay' => [
	'public_key' => 'i93595413555',
	'private_key' => '69uugCkvXzVoPncyVoOaOljU1rVHlReY6Nl6WOaR'
    ]

];

return $params;