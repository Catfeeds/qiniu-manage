<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class ErrorAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/error.css',
    ];
    public $js = [
        'vendor/geetest/jquery.min.js',
        'vendor/toastr/toastr.js',
        'js/fn.js',
    ];
    public $depends = [
    ];
}
