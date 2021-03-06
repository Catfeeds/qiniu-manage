<?php
namespace backend\controllers;

use common\services\UploadService;

/**
 * Upload controller
 */
class UploadController extends BaseController
{

    /**
     * 获取上传所需的Token
     */
    public function actionToken(){
        $qiniuConfig = \Yii::$app->params['qiniu'];
        $params = $this->get();
        $isImg = $params['isImg'];
        $bucket = $isImg ? $qiniuConfig['imgBucket'] : $qiniuConfig['downloadBucket'];
        $token = UploadService::getToken($bucket);
        die(json_encode(['uptoken'=>$token]));
    }
}
