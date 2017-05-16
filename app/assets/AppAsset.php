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
        'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
        'app/web/css/selectric.css',
        'app/web/css/jquery.auto-complete.css',
        'app/web/css/default.css',
        'app/web/css/default.date.css',
        'app/web/css/styles.css',
    ];
    public $js = [
        'app/web/js/jquery-3.2.0.min.js',
        'app/web/js/jquery.maskedinput.min.js',
        'app/web/js/jquery.selectric.min.js',
        'app/web/js/jquery.auto-complete.min.js',
        'app/web/js/slick.min.js',
        'app/web/js/jQvalidateCore.js',
        'app/web/js/jQvalidatePattern.js',
        'app/web/js/jQvalidateMessages_ru.js',
        'app/web/js/picker.js',
        'app/web/js/picker.date.js',
        'app/web/js/pickaday_rus.js',
        'app/web/js/app.js',
        'app/web/js/custom.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}