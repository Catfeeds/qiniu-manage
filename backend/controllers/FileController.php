<?php

namespace backend\controllers;

use common\libs\CommonFunction;
use common\models\Bucket;
use common\services\QiniuService;
use yii\helpers\Html;

/**
 * FileController implements the CRUD actions for File model.
 */
class FileController extends ContentController
{

    /**
     * 文件列表.
     *
     * @return string
     */
    public function actionIndex()
    {
        if($this->request()->isAjax){
            $params = $this->get();
            $bucketID = $params['bucketID'];
            $prefix = $params['prefix'];
            list($files, $bucket) = QiniuService::getFiles($bucketID, $prefix);
            foreach ($files as $key => $file){
                $file['timestamp'] = CommonFunction::dateTime($file['timestamp']);
                $url = $bucket['domain'].'/'.$file['path'];
                $file['url'] = Html::a('[点击访问]', null, ['class'=>'cmd-btn', 'data-url'=>$url, 'lay-event'=>'view',  'data-full'=>"true", 'data-height'=>"800px", 'data-refresh'=>'false']);
                $file['size'] = CommonFunction::formatSize($file['size']);
                $files[$key] = $file;
            }
            $this->success($files, '读取数据成功', 1000);
        }else{
            return $this->render('index');
        }
    }
}
