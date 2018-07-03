<?php

namespace backend\services;

use common\libs\CommonFunction;
use common\libs\UserMsg;
use backend\models\Admin;
use backend\models\AdminRole;

/**
 * Class AdminService
 * @package common\models
 */
class AdminService
{

    /**
     * 保存管理员
     *
     * @param $adminID
     * @param $data
     * @return array
     */
    public static function saveAdmin($adminID, $data)
    {
        $return = ['status'=>0, 'msg'=>UserMsg::$timeOut];
        $transaction = \Yii::$app->db->beginTransaction();
        $password = $data['password'];
        unset($data['password']);
        if($adminID){
            $admin = Admin::findOne($adminID);
            if(!$admin){
                $return['msg'] = '管理员不存在';
                return $return;
            }
            AdminRole::deleteAll(['adminID'=>$adminID]);
        }else{
            $admin = new Admin();
            $admin->encrypt = CommonFunction::getRandChar(6);
            if(!$password){
                $return['msg'] = '请设置管理员密码';
                return $return;
            }
        }
        if($password){
            $data['password'] = CommonFunction::encryptPassword($password.$admin->encrypt);
        }
        if(!$admin->load($data, '') || !$admin->save()){
            $return['msg'] = '保存管理员出错,出错原因:'.current($admin->getFirstErrors());
            $transaction->rollBack();
            return $return;
        }
        if(isset($data['roleIDs'])){
            $roleIDs = $data['roleIDs'];
            foreach ($roleIDs as $roleID){
                $adminRole = new AdminRole();
                $adminRole->adminID = $admin->id;
                $adminRole->roleID = $roleID;
                if(!$adminRole->save()){
                    $return['msg'] = '保存管理员权限出错,出错原因:'.current($adminRole->getFirstErrors());
                    $transaction->rollBack();
                    return $return;
                }
            }
        }
        $transaction->commit();
        $return = ['status'=>1, 'msg'=>'保存管理员成功', 'data'=>$admin];
        return $return;
    }
}
