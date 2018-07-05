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
     * 编辑文件
     *
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        $arr = json_decode(base64_decode($id), true);
        $response = QiniuService::fileInfo($arr[0], $arr[1]);
        if($response['status'] == 0){
            Session::error($response['msg']);
            return $this->redirect(['index']);
        }
        $file = $response['data'];
        $file['key'] = $arr[1];
        $file['id'] = $id;
        if($this->request()->isPost){
            $params = $this->post();
            if(!isset($params['force'])){
                $params['force'] = 'off';
            }
            $response = QiniuService::updateFile($arr[0], $arr[1], $params['key'], $params['mimeType'], $params['type'], $params['force']);
            if($response['status'] == 0){
                Session::error($response['msg']);
                return $this->render('@app/views/common/_form', ['model'=>$file, 'formView'=>'file/_form']);
            }
            Session::success('编辑文件成功');
            return $this->redirect(['content/close']);
        }else{
            return $this->render('@app/views/common/_form', ['model'=>$file, 'formView'=>'file/_form']);
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

    /**
     * 文件预取
     *
     * @return string
     */
    public function actionPrefetchUrls()
    {
        $params = $this->post();
        if($this->request()->isPost){
            $accountID = $params['accountID'];
            $urls = $params['urls'];
            $response = QiniuService::prefetchUrls($accountID, $urls);
            if($response['status'] == 0){
                Session::error($response['msg']);
                return $this->render('refresh', ['params'=>$params]);
            }
            Session::success('文件预取成功');
            return $this->render('prefetch-urls', ['params'=>$params]);
        }else{
            return $this->render('prefetch-urls', ['params'=>$params]);
        }
    }

    /**
     * 删除文件
     *
     * @param $id
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        $arr = json_decode(base64_decode($id), true);
        $response = QiniuService::deleteFile($arr[0], $arr[1]);
        if($response['status'] == 0){
            Session::error($response['msg']);
            return $this->redirect(['index']);
        }
        Session::success('删除文件成功');
        return $this->redirect(['index']);
    }
}
