<?php

namespace common\services;

use common\libs\CommonFunction;
use common\libs\UserMsg;
use common\models\AuthAccount;
use common\models\Bucket;
use common\models\Prefix;
use Jormin\Qiniu\Qiniu;
use yii\helpers\Html;

/**
 * Class QiniuService
 * @package common\models
 */
class QiniuService
{

    /**
     * 获取文件列表
     *
     * @param $bucketID
     * @param $limit
     * @param integer $prefixID
     * @param $marker
     * @return array
     */
    public static function getFiles($bucketID, $limit, $prefixID,$marker)
    {
        $return = ['status'=>0, 'msg'=>UserMsg::$timeOut];
        $bucket = Bucket::get($bucketID);
        if($prefixID){
            $prefix = Prefix::findOne($prefixID);
            $prefixStr = $prefix ? $prefix['prefix'] : '';
        }else{
            $prefixStr = '';
        }
        $account = $bucket['account'];
        $qiniu = new Qiniu($account['accessKey'], $account['secretKey']);
        $response = $qiniu->listFiles($bucket['bucket'], $limit, $prefixStr, $marker);
        if($response['error']){
            $return['msg'] = $response['message'];
            return $return;
        }
        $responseData = $response['data'];
        $files = $responseData['items'];
        $marker = isset($responseData['marker']) ? $responseData['marker'] : '';
        $types = ['标准存储', '低频存储'];
        foreach ($files as $key => $file){
            $file['putTime'] = CommonFunction::dateTime(intval($file['putTime']/10000000));
            $file['url'] = 'http://'.$bucket['defaultDomain'].'/'.$file['key'];
            if(CommonFunction::isImage($file['mimeType'])){
                $file['btn'] = CommonFunction::createLink('[预览]', $file['url'], 'cmd-btn', 'view', "true", '880px', "false");
            }else{
                $file['btn'] = CommonFunction::createLink('[下载]', $file['url'], 'cmd-btn', 'download');
            }
            $file['fsize'] = CommonFunction::formatSize($file['fsize']);
            $file['typeLabel'] = $types[$file['type']];
            $file['id'] = base64_encode(json_encode([$bucketID, $file['key']]));
            $files[$key] = $file;
        }
        $response = $qiniu->count($bucket['bucket']);
        if($response['error']){
            $return['msg'] = $response['message'];
            return $return;
        }
        $count = $response['data']['amount'];
        $return = ['status'=>1, 'msg'=>'获取数据成功', 'data'=>['files'=>$files, 'count'=>$count, 'marker'=>$marker]];
        return $return;
    }

    /**
     * 刷新
     *
     * @param $accountID
     * @param $urls
     * @param $dirs
     * @return array
     */
    public static function refresh($accountID, $urls, $dirs)
    {
        $return = ['status'=>0, 'msg'=>UserMsg::$timeOut];
        $urls && $urls = explode("\r\n", trim($urls));
        $dirs && $dirs = explode("\r\n", trim($dirs));
        $account = AuthAccount::get($accountID);
        $qiniu = new Qiniu($account['accessKey'], $account['secretKey']);
        $response = $qiniu->refresh($urls, $dirs);
        if($response['error']){
            $return['msg'] = $response['message'];
            return $return;
        }
        $return = ['status'=>1, 'msg'=>'刷新成功', 'data'=>$response['data']];
        return $return;
    }

    /**
     * 文件预取
     *
     * @param $accountID
     * @param $urls
     * @return array
     */
    public static function prefetchUrls($accountID, $urls)
    {
        $return = ['status'=>0, 'msg'=>UserMsg::$timeOut];
        if(!$urls){
            $return['msg'] = 'Url不能为空';
            return $return;
        }
        $urls && $urls = explode("\r\n", trim($urls));
        $account = AuthAccount::get($accountID);
        $qiniu = new Qiniu($account['accessKey'], $account['secretKey']);
        $response = $qiniu->prefetchUrls($urls);
        if($response['error']){
            $return['msg'] = $response['message'];
            return $return;
        }
        $return = ['status'=>1, 'msg'=>'文件预取成功', 'data'=>$response['data']];
        return $return;
    }

    /**
     * 删除文件
     *
     * @param $bucketID
     * @param $key
     * @return array
     */
    public static function deleteFile($bucketID, $key)
    {
        $return = ['status'=>0, 'msg'=>UserMsg::$timeOut];
        if(!$bucketID || !$key){
            $return['msg'] = '参数错误';
            return $return;
        }
        $bucket = Bucket::get($bucketID);
        if(!$bucket){
            $return['msg'] = '空间不存在';
            return $return;
        }
        $account = AuthAccount::get($bucket['accountID']);
        $qiniu = new Qiniu($account['accessKey'], $account['secretKey']);
        $response = $qiniu->delete($bucket['bucket'], $key);
        if($response['error']){
            $return['msg'] = $response['message'];
            return $return;
        }
        $return = ['status'=>1, 'msg'=>'删除文件成功', 'data'=>$response['data']];
        return $return;
    }

    /**
     * 获取文件信息
     *
     * @param $bucketID
     * @param $key
     * @return array
     */
    public static function fileInfo($bucketID, $key)
    {
        $return = ['status'=>0, 'msg'=>UserMsg::$timeOut];
        if(!$bucketID || !$key){
            $return['msg'] = '参数错误';
            return $return;
        }
        $bucket = Bucket::get($bucketID);
        if(!$bucket){
            $return['msg'] = '空间不存在';
            return $return;
        }
        $account = AuthAccount::get($bucket['accountID']);
        $qiniu = new Qiniu($account['accessKey'], $account['secretKey']);
        $response = $qiniu->stat($bucket['bucket'], $key);
        if($response['error']){
            $return['msg'] = $response['message'];
            return $return;
        }
        $return = ['status'=>1, 'msg'=>'获取文件信息成功', 'data'=>$response['data']];
        return $return;
    }

    /**
     * 编辑文件
     *
     * @param $bucketID
     * @param $key
     * @param $newKey
     * @param $mime
     * @param $type
     * @param $force
     * @return array
     */
    public static function updateFile($bucketID, $key, $newKey, $mime, $type, $force)
    {
        $return = ['status'=>0, 'msg'=>UserMsg::$timeOut];
        $force == (strtolower($force) === 'on') ? true : false;
        if(!$bucketID || !$key){
            $return['msg'] = '参数错误';
            return $return;
        }
        $bucket = Bucket::get($bucketID);
        if(!$bucket){
            $return['msg'] = '空间不存在';
            return $return;
        }
        $account = AuthAccount::get($bucket['accountID']);
        $qiniu = new Qiniu($account['accessKey'], $account['secretKey']);
        $response = $qiniu->stat($bucket['bucket'], $key);
        if($response['error']){
            $return['msg'] = $response['message'];
            return $return;
        }
        $file = $response['data'];
        if($mime != $file['mimeType']){
            $response = $qiniu->changeMime($bucket['bucket'], $key, $mime);
            if($response['error']){
                $return['msg'] = $response['message'];
                return $return;
            }
        }
        if($type != $file['type']){
            $response = $qiniu->changeType($bucket['bucket'], $key, $type);
            if($response['error']){
                $return['msg'] = $response['message'];
                return $return;
            }
        }
        if($newKey != $key){
            $response = $qiniu->move($bucket['bucket'], $key, $bucket['bucket'],$newKey, $force);
            if($response['error']){
                $return['msg'] = $response['message'];
                return $return;
            }
        }
        $return = ['status'=>1, 'msg'=>'编辑文件成功', 'data'=>null];
        return $return;
    }
}
