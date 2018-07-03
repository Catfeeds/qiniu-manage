<?php

namespace backend\services;

use common\libs\UserMsg;
use backend\models\Role;
use backend\models\RolePermission;

/**
 * Class RoleService
 * @package common\models
 */
class RoleService
{

    /**
     * 保存角色
     *
     * @param $roleID
     * @param $data
     * @return array
     */
    public static function saveRole($roleID, $data)
    {
        $return = ['status'=>0, 'msg'=>UserMsg::$timeOut];
        $transaction = \Yii::$app->db->beginTransaction();
        if($roleID){
            $role = Role::findOne($roleID);
            if(!$role){
                $return['msg'] = '角色不存在';
                return $return;
            }
            RolePermission::deleteAll(['roleID'=>$roleID]);
        }else{
            $role = new Role();
        }
        if(!$role->load($data, '') || !$role->save()){
            $return['msg'] = '保存角色出错,出错原因:'.current($role->getFirstErrors());
            $transaction->rollBack();
            return $return;
        }
        if(isset($data['permissionIDs'])){
            $permissionIDs = $data['permissionIDs'];
            foreach ($permissionIDs as $permissionID){
                $rolePermission = new RolePermission();
                $rolePermission->roleID = $role->id;
                $rolePermission->permissionID = $permissionID;
                if(!$rolePermission->save()){
                    $return['msg'] = '保存角色权限出错,出错原因:'.current($rolePermission->getFirstErrors());
                    $transaction->rollBack();
                    return $return;
                }
            }
        }
        $transaction->commit();
        $return = ['status'=>1, 'msg'=>'保存角色成功', 'data'=>$role];
        return $return;
    }
}
