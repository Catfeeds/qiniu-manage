<?php
namespace console\controllers;
use common\models\LoadScree;
use common\models\Video;

/**
 * Class TestController
 * @package console\controllers
 */
class TestController extends BaseController
{

    public function actionIndex(){
        $this->log('导入成功');
    }
}