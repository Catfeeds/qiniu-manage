<?php

namespace common\services;

use common\libs\UserMsg;
use common\models\AuthAccount;
use common\models\Bucket;
use common\models\Prefix;
use Jormin\Qiniu\Qiniu;

/**
 * Class PrefixService
 * @package common\models
 */
class PrefixService
{

    /**
     * 创建前缀
     *
     * @param $prefixs
     * @param $bucketID
     * @return array
     */
    public static function createPrefix($prefixs, $bucketID)
    {
        $return = ['status'=>0, 'msg'=>UserMsg::$timeOut];
        if(!$prefixs){
            $return['msg'] = '参数错误';
            return $return;
        }
        if($bucketID){
            $bucket = Bucket::get($bucketID);
            if(!$bucket){
                $return['msg'] = '空间不存在';
                return $return;
            }
            $accountID = $bucket['accountID'];
        }else{
            $bucketID = $accountID = 0;
        }
        $transaction = \Yii::$app->db->beginTransaction();
        $prefixs = explode("\r\n", trim($prefixs));
        foreach ($prefixs as $prefixStr){
            $prefix = new Prefix();
            $prefix->bucketID = $bucketID;
            $prefix->accountID = $accountID;
            $prefix->prefix = $prefixStr;
            if(!$prefix->save()){
                $transaction->rollBack();
                $return['msg'] = '创建前缀失败';
                return $return;
            }
        }
        $transaction->commit();
        $return = ['status'=>1, 'msg'=>'创建成功', 'data'=>$prefix];
        return $return;
    }
}
