<?php
namespace backend\controllers;

/**
 * ErrorController
 */
class ErrorController extends ContentController
{

    public static $errors = [
        '404' => '您似乎来到了荒原~',
        '403' => '您的权限似乎不够~',
        '500' => '工程师正在抢修服务器~',
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
