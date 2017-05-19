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
                    'create-osago-order'        => ['post'],
                    'osago-self-order'          => ['post'],
                    'osago-phone-order'         => ['post'],
                    'osago-doc-order'           => ['post'],
                ]
            ],
        ];
    }
    
    public function beforeAction($action)
    {
//        if (in_array($action->id, ['create-osago-order'])) {
            $this->enableCsrfValidation = false; 
//        }
        return parent::beforeAction($action);
    }
    
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionSendRequest()
    {
        $request = Yii::$app->request;
        if(!$request->isAjax)
        {
            throw new \yii\web\BadRequestHttpException('Wrong request!', 400);
        }
        $session = Yii::$app->session;
        $get = $request->get();
       
        $category_id = $get['type'] ? (int)$get['type'] : 1;
        
        $auto_category = AutoCategories::find()->select([
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
            'taxi'                  => $get['notTaxi'] ? (bool)$get['notTaxi'] : false,
            'usageMonths'           => 0,
            'driveExp'              => false,
        ];
        $propositions = ewa\find::osago($tariff_options);
//        var_dump($tariff_options); die();
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
//                ->asArray()
                ->all();
        
        $result_propositions = [];
        
        foreach ($propositions as $prop)
        {
            $prop['company'] = null;
            $discount = isset($prop['tariff']['brokerDiscount']) ? $prop['tariff']['brokerDiscount'] : 0;
            foreach ($companies as $key => $comp)
            {
                if($comp->ewa_id == $prop['tariff']['insurer']['id'])
                {
                    $prop['company'] = $comp;
                    $prop['discount_sum'] = round($discount * $prop['payment']);
                    $prop['payment'] = round($prop['payment'] - $prop['discount_sum']);
                    array_push($result_propositions, $prop);
                    unset($companies[$key]); // удаляем из массива компанию, которую добавили в массив предложений
                    break;
                }
            }
        }

        return $this->renderPartial('osago_propositions.twig', [
            'propositions'  => $result_propositions,
            'auto_category' => $auto_category,
            'city_name'     => $get['cityName'],
        ]);
    }
    
    public function actionCreateOsagoOrder()
    {
        if(!Yii::$app->request->isAjax)
        {
            throw new \yii\web\BadRequestHttpException('Wrong request!', 400);
        }
        $session = Yii::$app->session;
        $counter = Yii::$app->request->post('counter') ? (int)Yii::$app->request->post('counter') : 0;
        $order = Orders::getCurrent('osago');
        if(!is_object($order))
        {
            throw new \yii\base\Exception('Havent order!');
        }
        $search_data = $session->get('osago_search_data') ? $session->get('osago_search_data') : '';
        $propositions = ewa\find::osago(json_decode($search_data, true));
        
        $order->search  = $search_data;
        $order->offer   = $propositions[$counter] ? json_encode($propositions[$counter], JSON_UNESCAPED_UNICODE) : null;
        if($order->save(false))
        {
            $regions = NpCities::find()->where(['parent_id' => -1])
                    ->orderBy(NpCities::tableName().'.name_1 ASC')->asArray()->all();
            return $this->renderAjax('form_order.twig', [
                'regions' => $regions
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
        if(!Yii::$app->request->isAjax)
        {
            throw new \yii\web\BadRequestHttpException('Wrong request!', 400);
        }
        
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
        
        $order->info = json_encode($order_info, JSON_UNESCAPED_UNICODE);
        $delivery_type = isset($post['deliveryMode']) ? strip_tags(trim($post['deliveryMode'])) : '';
        
        switch ($delivery_type)
        {
            case 'bySelf':
                $delivery_city = '';
                $delivery_address = '';
                $del_type = 'Самовывоз';
                break;
            case 'byCourier':
                $delivery_city = isset($post['delivCityName']) ? strip_tags(trim($post['delivCityName'])) : '';
                $delivery_address = isset($post['deliveryAddr']) ? strip_tags(trim($post['deliveryAddr'])) : '';
                $del_type = 'Доставка курьером';
                break;
            case 'byNP':
                $delivery_city = isset($post['delivCityNP']) ? strip_tags(trim($post['delivCityNP'])) : '';
                $delivery_address = isset($post['delivFilialNP']) ? strip_tags(trim($post['delivFilialNP'])) : '';
                $del_type = 'Доставка Новой Почтой';
                break;
            default :
                $delivery_city = '';
                $delivery_address = '';
                $del_type = 'Самовывоз';
        }
               
        $fname = isset($post['firstName']) ? strip_tags(trim($post['firstName'])) : '';
        $lname = isset($post['lastName']) ? strip_tags(trim($post['lastName'])) : '';
        $order->name = $lname.' '.$fname;
        $order->phone = isset($post['phone']) ? strip_tags(trim($post['phone'])) : '';
        $order->email = isset($post['email']) ? strip_tags(trim($post['email'])) : '';
        $order->payment = isset($post['pay']) ? strip_tags(trim($post['pay'])) : '';
        $order->comment = isset($post['comment']) ? strip_tags(trim($post['comment'])) : '';
        $order->delivery = $delivery_city.' '.$delivery_address;
        
        $ewa_status = ewa\save::osago($order);
        $order->result = json_encode($ewa_status, JSON_UNESCAPED_UNICODE);
        
        if($order->save(false))
        {
            MailComponent::unisenderMailsend('thanks_landing_order', $order->email, ['order_id' => $order->id]);
            MailComponent::unisenderMailsend('landing_order_manager', 'kudrinskiy.y@vuso.ua', [
                'user_name' => $order->name,
                'user_phone' => $order->phone,
                'user_email' => $order->email,
                'type' => $order->type,
                'search' => print_r(json_decode($order->search, true), true),
                'offer' => print_r(json_decode($order->offer, true), true),
                'info' => print_r(json_decode($order->info, true), true),
                'date' => $order->last_active,
            ]);
            Yii::$app->session->destroy();
            $offer = json_decode($order->offer);
            return $this->renderAjax('thanks.twig', [
                'order' => $order,
                'price' => round($offer->payment),
                'delivery_type' => $del_type
            ]);
        } 
        else
        {
            return false;
        }
    }

    public function actionOsagoPhoneOrder()
    {
        if(!Yii::$app->request->isAjax)
        {
            throw new \yii\web\BadRequestHttpException('Wrong request!', 400);
        }
        $post   = Yii::$app->request->post();
        $order  = Orders::getCurrent('osago');
        $fname = isset($post['firstName']) ? strip_tags(trim($post['firstName'])) : '';
        $lname = isset($post['lastName']) ? strip_tags(trim($post['lastName'])) : '';
        $order->name = $lname.' '.$fname;
        $order->phone = isset($post['phone']) ? strip_tags(trim($post['phone'])) : '';
        if($order->save(false))
        {
            Yii::$app->session->destroy();
            MailComponent::unisenderMailsend('landing_order_manager', 'kudrinskiy.y@vuso.ua', [
                'user_name' => $order->name,
                'user_phone' => $order->phone,
                'user_email' => $order->email,
                'type' => $order->type,
                'search' => print_r(json_decode($order->search, true), true),
                'offer' => print_r(json_decode($order->offer, true), true),
                'info' => print_r(json_decode($order->info, true), true),
                'date' => $order->last_active,
            ]);
            $offer = json_decode($order->offer);
            return $this->renderAjax('thanks.twig', [
                'order' => $order,
                'price' => round($offer->payment),
            ]);
        }
        else
        {
            var_dump($order->getErrors());
            return false;
        }
    }

    public function actionOsagoDocOrder()
    {
        $post   = Yii::$app->request->post();
        $order  = Orders::getCurrent('osago');
        $delivery_type = isset($post['deliveryMode']) ? strip_tags(trim($post['deliveryMode'])) : '';
        
        switch ($delivery_type)
        {
            case 'bySelf':
                $delivery_city = '';
                $delivery_address = '';
                $del_type = 'Самовывоз';
                break;
            case 'byCourier':
                $delivery_city = isset($post['delivCityName']) ? strip_tags(trim($post['delivCityName'])) : '';
                $delivery_address = isset($post['deliveryAddr']) ? strip_tags(trim($post['deliveryAddr'])) : '';
                $del_type = 'Доставка курьером';
                break;
            case 'byNP':
                $delivery_city = isset($post['delivCityNP']) ? strip_tags(trim($post['delivCityNP'])) : '';
                $delivery_address = isset($post['delivFilialNP']) ? strip_tags(trim($post['delivFilialNP'])) : '';
                $del_type = 'Доставка Новой Почтой';
                break;
            default :
                $delivery_city = '';
                $delivery_address = '';
                $del_type = 'Самовывоз';
        }
               
        $order->name = isset($post['firstName']) ? strip_tags(trim($post['firstName'])) : '';
        $order->phone = isset($post['phone']) ? strip_tags(trim($post['phone'])) : '';
        $order->email = isset($post['email']) ? strip_tags(trim($post['email'])) : '';
        $order->payment = isset($post['pay']) ? strip_tags(trim($post['pay'])) : '';
        $order->comment = isset($post['comment']) ? strip_tags(trim($post['comment'])) : '';
        $order->delivery = $delivery_city.' '.$delivery_address;
        
        $upload_imgs = '';
        if($_FILES['docsScans']['error'][0] != 4 ) 
        {
            $count_files = count($_FILES['docsScans']['name']);
            for($i = 0; $i < $count_files; $i++){

//                    $mime = $_FILES['docsScans']['type'][$i];
                // првоеряем mime-тип файлов
//                    if($mime == 'image/jpeg' || $mime == 'image/pjpeg' )
//                    {
                    // если тип файлов подходящий - играем дальше. если нет - exception
                    $upload_dir = '../../../userfiles/landing_docs/';
                    $ext = pathinfo($_FILES['docsScans']['name'][$i], PATHINFO_EXTENSION);
                    $file_name = $order->id.'.'.$i.'.'.$ext;
                    $upload_file = $upload_dir . basename($file_name);

                    // сохраняем файл и кидаем ексепшн, если не удалось сохранить
                    if(move_uploaded_file($_FILES['docsScans']['tmp_name'][$i], $upload_file)){
                        $upload_imgs .= '/userfiles/landing_docs/'.$file_name.';';
                    } 
                    else 
                    {
                        throw new \yii\base\Exception('Cant move file!');
                    }
//                    } 
//                    else 
//                    {
//                        throw new \yii\base\Exception('Недопустимый тип файла!');
//                    }
            }

            $order->files = $upload_imgs;
        }
            
        if ($order->save(false))  
        {
            MailComponent::unisenderMailsend('thanks_landing_order', $order->email, ['order_id' => $order->id]);
            MailComponent::unisenderMailsend('landing_order_manager', 'kudrinskiy.y@vuso.ua', [
                'user_name' => $order->name,
                'user_phone' => $order->phone,
                'user_email' => $order->email,
                'type' => $order->type,
                'search' => print_r(json_decode($order->search, true), true),
                'offer' => print_r(json_decode($order->offer, true), true),
                'info' => print_r(json_decode($order->info, true), true),
                'date' => $order->last_active,
            ]);            
            Yii::$app->session->destroy();
            $offer = json_decode($order->offer);
            return $this->renderAjax('thanks.twig', [
                'order' => $order,
                'price' => round($offer->payment),
                'delivery_type' => $del_type
            ]);
        } else {
            var_dump($order->files);
            var_dump($order->getErrors());
        }
        
    }
    
}