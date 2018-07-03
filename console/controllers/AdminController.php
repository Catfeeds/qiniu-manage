<?php
namespace console\controllers;
use backend\models\Admin;
use common\libs\CommonFunction;
use common\models\LoadScree;
use common\models\Video;

/**
 * Class AdminController
 * @package console\controllers
 */
class AdminController extends BaseController
{

    public function actionIndex(){
        $encrypt = CommonFunction::getRandChar(6);
        $password = '111111';
        $hash = CommonFunction::encryptPassword($password.$encrypt);
        $this->log('盐值：'.$encrypt);
        $this->log('明文密码：'.$password);
        $this->log('hash密码：'.$hash);
        $admin = new Admin();
        $admin->username = 'admin';
        $admin->password = $hash;
        $admin->encrypt = $encrypt;
        $admin->save();
    }
}