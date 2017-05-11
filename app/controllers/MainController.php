<?php

namespace app\controllers;

use Yii;
use app\components\ewa;
/**
 * Description of MainController
 *
 * @author kossworth
 */
class MainController extends \app\components\BaseController 
{

    public function behaviors() {
        return [
            'verbs' => [
                'class'   => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'index'             => ['get'],
                ]
            ],
        ];
    }
    
    public function actionIndex()
    {        
        $tariff_options = [
            'autoCategory' => 'A1',
            'bonusMalus' => 0.8,
            'customerCategory' => 'NATURAL',
            'dateFrom' => date('Y-m-d', strtotime('+1 day')),
            'dateTo' => date('Y-m-d', strtotime('+1 year')),
            'zone' => 1,
            'taxi' => false,
            'usageMonths' => 0,
            'driveExp' => false
        ];
        
//        $test = ewa\find::osago($tariff_options);
//        var_dump($test); die();
        return $this->render('index.twig', [
            'test' => 'MY TEST'
        ]);
    }
    
}