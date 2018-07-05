<?php
namespace backend\controllers;

/**
 * ErrorController
 */
class ErrorController extends ContentController
{

    public static $errors = [
        '404' => '404 页面未找到!',
        '403' => '403 权限不足!',
        '500' => '500 服务器内部错误!',
    ];

    /**
     * 错误页面.
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->layout = 'error';
        $params = $this->get();
        $code = isset($params['code']) ? $params['code'] : '404';
        return $this->render('index', ['code'=>$code, 'msg'=>self::$errors[$code]]);
    }
}
