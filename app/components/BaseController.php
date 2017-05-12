<?php

namespace app\components;

use app\models\Orders;
//use app\models\User\User;
use Yii;

use app\models\Pages;
use app\models\Lang;
use app\models\Slovar;
use app\models\Redirect;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class BaseController extends \yii\web\Controller
{
    public $default_content;

    protected function url_origin( $use_forwarded_host = false )
    {
        $s        = $_SERVER;
        $ssl      = ( ! empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on' );
        $sp       = strtolower( $s['SERVER_PROTOCOL'] );
        $protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
        $port     = $s['SERVER_PORT'];
        $port     = ( ( ! $ssl && $port=='80' ) || ( $ssl && $port=='443' ) ) ? '' : ':'.$port;
        $host     = ( $use_forwarded_host && isset( $s['HTTP_X_FORWARDED_HOST'] ) ) ? $s['HTTP_X_FORWARDED_HOST'] : ( isset( $s['HTTP_HOST'] ) ? $s['HTTP_HOST'] : null );
        $host     = isset( $host ) ? $host : $s['SERVER_NAME'] . $port;
        return $protocol . '://' . $host;
    }

    protected function full_url( $use_forwarded_host = false )
    {
        return $this->url_origin( $use_forwarded_host ) . $_SERVER['REQUEST_URI'];
    }

    public function init()
    {
        parent::init();

        $this->layout = 'main.twig';

        $session = Yii::$app->session;
        if(!$session->isActive) 
        {
            $session->open();
        }

//        $lang = Lang::getCurrent();
//        $this->view->params['lang'] = $lang;
//        $this->view->params['lang_sh'] = mb_substr(($lang->name),0,3, 'utf-8');
//        $langs = Lang::find()->all();
//        $this->view->params['langs'] = $langs;
//        $current_url=Yii::$app->request->pathinfo;
//        // request url
//        $this->view->params['current_url']=$current_url;
//
//
//        $slovar = Slovar::getDb()->cache(function ($db){
//            return Slovar::find()
//                ->leftJoin('`slovar_info`', '`slovar_info`.`record_id` = `slovar`.`id`')
//                ->select(['`slovar`.`alias`', '`slovar_info`.`value`'])
//                ->where(['`slovar_info`.`lang`' => Lang::getCurrentId()])
//                ->asArray()
//                ->all();
//        });
//        $slovar = ArrayHelper::map($slovar, 'alias', 'value');
//
//        $this->view->params = array_merge($this->view->params, $slovar);
//
//        if($lang->by_default)
//        {
//            $this->view->params['lang_url'] = '';
//            Yii::$app->homeUrl = $this->view->params['home_url']='/';
//            $this->view->params['current_url'] = $current_url ? "/{$current_url}": '/';
//        }
//        else
//        {
//            $this->view->params['lang_url']="/{$lang->url}";
//            Yii::$app->homeUrl = $this->view->params['home_url']="/{$lang->url}/";
//            $this->view->params['current_url']="/{$lang->url}/{$current_url}";
//        }

        Yii::$app->view->registerMetaTag([
            'name'    => 'robots',
            'content' => 'NOINDEX, NOFOLLOW'
        ]);
 
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