<?php

namespace app\controllers;

use app\models\Faq;
use app\models\Company;
use app\models\LandingReasons;

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
        $compamies  = Company::find()->joinWith(['osago'], true)->where([Company::tableName().'.active_osago' => 1])->all();
        $faqs       = Faq::find()->andWhere([Faq::tableName().'.to_landing' => 1])->orderBy(Faq::tableName().'.sort DESC')->asArray()->all();
        $reasons    = LandingReasons::find()->where([LandingReasons::tableName().'.active' => 1])->orderBy('sort DESC')->asArray()->all();

        return $this->render('index.twig', [
            'companies'     => $compamies,
            'faqs'          => $faqs,
            'reasons'       => $reasons,
        ]);
    }
}