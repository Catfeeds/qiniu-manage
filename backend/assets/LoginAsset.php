<?php

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Login Page asset bundle.
 */
class LoginAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'layuiadmin/layui/css/layui.css',
        'layuiadmin/style/admin.css',
        'layuiadmin/style/login.css',
    ];
    public $js = [
        'layuiadmin/layui/layui.js',
        'vendor/geetest/jquery.min.js',
        'vendor/geetest/gt.js',
        'js/fn.js',
    ];
    public $jsOptions = [
        'position' => View::POS_HEAD
    ];
    public $depends = [
    ];
}
