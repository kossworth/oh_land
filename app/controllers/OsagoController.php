<?php

namespace app\controllers;

use Yii;
use app\components\ewa;
use app\models\AutoCategories;
use app\models\Orders;
use app\models\Company;

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
            ])->andFilterWhere([AutoCategories::tableName().'.id' => $category_id])->limit(1)->one();
        
        if(is_null($auto_category))
        {
            throw new \yii\db\Exception('Unknown category!');
        }
        
        $tariff_options = [
            'autoCategory' => $auto_category['auto_code'],
            'bonusMalus' => 0.8,
            'franchise' => $get['franshiza'] ? (int)$get['franshiza'] : 0,
            'customerCategory' => 'NATURAL',
            'dateFrom' => date('Y-m-d', strtotime('+1 day')),
            'dateTo' => date('Y-m-d', strtotime('+1 year')),
            'zone' => $get['city'] ? (int)$get['city'] : 1,
            'taxi' => $get['notTaxi'] ? (bool)$get['notTaxi'] : false,
            'usageMonths' => 0,
            'driveExp' => false,
        ];
        
        $propositions = ewa\find::osago($tariff_options);
        $session->set('osago_search_data', json_encode($tariff_options));
        $session->set('search_city', $get['cityName']);
        
        $companies = Company::find()
                ->joinWith(['osago'], true)
//                ->where([Company::tableName().'.active_osago' => 1])
//                ->asArray()
                ->all();
        
        $result_propositions = [];
        
        foreach ($propositions as $prop)
        {
            $prop['company'] = null;
            foreach ($companies as $key => $comp)
            {
                if($comp->ewa_id == $prop['tariff']['insurer']['id'])
                {
                    $prop['company'] = $comp;
                    $prop['discount_sum'] = round($prop['tariff']['brokerDiscount'] * $prop['payment']);
                    array_push($result_propositions, $prop);
                    unset($companies[$key]); // удаляем из массива компанию, которую добавили в массив предложений
                    break;
                }
            }
        }

//        if(empty($propositions)) { 
//            return null; 
//        }
//        var_dump($companies);
//        var_dump('_________');
//        die();
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
        $search_data = $session->get('osago_search_data') ? $session->get('osago_search_data') : [];
        $propositions = ewa\find::osago(json_decode($search_data, true));
        
        $order->search  = $search_data;
        $order->offer   = $propositions[$counter] ? json_encode($propositions[$counter], JSON_UNESCAPED_UNICODE) : null;
        if($order->save(false))
        {
            return $this->renderAjax('form_order.twig', []);
        }
        else
        {
            return ['status' => false, 'msg' => serialize($order->getErrors())];
        }
    }

}