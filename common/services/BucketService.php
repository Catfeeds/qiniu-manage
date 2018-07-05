<?php

namespace common\services;

use common\libs\UserMsg;
use common\models\AuthAccount;
use common\models\Bucket;
use common\models\Prefix;
use Jormin\Qiniu\Qiniu;

/**
 * Class BucketService
 * @package common\models
 */
class BucketService
{

    /**
     * 创建七牛空间
     *
     * @param $accountID
     * @param $bucket
     * @param string $region
     * @return array
     */
    public static function createBucket($accountID, $bucket, $region='z0')
    {
        $return = ['status'=>0, 'msg'=>UserMsg::$timeOut];
        if(!$bucket){
            $return['msg'] = '空间名称不能为空';
            return $return;
        }
        $authAccount = AuthAccount::get($accountID);
        if(!$authAccount){
            $return['msg'] = '授权账号不存在';
            return $return;
        }
        $transaction = \Yii::$app->db->beginTransaction();
        $qiniu = new Qiniu($authAccount['accessKey'], $authAccount['secretKey']);
        $response = $qiniu->createBucket($bucket, $region);
        if($response['error']){
            $transaction->rollBack();
            $return['msg'] = '创建七牛空间失败,原因：'.$response['message'];
            return $return;
        }
        $response = $qiniu->domains($bucket);
        if($response['error']){
            $transaction->rollBack();
            $return['msg'] = '读取七牛空间域名失败,原因：'.$response['message'];
            return $return;
        }
        $domains = $response['data'];
        $bucketModel = new Bucket();
        $bucketModel->accountID = $accountID;
        $bucketModel->bucket = $bucket;
        $bucketModel->domains = json_encode($domains);
        $bucketModel->defaultDomain = end($domains);
        if(!$bucketModel->save()){
            $transaction->rollBack();
            $return['msg'] = '创建七牛空间失败';
            return $return;
        }
        $transaction->commit();
        $return = ['status'=>1, 'msg'=>'创建成功', 'data'=>$bucketModel];
        return $return;
    }

    /**
     * 删除七牛空间
     *
     * @param $bucketID
     * @return array
     */
    public static function deleteBucket($bucketID)
    {
        $return = ['status'=>0, 'msg'=>UserMsg::$timeOut];
        $bucket = Bucket::findOne($bucketID);
        if(!$bucket){
            $return['msg'] = '七牛空间不存在';
            return $return;
        }
        $transaction = \Yii::$app->db->beginTransaction();
        if(!$bucket->delete()){
            $transaction->rollBack();
            $return['msg'] = '删除七牛空间失败';
            return $return;
        }
        $authAccount = AuthAccount::get($bucket['accountID']);
        $qiniu = new Qiniu($authAccount['accessKey'], $authAccount['secretKey']);
        $response = $qiniu->dropBucket($bucket['bucket']);
        if($response['error']){
            $transaction->rollBack();
            $return['msg'] = '删除七牛空间失败,原因：'.$response['message'];
            return $return;
        }
        Prefix::deleteAll(['bucketID'=>$bucketID]);
        $transaction->commit();
        $return = ['status'=>1, 'msg'=>'删除七牛空间成功', 'data'=>null];
        return $return;
    }

    /**
     * 同步七牛空间
     *
     * @param $accountID
     * @return array
     */
    public static function syncBuckets($accountID)
    {
        $return = ['status'=>0, 'msg'=>UserMsg::$timeOut];
        $authAccount = AuthAccount::get($accountID);
        if(!$authAccount){
            $return['msg'] = '授权账号不存在';
            return $return;
        }
        $transaction = \Yii::$app->db->beginTransaction();
        $qiniu = new Qiniu($authAccount['accessKey'], $authAccount['secretKey']);
        $response = $qiniu->buckets();
        if($response['error']){
            $transaction->rollBack();
            $return['msg'] = '读取七牛空间失败,原因：'.$response['message'];
            return $return;
        }
        $qiniuBuckets = $response['data'];
        $bucketNames = [];
        $existBucket = [];
        foreach ($qiniuBuckets as $qiniuBucket){
            $bucketNames = $qiniuBucket['name'];
            $bucket = Bucket::findOne(['accountID'=>$accountID, 'bucket'=>$qiniuBucket['name']]);
            if(!$bucket){
                $bucket = new Bucket();
                $bucket->accountID = $authAccount['id'];
            }
            $bucket->bucket = $qiniuBucket['name'];
            $bucket->domains = json_encode($qiniuBucket['domains']);
            if(!$bucket->defaultDomain || !in_array($bucket->defaultDomain, $qiniuBucket['domains'])){
                $bucket->defaultDomain = end($qiniuBucket['domains']);
            }
            if(!$bucket->save()){
                $transaction->rollBack();
                $return['msg'] = '更新七牛空间失败';
                return $return;
            }
            $existBucket[] = $bucket;
        }
        $deleteBuckets = Bucket::findAll(['and', ['accountID'=>$accountID], ['not in', 'bucket', $bucketNames]]);
        foreach ($deleteBuckets as $deleteBucket){
            Prefix::deleteAll(['bucketID'=>$deleteBucket['id']]);
            if(!$deleteBucket->delete()){
                $transaction->rollBack();
                $return['msg'] = '删除七牛空间失败';
                return $return;
            }
        }
        $transaction->commit();
        // 更新缓存
        foreach ($existBucket as $bucket){
            $bucket->resetCache();
        }
        foreach ($deleteBuckets as $bucket){
            $bucket->resetCache();
        }
        $return = ['status'=>1, 'msg'=>'删除七牛空间成功', 'data'=>null];
        return $return;
    }

    /**
     * 同步域名
     *
     * @param $bucketID
     * @return array
     */
    public static function syncDomains($bucketID)
    {
        $return = ['status'=>0, 'msg'=>UserMsg::$timeOut];
        $bucket = Bucket::findOne($bucketID);
        if(!$bucket){
            $return['msg'] = '空间不存在';
            return $return;
        }
        $authAccount = AuthAccount::get($bucket['accountID']);
        $qiniu = new Qiniu($authAccount['accessKey'], $authAccount['secretKey']);
        $response = $qiniu->domains($bucket['bucket']);
        if($response['error']){
            $return['msg'] = '读取七牛空间域名失败,原因：'.$response['message'];
            return $return;
        }
        $domains = $response['data'];
        $bucket->domains = json_encode($domains);
        if(!$bucket->defaultDomain || !in_array($bucket->defaultDomain, $domains)){
            $bucket->defaultDomain = end($domains);
        }
        if(!$bucket->save()){
            $return['msg'] = '更新七牛空间失败';
            return $return;
        }
        $return = ['status'=>1, 'msg'=>'同步域名成功', 'data'=>null];
        return $return;
    }
}
