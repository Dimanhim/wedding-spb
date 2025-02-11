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
        'fancybox/source/jquery.fancybox.css',
        'css/jquery.datetimepicker.min.css',
        'css/site.css',
    ];
    public $js = [
        'fancybox/source/jquery.fancybox.pack.js',
        'js/jquery.json.min.js',
        'js/mask.js',
        'js/jquery.datetimepicker.full.min.js',
        'js/script.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
