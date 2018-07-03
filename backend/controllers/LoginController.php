<?php
namespace backend\controllers;

use backend\services\AuthService;
use Jormin\Geetest\Geetest;
use Yii;
use yii\helpers\Url;

/**
 * Class LoginController
 *
 * @package backend\controllers
 */
class LoginController extends BaseController
{

    /**
     * @var Geetest
     */
    public $geetest;

    public function beforeAction($action)
    {
        if(!parent::beforeAction($action)){
            return false;
        }
        if($this->admin){
            $this->redirect(Url::to(['index/index']))->send();
        }
        $config = Yii::$app->params['geetest'];
        $config['captchaUrl'] = Url::to(['login/captcha']);
        $this->geetest = new Geetest($config);
        return true;
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if(Yii::$app->request->isGet){
            $this->getView()->title = "后台管理系统·登录";
            $this->layout = 'login';
            return $this->render('index', ['geetest'=>$this->geetest]);
        }else{
            $params = Yii::$app->request->post();
            $response = AuthService::login($params['username'], $params['password'], $this->geetest, $params['geetest_challenge'], $params['geetest_validate'], $params['geetest_seccode']);
            $this->autoResult($response);
        }
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionCaptcha()
    {
        echo $this->geetest->captcha();
    }
}
