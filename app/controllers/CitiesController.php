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
                    'np-city'                  => ['get'],
                    'np-filial'                => ['get'],
                ]
            ],
        ];
    }
    
    public function actionEwaCity()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;
//        if(!$request->isAjax)
//        {
//            throw new BadRequestHttpException("Wrong request", 400);
//        }
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
            return ['items' => []];
        }
        
        $result = [];
        foreach ($cities as $city)
        {
            $result['items'][] = ['name' => $city['name_full'], 'id' => $city['id'], 'zone_id' => $city['zone']];
        }
        return $result;
    }
      
    public function actionNpCity()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;
//        if(!$request->isAjax)
//        {
//            throw new BadRequestHttpException("Wrong request", 400);
//        }
        $get = $request->get();
        $search = strip_tags(trim($get['item']));
        $region_id = isset($get['criteria']) ? strip_tags(trim($get['criteria'])) : null;
        return NpCities::getAutocompleteCitiesArray($search, $region_id);
    }
    
    public function actionNpFilial()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;
//        if(!$request->isAjax)
//        {
//            throw new BadRequestHttpException("Wrong request", 400);
//        }
//        $search = strip_tags(trim($request->get('item')));
        $city_id = strip_tags(trim($request->get('cityId')));
        $divisions = NpFilials::getAutocompleteFilialsArray($city_id);

        return $this->renderAjax('divisions.twig', [
            'divisions' => $divisions
        ]);
    }
}