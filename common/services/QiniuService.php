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
            $prefix = Prefix::get($prefixID);
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
}
