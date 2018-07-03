<?php

namespace common\services;

use League\Flysystem\Filesystem;
use Overtrue\Flysystem\Qiniu\Plugins\UploadToken;
use Overtrue\Flysystem\Qiniu\QiniuAdapter;

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
     * @param $domain
     * @return mixed
     */
    public static function getToken($bucket, $domain)
    {
        $qiniuConfig = \Yii::$app->params['qiniu'];
        $adapter = new QiniuAdapter($qiniuConfig['accessKey'], $qiniuConfig['secretKey'], $bucket, $domain);
        $flysystem = new Filesystem($adapter);
        $flysystem->addPlugin(new UploadToken());
        $token = $flysystem->getUploadToken(null, 3600);
        return $token;
    }
}
