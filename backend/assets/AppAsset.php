<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'layuiadmin/layui/css/layui.css',
        'layuiadmin/style/admin.css',
        'layuiadmin/style/template.css',
        'vendor/font-awesome/css/font-awesome.min.css',
        'css/layui.extend.css',
        'vendor/toastr/toastr.min.css',
    ];
    public $js = [
        'layuiadmin/layui/layui.js',
        'vendor/geetest/jquery.min.js',
        'vendor/toastr/toastr.js',
        'js/fn.js',
    ];
    public $depends = [
    ];
}
