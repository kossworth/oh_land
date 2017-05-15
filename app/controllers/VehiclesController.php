<?php

namespace app\controllers;

use Yii;
use app\components\ewa;

class VehiclesController extends \app\components\BaseController
{
    
    public function behaviors() 
    {
        return [
            'verbs' => [
                'class'   => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'search-city'              => ['get'],
                    'ewa-city'                 => ['get'],
                ]
            ],
        ];
    }
    
    public function actionEwaBrand()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;
//        if(!$request->isAjax)
//        {
//            throw new BadRequestHttpException("Wrong request", 400);
//        }
//        $get = $request->get();

        if(isset($get['brand']))
        {
            $criteria =  strip_tags(trim($get['brand']));   
        }
        else
        {
            $criteria = '';
        }
        
        var_dump($brands); die();
        
        $result = [];
        foreach ($cities as $city)
        {
            $result['items'][] = ['name' => $city['name_full'], 'id' => $city['zone']];
        }
        return $result;
    }
    
}