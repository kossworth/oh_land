<?php

namespace app\controllers;

use Yii;
use yii\web\BadRequestHttpException;
use app\components\MailComponent;
use app\models\LandingFeedbacks;
use app\models\LandingCallbacks;


class FeedbacksController extends \app\components\BaseController
{
    
    public function behaviors() {
        return [
            'verbs' => [
                'class'   => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'create-feedback'          => ['post'],
                    'create-callback'          => ['post'],
                ]
            ],
        ];
    }
    
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false; 
        return parent::beforeAction($action);
    }
    
    public function actionCreateFeedback()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//        if(!Yii::$app->request->isAjax)
//        {
//            throw new BadRequestHttpException("Wrong request", 400);
//        }

        $post                   = Yii::$app->request->post();
        $feedback               = new LandingFeedbacks();
        $feedback->username     = isset($post['name']) ? strip_tags(trim($post['name'])) : '';
        $feedback->userphone    = isset($post['phone']) ? strip_tags(trim($post['phone'])) : '';
        $feedback->text         = isset($post['feedback']) ? strip_tags(trim($post['feedback'])) : '';
        $feedback->rating       = isset($post['rating']) ? strip_tags(trim($post['rating'])) : 5;
        $feedback->processed    = 0;
        $feedback->published    = 0;
        $feedback->crtdate      = date('U');
        
        if($feedback->save())
        {
            if(isset($this->partner) && is_object($this->partner))
            {               
                MailComponent::unisenderMailsend('feedback_landing', $this->partner->task_email, null, [
                    'name'  => $feedback->username,
                    'phone' => $feedback->userphone,
                    'text'  => $feedback->text,
                ]);    
            }
            
            return ['status' => true, 'msg' => true];
        }
        else
        {
            return ['status' => false, 'msg' => serialize($feedback->getErrors)];
        }
    }
    
    public function actionCreateCallback()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//        if(!Yii::$app->request->isAjax)
//        {
//            throw new BadRequestHttpException("Wrong request", 400);
//        }

        $post                   = Yii::$app->request->post();
        $callback               = new LandingCallbacks();
        $callback->phone        = isset($post['phone']) ? strip_tags(trim($post['phone'])) : '';
        $callback->processed    = 0;
        $callback->crtdate      = date('U');
        
        if($callback->save())
        {
            if(isset($this->partner) && is_object($this->partner))
            { 
                MailComponent::unisenderMailsend('callback_landing', $this->partner->task_email, 'Заказан обратный звонок по ОСАГО на лендинге', [
                    'phone' => $callback->phone,
                ]);
            }
            return ['status' => true, 'msg' => true];
        }
        else
        {
            return ['status' => false, 'msg' => serialize($callback->getErrors)];
        }
    }
}