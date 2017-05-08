<?php

namespace app\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "lang".
 */
class Lang extends \yii\db\ActiveRecord
{
    static $current = null;
    static $default = null;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url', 'local', 'name', 'created_at', 'updated_at'], 'required'],
            [['by_default', 'created_at', 'updated_at'], 'integer'],
            [['url', 'local'], 'string', 'max' => 10],
            [['name'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'url' => Yii::t('app', 'Url'),
            'local' => Yii::t('app', 'Local'),
            'name' => Yii::t('app', 'Name'),
            'by_default' => Yii::t('app', 'Default'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @inheritdoc
     * @return \app\models\Queries\Lang the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\Queries\Lang(get_called_class());
    }
    
    public function getLangUrl()
    {
        $url = '';
        if ($this->by_default) 
        {
            $url = Yii::$app->getRequest()->getLangUrl();
            if ($url == '')
            {
                $url = '/';
            } // баг был. на главной странице язык не менялся на дефолтный
        }
        else
        {
            $url = '/'.$this->url.Yii::$app->getRequest()->getLangUrl();
        }
        return $url;
    }
    
    public static function getCurrent()
    {
        if( self::$current === null ){
            self::$current = self::getDefaultLang();
        }
        return self::$current;
    }

    public static function getCurrentUrl()
    {
        if(self::getDefaultLang()->url == self::getCurrent()->url)
        {
            return '/';
        }
        return '/'.self::getCurrent()->url .'/';
    }

    public static function getCurrentId()
    {
        return self::getCurrent()->id; // for multiLang
    }

    public static function setCurrent($url = null)
    {
        $language = self::getLangByUrl($url);
        self::$current = ($language === null) ? self::getDefaultLang() : $language;

        Yii::$app->language = self::$current->local;
    }

    public static function getDefaultLang()
    {
        if (self::$default === null)
        {
            self::$default = self::find()->getDefault();
        }

        return self::$default;
    }

    public static function getLangByUrl($url = null)
    {
        if ($url === null) {
            return null;
        } else {
            $language = self::find()->byUrl($url);
            if ( $language === null ) {
                return null;
            }else{
                return $language;
            }
        }
    }
}

