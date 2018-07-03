<?php
namespace backend\controllers;

/**
 * Index controller
 */
class IndexController extends AuthController
{

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->getView()->title = "后台管理系统";
        return $this->render('index');
    }
}
