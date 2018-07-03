<?php
namespace backend\controllers;

use backend\models\Admin;
use common\libs\CommonFunction;
use common\libs\Session;

/**
 * Account controller
 */
class AccountController extends ContentController
{

    /**
     * 个人资料
     *
     * @return string
     */
    public function actionProfile()
    {
        $model = Admin::findOne($this->admin['id']);
        $post = $this->post();
        if ($model->load($post, '') && $model->save()) {
            Session::success('编辑个人资料成功');
            \Yii::$app->session->set('admin', $model->attributes);
            return $this->redirect(['account/profile']);
        } else {
            return $this->baseForm($model, 'account/profile');
        }
    }

    /**
     * 修改密码
     *
     * @return string
     */
    public function actionPassword()
    {
        $model = Admin::findOne($this->admin['id']);
        if($this->request()->isPost){
            $post = $this->post();
            $oldPassword = $post['oldPassword'];
            $password = $post['password'];
            $repassword = $post['repassword'];
            if($password != $repassword){
                return $this->baseForm($model, 'account/password', ';两次输入的密码不一致');
            }
            if(!CommonFunction::validatePassword($oldPassword.$model['encrypt'], $model['password'])){
                return $this->baseForm($model, 'account/password', '当前密码错误');
            }
            $model->password = CommonFunction::encryptPassword($password.$model->encrypt);
            if(!$model->save()){
                return $this->baseForm($model, 'account/password');
            }
            Session::success('修改密码成功');
            \Yii::$app->session->set('admin', $model->attributes);
            return $this->redirect(['account/password']);
        }else{
            return $this->baseForm($model, 'account/password');
        }
    }
}
