<?php

namespace app\controllers;

use Yii;
use app\components\ewa;
use app\components\MailComponent;
use app\models\Faq;
use app\models\Company;

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
        $compamies = Company::find()->all();
        $faqs = Faq::find()->orderBy(Faq::tableName().'.sort DESC')->asArray()->all();
        return $this->render('index.twig', [
            'companies' => $compamies,
            'faqs' => $faqs,
        ]);
    }
    
}