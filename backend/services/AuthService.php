<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/6/17
 * Time: 10:41
 */

namespace backend\services;

use backend\models\Admin;
use backend\models\Menu;
use common\libs\CommonFunction;
use common\libs\UserMsg;
use Jormin\Geetest\Geetest;

class AuthService {

    /**
     * 管理员登录
     *
     * @param $username
     * @param $password
     * @param $geetest Geetest
     * @param $geetestChallenge
     * @param $geetestValidate
     * @param $geetestSeccode
     * @return array
     */
    public static function login($username, $password, $geetest, $geetestChallenge, $geetestValidate, $geetestSeccode){
        $return = ['status'=>0, 'msg'=>UserMsg::$timeOut];
        foreach (func_get_args() as $value){
            if(!$value){
                $return['msg'] = '参数错误';
                return $return;
            }
        }
        $geetestResult = $geetest->validate($geetestChallenge, $geetestValidate, $geetestSeccode);
        if(!$geetestResult){
            $return['msg'] = '验证码未通过';
            return $return;
        }
        $admin = Admin::findOne(['username'=>$username]);
        if(!$admin){
            $return['msg'] = '用户名错误';
            return $return;
        }
        if(!CommonFunction::validatePassword($password.$admin['encrypt'], $admin['password'])){
            $return['msg'] = '密码错误';
            return $return;
        }
        if($admin['status'] != 1){
            $return['msg'] = '该账号已被禁用';
            return $return;
        }
        \Yii::$app->session->set('admin', $admin->attributes);
        $return = ['status'=>1, 'msg'=>'登录成功'];
        return $return;
    }

    /**
     * 获取管理员权限列表
     *
     * @return array
     */
    public static function getAdminPermissions(){
        $admin = \Yii::$app->session->get('admin');
        $admin = Admin::findOne($admin['id']);
        $permissions = [];
        foreach ($admin->roles as $role){
            $rolePermissions = $role->permissions;
            $rolePermissions && $permissions = array_merge($permissions, $role->permissions);
        }
        $permissionIDs = [];
        foreach ($permissions as $permission){
            $permissionIDs[] = $permission['id'];
        }
        $permissionIDs = array_values(array_unique($permissionIDs));
        return $permissionIDs;
    }

}