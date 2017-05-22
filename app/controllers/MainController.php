<?php

namespace app\controllers;

use Yii;
//use app\components\ewa;
use app\components\MailComponent;
use app\models\Faq;
use app\models\Company;
use app\models\LandingFeedbacks;
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
        $compamies  = Company::find()->joinWith(['osago'], true)->all();
        $faqs       = Faq::find()->orderBy(Faq::tableName().'.sort DESC')->asArray()->all();
        $reasons    = LandingReasons::find()->where([LandingReasons::tableName().'.active' => 1])->orderBy('sort DESC')->asArray()->all();
        $feedbacks  = LandingFeedbacks::find()->published()->orderBy('crtdate DESC')->asArray()->all();
        return $this->render('index.twig', [
            'companies'     => $compamies,
            'faqs'          => $faqs,
            'reasons'       => $reasons,
            'feedbacks'     => $feedbacks,
        ]);
    }
}