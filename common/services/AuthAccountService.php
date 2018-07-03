<?php

namespace common\services;

use common\libs\UserMsg;
use common\models\AuthAccount;
use common\models\Bucket;
use Jormin\Qiniu\Qiniu;

/**
 * Class AuthAccountService
 * @package common\models
 */
class AuthAccountService
{

    /**
     * 创建授权账号
     *
     * @param $alias
     * @param $accessKey
     * @param $secretKey
     * @return array
     */
    public static function createAuthAccount($alias, $accessKey, $secretKey)
    {
        $return = ['status'=>0, 'msg'=>UserMsg::$timeOut];
        $transaction = \Yii::$app->db->beginTransaction();
        $authAccount = new AuthAccount();
        $authAccount->alias = $alias;
        $authAccount->accessKey = $accessKey;
        $authAccount->secretKey = $secretKey;
        if(!$authAccount->save()){
            $transaction->rollBack();
            $return['msg'] = '创建授权账号失败';
            return $return;
        }
        $qiniu = new Qiniu($accessKey, $secretKey);
        $response = $qiniu->buckets();
        if($response['error']){
            $transaction->rollBack();
            $return['msg'] = '创建授权账号失败,原因：'.$response['message'];
            return $return;
        }
        $qiniuBuckets = $response['data'];
        foreach ($qiniuBuckets as $qiniuBucket){
            $bucket = new Bucket();
            $bucket->accountID = $authAccount->id;
            $bucket->bucket = $qiniuBucket['name'];
            $bucket->domains = json_encode($qiniuBucket['domains']);
            $bucket->defaultDomain = end($qiniuBucket['domains']);
            if(!$bucket->save()){
                $transaction->rollBack();
                $return['msg'] = '创建七牛空间失败';
                return $return;
            }
        }
        $transaction->commit();
        $return = ['status'=>1, 'msg'=>'创建成功', 'data'=>$authAccount];
        return $return;
    }
}
