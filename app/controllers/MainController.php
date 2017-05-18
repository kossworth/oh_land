<?php

namespace app\controllers;

use Yii;
use app\components\ewa;
use app\components\MailComponent;
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
        return $this->render('index.twig', [
            
        ]);
    }
    
}