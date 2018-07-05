<?php
namespace backend\controllers;

use backend\models\Permission;
use backend\services\AuthService;
use Yii;
use yii\helpers\Url;
/**
 * Auth controller
 */
class AuthController extends BaseController
{

    /**
     * 鉴权判定
     *
     * @param \yii\base\Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        if(!parent::beforeAction($action)){
            return false;
        }
        if(!$this->admin){
            if($this->request()->isAjax){
                $this->authFail();
            }else{
                $this->redirect(Url::to(['login/index']))->send();
            }
            return false;
        }
        $currentController = Yii::$app->controller;
        $currentPermission = Permission::findOne(['controller'=>$currentController->id, 'action'=>$currentController->action->id]);
        if($currentPermission){
            $adminPermissions = AuthService::getAdminPermissions();
            if(!in_array($currentPermission['id'], $adminPermissions)){
                if($this->request()->isAjax){
                    $this->fail('权限不足');
                }else{
                    $this->redirect(Url::to(['error/index', 'code'=>403]))->send();
                }
                return false;
            }
        }
        return true;
    }

    /**
     * 退出
     */
    public function actionLogout(){
        session_destroy();
        $this->success(null, '退出成功');
    }
}
