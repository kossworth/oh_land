<?php
namespace app\components;

use yii\web\UrlManager;
use app\models\Lang;

class LangUrlManager extends UrlManager
{
    public function createUrl($params)
    {
        if( isset($params['lang_id']) ){
            #if language selected try to find it into DB
            $lang = Lang::findOne($params['lang_id']);
            if( $lang === null ){
                $lang = Lang::getDefaultLang();
            }
            unset($params['lang_id']);
        } else {
            #default lang if not selected other
            $lang = Lang::getCurrent();
        }
        #url without prefix
        $url = parent::createUrl($params);

		if ($url[0] == '/') {
            $url = substr($url, 1);
        }
        if ($url == '/') {
            $url = '';
        }
        #add prefix
        if($lang->by_default)
        {
            return $url;
        } 
        else	
        {
            return ($url == '/') ? '/'.$lang->url . '/': '/'.$lang->url.$url;
        }
    }

}
