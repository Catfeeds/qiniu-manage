<?php

namespace common\services;

use common\models\Bucket;
use League\Flysystem\Filesystem;
use Overtrue\Flysystem\Qiniu\QiniuAdapter;

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
     * @param $prefix
     * @return mixed
     */
    public static function getFiles($bucketID, $prefix='')
    {
        $bucket = Bucket::get($bucketID);
        $account = $bucket['account'];
        $adapter = new QiniuAdapter($account['accessKey'], $account['secretKey'], $bucket['bucket'], $bucket['domain']);
        $flysystem = new Filesystem($adapter);
        $files = $flysystem->listContents($prefix);
        $data = [$files, $bucket];
        return $data;
    }
}
