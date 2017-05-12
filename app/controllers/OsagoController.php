<?php

namespace app\controllers;

use Yii;
use app\components\ewa;
use app\models\AutoCategories;
use app\models\Orders;

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
    
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionSendRequest()
    {
//        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if(!$request->isAjax)
        {
            throw new \yii\web\BadRequestHttpException('Wrong request!', 400);
        }
        $session = Yii::$app->session;
        $get = $request->get();
        
//        $test = ewa\find::city('Одесса');
        
        $category_id = $get['type'] ? (int)$get['type'] : 1;
        $auto_category = AutoCategories::find()->select(AutoCategories::tableName().'.auto_code')
                ->andFilterWhere([AutoCategories::tableName().'.id' => $category_id])->limit(1)->one();
        
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
//            'taxi' => $get['notTaxi'] ? (bool)$get['notTaxi'] : false,
            'taxi' => false,
            'usageMonths' => 0,
            'driveExp' => false,
        ];
        
        $propositions = ewa\find::osago($tariff_options);
        $session->set('osago_search_data', json_encode($tariff_options));
//        if(empty($propositions)) { 
//            return null; 
//        }
//        
//        $b = json_decode($session->get('osago_search_data'));
//        $c = yii\helpers\ArrayHelper::toArray($b);
//        var_dump($c);
//        
//        var_dump($propositions);
//        die('yeah');
        return $this->renderPartial('osago_propositions.twig', [
            'propositions' => $propositions
        ]);
    }
    
    public function actionCreateOsagoOrder()
    {
        if(Yii::$app->request->isAjax)
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
        $search_data = $session->get('osago_search_data') ? $session->get('osago_search_data') : null;
        $propositions = ewa\find::osago(\yii\helpers\ArrayHelper::toArray(json_decode($search_data)));
        $order->search = $search_data;
        var_dump($propositions); die('ggggg');
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