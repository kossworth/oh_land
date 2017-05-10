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

//        $content = file_get_contents('301.csv');
//        $content = explode('http', $content);
//        array_shift($content);
//        $count = count($content);
//        $string = '';
//        for($i = 0; $i < $count; $i++)
//        {
//            if($i%2 == 0)
//            {
//                $string .= trim('http'.$content[$i]);
//            }
//            else
//            {
//                $string .= trim('http'.$content[$i]).';';
//            }
//        }
//
//        $transaction = \Yii::$app->getDb()->beginTransaction();
//        $string = explode(';', $string);
//        array_pop($string);
//        $status = [];
//        foreach ($string as $con)
//        {
//            $row = explode(',', $con);
//            if(!isset($row[1]))
//            {
//                continue;
//            }
//            $redirect = new Redirect();
//            $redirect->redirect_from = trim($row[0]);
//            $redirect->redirect_to = trim($row[1]);
//            $redirect->comment = '';
//            $redirect->creation_time = date('U');
//            $status[] = $redirect->save();
//        }
//        if(in_array(false, $status))
//        {
//            $transaction->rollBack();
//            var_dump($status); die();
//        }
//        else
//        {
//            $transaction->commit();
//        }
        
        
        // редирект из таблицы редиректов. если находим в базе совпадение - редиректим
//        $absolute_url = $this->full_url();
//        $need_redirect = Redirect::find()->where(['redirect.redirect_from' => $absolute_url])->limit(1)->one();
//
//        if(!is_null($need_redirect)) {
//            return $this->redirect($need_redirect->redirect_to, 301)->send();
//        }

        $this->layout = 'main.twig';

        Yii::$app->session->open();
        $session = Yii::$app->session;

//        $cart_count = 0;
//        if($session->isActive && $session->has('cart') && !empty($session['cart']))
//        {
//            $cart_count = count($session['cart']);
//        }
//
//        $this->view->params['cart_count'] = $cart_count;
//
//        // Обработка корзины
//        $this->view->params['cart'] = Orders::getCartInfo();
//
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
//
//        $main_menu_pages = [];
//        $top_menu_pages = [];
//
//        $menu_pages = Pages::getDb()->cache(function ($db){
//            return Pages::find()->inMenu()->joinWith(['info'], true)->orderBy('sort')->all();
//        });
//
//        foreach($menu_pages as $menu_page){
//            if($menu_page->parent_id == 3){
//                $top_menu_pages[] = $menu_page;
//            }elseif ($menu_page->parent_id == 4){
//                $main_menu_pages[] = $menu_page;
//            }
//        }
//
//        $this->view->params['main_menu_pages'] = $main_menu_pages;
//        $this->view->params['top_menu_pages'] = $top_menu_pages;
//        $this->view->params['menu_pages'] = $menu_pages;
//        $this->view->params['menu_pages_count'] = count($menu_pages);

        Yii::$app->view->registerMetaTag([
            'name'    => 'robots',
            'content' => 'NOINDEX, NOFOLLOW'
        ]);

//        if(isset($_GET['page']) && !empty($_GET['page']) && (int)$_GET['page'] > 1)
//        {
//            Yii::$app->view->registerMetaTag([
//                'name'    => 'robots',
//                'content' => 'NOINDEX, FOLLOW'
//            ]);
//        }
 
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