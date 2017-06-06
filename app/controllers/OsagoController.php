<?php

namespace app\controllers;

use Yii;
use app\components\ewa;
use app\components\MailComponent;
use app\models\AutoCategories;
use app\models\Orders;
use app\models\Company;
use app\models\NpCities;

class OsagoController extends \app\components\BaseController
{
    
    public function behaviors() {
        return [
            'verbs' => [
                'class'   => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'index'                     => ['get'],
                    'send-request'              => ['get'],
                    'create-osago-order'        => ['post', 'get'],
                    'osago-self-order'          => ['post'],
                    'osago-phone-order'         => ['post'],
                    'osago-doc-order'           => ['post'],
                    'osago-change-data'         => ['get'],
                ]
            ],
        ];
    }
    
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false; 
        return parent::beforeAction($action);
    }
    
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionSendRequest()
    {
//        if(!Yii::$app->request->isAjax)
//        {
//            throw new \yii\web\BadRequestHttpException('Wrong request!', 400);
//        }
        $session        = Yii::$app->session;
        $get            = Yii::$app->request->get();
        $view           = 'osago_propositions.twig';
        $category_id    = $get['type'] ? (int)$get['type'] : 1;
        
        $auto_category  = AutoCategories::find()->select([
                            AutoCategories::tableName().'.auto_code',
                            AutoCategories::tableName().'.name_object_rus',
                            AutoCategories::tableName().'.name_param_rus',
                        ])->andFilterWhere([AutoCategories::tableName().'.id' => $category_id])
                            ->asArray()
                            ->limit(1)->one();
        
        if(is_null($auto_category))
        {
            throw new \yii\db\Exception('Unknown category!');
        }
        
        $tariff_options = [
            'autoCategory'          => $auto_category['auto_code'],
            'bonusMalus'            => 0.8,
            'franchise'             => $get['franshiza'] ? (int)$get['franshiza'] : 0,
            'customerCategory'      => 'NATURAL',
            'dateFrom'              => date('Y-m-d', strtotime('+1 day')),
            'dateTo'                => date('Y-m-d', strtotime('+1 year')),
            'zone'                  => $get['zone'] ? (int)$get['zone'] : 1,
            'taxi'                  => false,
//            'taxi'                  => ($get['notTaxi'] == 1) ? false : true,
            'usageMonths'           => 0,
            'driveExp'              => false,
            'additionalLimit'       => false,
        ];
        
        $propositions = ewa\find::osago($tariff_options);

        // если предложений не найдено - ищем предложение с теми же параметрами, но без установленной франшизы
        if(empty($propositions))
        {
            unset($tariff_options['franchise']);
            $propositions   = ewa\find::osago($tariff_options);
            $view           = 'osago_propositions_empty.twig';
        }
        
        $tariff_options['city'] = ['id' => $get['city'], 'zone' => $get['zone'], 'name' => $get['cityName']];

        if($session->has('osago_search_data'))
        {
            $session->remove('osago_search_data');
        }
        if($session->has('regCity'))
        {
            $session->remove('regCity');
        }
        
        $session->set('osago_search_data', json_encode($tariff_options, JSON_UNESCAPED_UNICODE));
        $session->set('regCity', $get['cityName']);
        
        $companies = Company::find()
                ->joinWith(['osago'], true)
                ->all();
        
        $result_propositions = [];
        
        foreach ($propositions as $prop)
        {
            $prop['company']    = null;
            $discount           = isset($prop['tariff']['brokerDiscount']) ? $prop['tariff']['brokerDiscount'] : 0;
            foreach ($companies as $key => $comp)
            {
                if($comp->ewa_id == $prop['tariff']['insurer']['id'])
                {
                    $prop['company']        = $comp;
                    $prop['full_sum']       = $prop['payment'];
                    $prop['discount_sum']   = round($discount * $prop['payment'], PHP_ROUND_HALF_UP);
                    $prop['payment']        = round($prop['payment'] - $prop['discount_sum'], PHP_ROUND_HALF_UP);
                    array_push($result_propositions, $prop);
                    unset($companies[$key]); // удаляем из массива компанию, которую добавили в массив предложений
                    break;
                }
            }
        }

        return $this->renderPartial($view, [
            'propositions'  => $result_propositions,
            'auto_category' => $auto_category,
            'city_name'     => $get['cityName'],
        ]);
    }
    
    public function actionCreateOsagoOrder()
    {
//        if(!Yii::$app->request->isAjax)
//        {
//            throw new \yii\web\BadRequestHttpException('Wrong request!', 400);
//        }
        $session            = Yii::$app->session;
        $counter            = Yii::$app->request->post('counter') ? (int)Yii::$app->request->post('counter') : 0;
        $order              = Orders::getCurrent('osago');
        if(!is_object($order))
        {
            throw new \yii\base\Exception('Havent order!');
        }
        $search_data        = $session->get('osago_search_data') ? $session->get('osago_search_data') : '';
        $propositions       = ewa\find::osago(json_decode($search_data, true));
        
        $order->search      = $search_data;
        $order->offer       = $propositions[$counter] ? json_encode($propositions[$counter], JSON_UNESCAPED_UNICODE) : null;
        $order->last_stage  = 'select_proposition';
        if($order->save(false))
        {
            $regions = NpCities::find()->where(['parent_id' => -1])
                    ->orderBy(NpCities::tableName().'.name_1 ASC')->asArray()->all();
            return $this->renderAjax('form_order.twig', [
                'regions' => $regions,
            ]);
        }
        else
        {
            return ['status' => false, 'msg' => serialize($order->getErrors())];
        }
    }
    
    public function actionOsagoSelfOrder()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//        if(!Yii::$app->request->isAjax)
//        {
//            throw new \yii\web\BadRequestHttpException('Wrong request!', 400);
//        }
        
        $post   = Yii::$app->request->post();
        
        $order  = Orders::getCurrent('osago');
        
        $order_info = [];
        $order_info['customer'] = [
            'code'          => isset($post['inn']) ? strip_tags(trim($post['inn'])) : 0,
            'name_last'     => isset($post['lastName']) ? strip_tags(trim($post['lastName'])) : 0,
            'name_first'    => isset($post['firstName']) ? strip_tags(trim($post['firstName'])) : 0,
//            'name_middle' => isset($post['name_middle']) ? strip_tags(trim($post['name_middle'])) : 0,
            'address'       => isset($post['address']) ? strip_tags(trim($post['address'])) : 0,
            'phone'         => isset($post['phone']) ? strip_tags(trim($post['phone'])) : 0,
            'email'         => isset($post['email']) ? strip_tags(trim($post['email'])) : 0,
//            'birth_date' => isset($post['birth_date']) ? (int)$post['birth_date'] : 0,
        ];
        $order_info['transport'] = [
            'model_id'      => isset($post['modelId']) ? (int)$post['modelId'] : 0,
            'vendor_id'     => isset($post['brandId']) ? (int)$post['brandId'] : 0,
            'model'         => isset($post['model']) ? strip_tags(trim($post['model'])) : 0,
            'vendor'        => isset($post['brand']) ? strip_tags(trim($post['brand'])) : 0,
            'vin_number'    => isset($post['chassis']) ? strip_tags(trim($post['chassis'])) : 0,
            'gov_number'    => isset($post['plateNum']) ? strip_tags(trim($post['plateNum'])) : 0,
            'year'          => isset($post['year']) ? (int)$post['year'] : 0,
        ];
        $order_info['contract'] = [
            'comment'       => isset($post['commentBySelf']) ? strip_tags(trim($post['commentBySelf'])) : 0,
            'start_date'    => isset($post['date']) ? strip_tags(trim($post['date'])) : 0,
        ];
        
        $order->info        = json_encode($order_info, JSON_UNESCAPED_UNICODE);
        $delivery_type      = isset($post['deliveryMode']) ? strip_tags(trim($post['deliveryMode'])) : '';
        
        switch ($delivery_type)
        {
            case 'bySelf':
                $delivery = ['type' => 'Самовывоз', 'city' => '', 'address' => ''];
                break;
            case 'byCourier':
                $delivery_city = isset($post['deliveryCity']) ? strip_tags(trim($post['deliveryCity'])) : '';
                $delivery_address = isset($post['deliveryAddr']) ? strip_tags(trim($post['deliveryAddr'])) : '';
                $delivery = ['type' => 'Доставка курьером', 'city' => $delivery_city, 'address' => $delivery_address];
                break;
            case 'byNP':
                $delivery_city = isset($post['delivCityNp']) ? strip_tags(trim($post['delivCityNp'])) : '';
                $delivery_address = isset($post['delivDivisionIdNP']) ? strip_tags(trim($post['delivDivisionIdNP'])) : '';
                $delivery = ['type' => 'Доставка Новой Почтой', 'city' => $delivery_city, 'address' => $delivery_address];
                break;
            default :
                $delivery = ['type' => 'Самовывоз', 'city' => '', 'address' => ''];
        }
        
        $fname              = isset($post['firstName']) ? strip_tags(trim($post['firstName'])) : '';
        $lname              = isset($post['lastName']) ? strip_tags(trim($post['lastName'])) : '';
        $order->name        = $lname.' '.$fname;
        $order->phone       = isset($post['phone']) ? strip_tags(trim($post['phone'])) : '';
        $order->email       = isset($post['email']) ? strip_tags(trim($post['email'])) : '';
        $order->payment     = isset($post['pay']) ? strip_tags(trim($post['pay'])) : '';
        $order->comment     = isset($post['comment']) ? strip_tags(trim($post['comment'])) : '';
        $order->delivery    = json_encode($delivery, JSON_UNESCAPED_UNICODE);
        $order->last_stage  = 'save_self_order';
        $order->done        = 1;
        $ewa_status         = ewa\save::osago($order);
        $order->result      = json_encode($ewa_status, JSON_UNESCAPED_UNICODE);
        
        if($order->save(false))
        {
            MailComponent::unisenderMailsend('thanks_landing_order', $order->email, null, ['order_id' => $order->id]);
            MailComponent::unisenderMailsend('landing_order_manager', 'oh.ua.insurance1@gmail.com', 'Новый заказ ОСАГО на лендинге Oh.UA',[
                'user_name'         => $order->name,
                'user_phone'        => $order->phone,
                'user_email'        => $order->email,
                'type'              => $order->type,
                'info'              => $order->getTaskData(),
                'date'              => $order->last_active,
            ]);
            Yii::$app->session->destroy();
            $offer = json_decode($order->offer);
            
            $liqpay_button = '';
            // если оплата онлайн - тогда гененрим параметры Liqpay
            if($order->payment === 'card'){
                $liqpay_params = [
                    'currency'      => 'UAH',
                    'amount'        => $offer->discountedPayment,
                    'order_id'      => (string)$order->id,
                    'action'        => 'pay',
                    'description'   => 'OSAGO landing pay'
                ];
                $liqpay_button      = Yii::$app->liqpay->cnb_form($liqpay_params);    
            }

            return $this->renderAjax('thanks.twig', [
                'order'             => $order,
                'offer'             => $offer,
                'delivery'          => $delivery,
                'price'             => round($offer->discountedPayment, PHP_ROUND_HALF_UP),
                'liqpay_button'     => $liqpay_button,
            ]);
        } 
        else
        {
            return false;
        }
    }

    public function actionOsagoPhoneOrder()
    {
//        if(!Yii::$app->request->isAjax)
//        {
//            throw new \yii\web\BadRequestHttpException('Wrong request!', 400);
//        }
        $post               = Yii::$app->request->post();
        $order              = Orders::getCurrent('osago');
        $fname              = isset($post['firstName']) ? strip_tags(trim($post['firstName'])) : '';
        $lname              = isset($post['lastName']) ? strip_tags(trim($post['lastName'])) : '';
        $order->name        = $lname.' '.$fname;
        $order->phone       = isset($post['phone']) ? strip_tags(trim($post['phone'])) : '';
        $order->last_stage  = 'save_phone_order';
        $order->done        = 1;
        if($order->save(false))
        {
            Yii::$app->session->destroy();
            MailComponent::unisenderMailsend('landing_order_manager', 'oh.ua.insurance1@gmail.com', 'Новый заказ с лендинга ОСАГО на Oh.UA по телефону',[
                'user_name'     => $order->name,
                'user_phone'    => $order->phone,
                'user_email'    => $order->email,
                'type'          => $order->type,
                'info'          => $order->getTaskData(),
                'date'          => $order->last_active,
            ]);
            $offer              = json_decode($order->offer);
            
            $liqpay_button      = '';
            // если оплата онлайн - тогда гененрим параметры Liqpay
            if($order->payment == 'card'){
                $liqpay_params = [
                    'currency'      => 'UAH',
                    'amount'        => $offer->discountedPayment,
                    'order_id'      => (string)$order->id,
                    'action'        => 'pay',
                    'description'   => 'OSAGO landing pay'
                ];
                $liqpay_button = Yii::$app->liqpay->cnb_form($liqpay_params);    
            }
            
            return $this->renderAjax('thanks.twig', [
                'order'             => $order,
                'offer'             => $offer,
                'price'             => round($offer->discountedPayment, PHP_ROUND_HALF_UP),
                'liqpay_button'     => $liqpay_button,                
            ]);
        }
        else
        {
            return false;
        }
    }

    public function actionOsagoDocOrder()
    {
        $post           = Yii::$app->request->post();
        $order          = Orders::getCurrent('osago');
        $delivery_type  = isset($post['deliveryMode']) ? strip_tags(trim($post['deliveryMode'])) : '';
        
        switch ($delivery_type)
        {
            case 'bySelf':
                $delivery = ['type' => 'Самовывоз', 'city' => '', 'address' => ''];
                break;
            case 'byCourier':
                $delivery_city      = isset($post['delivCityName']) ? strip_tags(trim($post['delivCityName'])) : '';
                $delivery_address   = isset($post['deliveryAddr']) ? strip_tags(trim($post['deliveryAddr'])) : '';
                $delivery           = ['type' => 'Доставка курьером', 'city' => $delivery_city, 'address' => $delivery_address];
                break;
            case 'byNP':
                $delivery_city      = isset($post['delivCityNp']) ? strip_tags(trim($post['delivCityNp'])) : '';
                $delivery_address   = isset($post['delivDivisionIdNP']) ? strip_tags(trim($post['delivDivisionIdNP'])) : '';
                $delivery = ['type' => 'Доставка Новой Почтой', 'city' => $delivery_city, 'address' => $delivery_address];
                break;
            default :
                $delivery = ['type' => 'Самовывоз', 'city' => '', 'address' => ''];
        }
        
        $order->name            = isset($post['firstName']) ? strip_tags(trim($post['firstName'])) : '';
        $order->phone           = isset($post['phone']) ? strip_tags(trim($post['phone'])) : '';
        $order->email           = isset($post['email']) ? strip_tags(trim($post['email'])) : '';
        $order->payment         = isset($post['pay']) ? strip_tags(trim($post['pay'])) : '';
        $order->comment         = isset($post['comment']) ? strip_tags(trim($post['comment'])) : '';
        $order->delivery        = json_encode($delivery, JSON_UNESCAPED_UNICODE);
        $order->last_stage      = 'save_docs_order';
        $order->done            = 1;
        $upload_imgs            = '';
        if($_FILES['docScan']['error'][0] != 4 ) 
        {
            $count_files = count($_FILES['docScan']['name']);
            for($i = 0; $i < $count_files; $i++){

                $mime = $_FILES['docScan']['type'][$i];
                if($mime == '' || is_null($mime))
                {
                    break;
                }
                // проверяем mime-тип файлов
                if(in_array($mime, $order->acceptFilesTypes()))
                {
                    // если тип файлов подходящий - играем дальше. если нет - exception
                    $upload_dir = '../../../userfiles/landing_docs/';
                    $ext = pathinfo($_FILES['docScan']['name'][$i], PATHINFO_EXTENSION);
                    $file_name = $order->id.'.'.($i+1).'.'.$ext;
                    $upload_file = $upload_dir . basename($file_name);

                    // сохраняем файл и кидаем ексепшн, если не удалось сохранить
                    if(move_uploaded_file($_FILES['docScan']['tmp_name'][$i], $upload_file)){
                        $upload_imgs .= '/userfiles/landing_docs/'.$file_name.';';
                    } 
                    else 
                    {
                        throw new \yii\base\Exception('Cant move file!');
                    }
                } 
                else 
                {
                    throw new \yii\base\Exception('Недопустимый тип файла!');
                }
            }

            $order->files = $upload_imgs;
        }
            
        if ($order->save(false))  
        {
            MailComponent::unisenderMailsend('thanks_landing_order', $order->email, null, ['order_id' => $order->id]);

            MailComponent::unisenderMailsend('landing_order_manager', 'oh.ua.insurance1@gmail.com', 'Новый заказ ОСАГО с фото на лендинге Oh.UA',[
                'user_name'     => $order->name,
                'user_phone'    => $order->phone,
                'user_email'    => $order->email,
                'type'          => $order->type,
                'info'          => $order->getTaskData(),
                'date'          => $order->last_active,
            ]);            
            Yii::$app->session->destroy();
            $offer              = json_decode($order->offer);
            
            $liqpay_button      = '';
            // если оплата онлайн - тогда гененрим параметры Liqpay
            if($order->payment  == 'card'){
                $liqpay_params  = [
                    'currency'      => 'UAH',
                    'amount'        => $offer->discountedPayment,
                    'order_id'      => (string)$order->id,
                    'action'        => 'pay',
                    'description'   => 'OSAGO landing pay'
                ];
                $liqpay_button = Yii::$app->liqpay->cnb_form($liqpay_params);    
            }
            
            return $this->renderAjax('thanks.twig', [
                'order'             => $order,
                'offer'             => $offer,
                'delivery'          => $delivery,
                'price'             => round($offer->payment, PHP_ROUND_HALF_UP),
                'liqpay_button'     => $liqpay_button,
            ]);
        } else {
            return false;
        }
        
    }
    
    public function actionOsagoChangeData()
    {
//        if(!Yii::$app->request->isAjax)
//        {
//            throw new \yii\web\BadRequestHttpException('Wrong request!', 400);
//        }       
        $session = Yii::$app->session;
        if($session->has('osago_search_data'))
        {
            $search_data = $session['osago_search_data'];
        }
        $data               = json_decode($search_data);
        $auto_categories    = AutoCategories::find()->select([
                AutoCategories::tableName().'.id_auto_kind',
                AutoCategories::tableName().'.auto_code',
                AutoCategories::tableName().'.name_object_rus',
                AutoCategories::tableName().'.name_param_rus',
            ])->asArray()->all();
        
        return $this->renderAjax('change_data.twig', [
            'data'              => $data,
            'auto_categories'   => $auto_categories,
        ]);
    } 
    
    public function actionOsagoShowPropositions()
    {
//        if(!Yii::$app->request->isAjax)
//        {
//            throw new \yii\web\BadRequestHttpException('Wrong request!', 400);
//        }
        $session = Yii::$app->session;
       
        if($session->has('osago_search_data') && $session->has('regCity'))
        {
            $search_data = json_decode($session['osago_search_data']);
            $city = $session['regCity'];
        }
        else
        {
            return false;
        }

        $auto_category = AutoCategories::find()->select([
                AutoCategories::tableName().'.auto_code',
                AutoCategories::tableName().'.name_object_rus',
                AutoCategories::tableName().'.name_param_rus',
            ])->andFilterWhere([AutoCategories::tableName().'.auto_code' => $search_data->autoCategory])
                ->asArray()
                ->limit(1)->one();
        
        if(is_null($auto_category))
        {
            throw new \yii\db\Exception('Unknown category!');
        }
        
        $tariff_options = [
            'autoCategory'          => $auto_category['auto_code'],
            'bonusMalus'            => 0.8,
            'franchise'             => $search_data->franchise,
            'customerCategory'      => 'NATURAL',
            'dateFrom'              => date('Y-m-d', strtotime('+1 day')),
            'dateTo'                => date('Y-m-d', strtotime('+1 year')),
            'zone'                  => $search_data->zone,
            'taxi'                  => $search_data->taxi,
            'usageMonths'           => 0,
            'driveExp'              => false,
        ];
        
        $propositions   = ewa\find::osago($tariff_options);
        
        $companies      = Company::find()
                            ->joinWith(['osago'], true)
                            ->all();
        
        $result_propositions = [];
        
        foreach ($propositions as $prop)
        {
            $prop['company']    = null;
            $discount           = isset($prop['tariff']['brokerDiscount']) ? $prop['tariff']['brokerDiscount'] : 0;
            foreach ($companies as $key => $comp)
            {
                if($comp->ewa_id == $prop['tariff']['insurer']['id'])
                {
                    $prop['company']        = $comp;
                    $prop['discount_sum']   = round($discount * $prop['payment'], PHP_ROUND_HALF_UP);
                    $prop['payment']        = round($prop['payment'] - $prop['discount_sum'], PHP_ROUND_HALF_UP);
                    array_push($result_propositions, $prop);
                    unset($companies[$key]); // удаляем из массива компанию, которую добавили в массив предложений
                    break;
                }
            }
        }
		
        return $this->renderPartial('osago_propositions.twig', [
            'propositions'  => $result_propositions,
            'auto_category' => $auto_category,
            'city_name'     => $city,
        ]);
    }
    
}