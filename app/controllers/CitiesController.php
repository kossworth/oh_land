<?php

namespace app\controllers;

use Yii;
use yii\web\BadRequestHttpException;
use app\components\ewa;
use app\models\NpFilials;
use app\models\NpCities;

class CitiesController extends \app\components\BaseController
{
    
    public function behaviors() {
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
    
    public function actionEwaCity()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if(!$request->isAjax)
        {
            throw new BadRequestHttpException("Wrong request", 400);
        }
        $get = $request->get();
        
        if(isset($get['item']))
        {
            $criteria =  strip_tags(trim($get['item']));   
        }
        else
        {
            $criteria = '';
        }
        
        $cities = ewa\find::city($criteria);
        
        if(empty($cities) || is_null($cities))
        {
            return [];
        }
        
        $result = [];
        foreach ($cities as $city)
        {
            $result['items'][] = ['name' => $city['name_full'], 'id' => $city['zone']];
        }
        return $result;
    }
    
    public function actionNpRegion()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if(!$request->isAjax)
        {
            throw new BadRequestHttpException("Wrong request", 400);
        }
        $criteria = strip_tags(trim($request->get('item')));
        return NovaposhtaCities::getAutocompleteArray($criteria);
    }
    
    public function actionNpCity()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if(!$request->isAjax)
        {
            throw new BadRequestHttpException("Wrong request", 400);
        }
        $criteria = strip_tags(trim($request->get('item')));
        return NovaposhtaCities::getAutocompleteArray($criteria);
    }
}
