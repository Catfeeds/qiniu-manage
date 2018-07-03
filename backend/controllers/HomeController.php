<?php
namespace backend\controllers;

/**
 * Home controller
 */
class HomeController extends ContentController
{

    /**
     * 控制台
     *
     * @return string
     */
    public function actionDashboard()
    {
        return $this->render('dashboard');
    }
}
