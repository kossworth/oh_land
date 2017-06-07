<?php

namespace app\components;

use Yii;
use app\models\LandingFeedbacks;
use app\models\Partners;

class BaseController extends \yii\web\Controller
{
    public $default_content;
    protected $partner = null;
    
    public function init()
    {
        parent::init();

        $this->layout = 'main.twig';

        $session = Yii::$app->session;
        if(!$session->isActive) 
        {
            $session->open();
        }

        $this->view->params['feedbacks'] = LandingFeedbacks::find()->published()->orderBy('crtdate DESC')->asArray()->all();
        
        $host = Yii::$app->request->serverName.Yii::$app->request->baseUrl;
        $partner = Partners::find()->where([Partners::tableName().'.host' => $host])->limit(1)->one();
        if(!is_null($partner))
        {
            $this->partner = $partner;
            $this->view->params['partner_phone'] = $partner->phone;
            $this->view->params['partner_email'] = $partner->admin_email;
        }
        
        
//        Yii::$app->view->registerMetaTag([
//            'name'    => 'robots',
//            'content' => 'NOINDEX, NOFOLLOW'
//        ]);
 
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                'view' => '@app/views/main/404.twig',
            ],
        ];
    }
} 