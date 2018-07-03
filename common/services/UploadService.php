<?php

namespace common\services;
use Jormin\Qiniu\Qiniu;


/**
 * Class UploadService
 * @package common\models
 */
class UploadService
{

    /**
     * 获取上传Token
     *
     * @param $bucket
     * @return null
     * @throws \Exception
     */
    public static function getToken($bucket)
    {
        $qiniuConfig = \Yii::$app->params['qiniu'];
        $qiniu = new Qiniu($qiniuConfig['accessKey'], $qiniuConfig['secretKey']);
        $response = $qiniu->uploadToken($bucket);
        if($response['error']){
            return null;
        }
        return $response['data'];
    }
}
