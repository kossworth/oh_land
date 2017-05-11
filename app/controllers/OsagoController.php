<?php

namespace app\controllers;

use Yii;
use app\components\ewa;
use app\models\AutoCategories;

class OsagoController extends \app\components\BaseController
{
    
    public function behaviors() {
        return [
            'verbs' => [
                'class'   => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'index'             => ['get'],
                    'send-request'      => ['get'],
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
//            'franchise' => $get['franshiza'] ? (int)$get['franshiza'] : 0,
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
//        if(empty($propositions)) { 
//            return null; 
//        }
        
//        var_dump($tariff_options);
//        var_dump(count($propositions));
//        var_dump($propositions);
//        die('yeah');
        return $this->renderPartial('osago_propositions.twig', [
            'propositions' => $propositions
        ]);
    }

}