<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'app/web/css/normalize.css',
        'app/web/css/selectric.css',
        'app/web/css/default.css',
        'app/web/css/default.date.css',
        'app/web/css/styles.css',
    ];
    public $js = [
        'app/web/js/jquery-3.2.0.min.js',
        'app/web/js/jquery.maskedinput.min.js',
        'app/web/js/jquery.selectric.min.js',
        'app/web/js/slick.min.js',
        'app/web/js/picker.js',
        'app/web/js/picker.date.js',
        'app/web/js/pickaday_rus.js',
        'app/web/js/app.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}