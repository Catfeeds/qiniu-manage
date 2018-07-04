<?php

namespace backend\controllers;

use common\libs\CommonFunction;
use common\libs\Session;
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
            $limit = $params['limit'];
            $marker = isset($params['marker']) ? $params['marker'] : '';
            $prefixID = isset($params['prefixID']) ? $params['prefixID'] : '';
            $response = QiniuService::getFiles($bucketID, $limit, $prefixID, $marker);
            if($response['status'] == 0){
                $this->fail('读取文件失败,原因:'.$response['msg']);
            }
            $this->success($response['data']['files'], '读取数据成功', $response['data']['count'], ['marker'=>$response['data']['marker']]);
        }else{
            return $this->render('index');
        }
    }

    /**
     * 刷新缓存
     *
     * @return string
     */
    public function actionRefresh()
    {
        $params = $this->post();
        if($this->request()->isPost){
            $accountID = $params['accountID'];
            $urls = $params['urls'];
            $dirs = $params['dirs'];
            $response = QiniuService::refresh($accountID, $urls, $dirs);
            if($response['status'] == 0){
                Session::error($response['msg']);
                return $this->render('refresh', ['params'=>$params]);
            }
            Session::success('刷新成功');
            return $this->render('refresh', ['params'=>$params]);
        }else{
            return $this->render('refresh', ['params'=>$params]);
        }
    }
}
