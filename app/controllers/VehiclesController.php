<?php

namespace app\controllers;

use Yii;
use app\models\AutoMakers;
use app\models\AutoModels;

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
        $criteria = strip_tags(trim($request->get('item')));
        return AutoMakers::getAutocompleteMakersArray($criteria);
    }
    
    public function actionEwaModel()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;
//        if(!$request->isAjax)
//        {
//            throw new BadRequestHttpException("Wrong request", 400);
//        }
        $criteria = strip_tags(trim($request->get('item')));
        $brand_id = (int)$request->get('criteria');
        return AutoModels::getAutocompleteModelsArray($criteria, $brand_id);
    }
    
}