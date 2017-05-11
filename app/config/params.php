<?php

$params = [
    'site_id' => 'default',
    'ewa' => [
        'host' => 'https://ewa.oh.ua/ewa/api/v3/',
        //'login' => 'maryna.leschenko@otpbank.com.ua',
        //'password' => '1111',
        //'login' => 'l.otp@ukr.net',
        //'password' => '77187719',
//        'login' => 'OTPBANK@vuso.ua',
//        'password' => '77187719',
        'login' => 'sales@oh.ua',
        'password' => 'br&544Dkl0#a',
        'cookie_file' => realpath(__DIR__.'/..').'/runtime/ewa.cookie.otp.txt'
    ],

];

//if (isset($_SERVER['HTTP_HOST']))
//{
//    switch ($_SERVER['HTTP_HOST'])
//    {
//        case 'otp.inswidget.vuso.ua':
//        case 'otp.vuso.local':
//            $params['site_id'] = 'otp';
//            break;
//        case 'avtoradosti.inswidget.vuso.ua':
//        case 'avtoradosti.vuso.local':
//            $params['site_id'] = 'avtoradosti';
//            $params['ewa']['login'] = 'avtoradosti@vuso.ua';
//            $params['ewa']['password'] = '77187719';
//            $params['ewa']['cookie_file'] = realpath(__DIR__.'/..').'/runtime/ewa.cookie.avtoradosti.txt';
//            break;
//        default;
//    }
//}

return $params;