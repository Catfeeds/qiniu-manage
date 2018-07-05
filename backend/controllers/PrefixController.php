<?php

namespace backend\controllers;

use common\models\Bucket;
use common\services\PrefixService;
use Yii;
use common\models\Prefix;
use yii\web\NotFoundHttpException;
use common\libs\Session;

/**
 * PrefixController implements the CRUD actions for Prefix model.
 */
class PrefixController extends ContentController
{

    /**
     * 前缀列表.
     *
     * @return string
     */
    public function actionIndex()
    {
        if($this->request()->isAjax){
            $options = [
                'join' => [],
                'view' => 'index',
                'like' => [],
                'where' => [],
                'order' => 'createTime desc'
            ];
            $params = $this->post();
            if(isset($params['bucketID'])){
                $options['where'][] = ['in', 'bucketiD', [0, $params['bucketID']]];
            }
            $columns = ['id', 'accountID:qiniuAccount', 'bucketID:qiniuBucket', 'prefix', 'createTime:dateTime', 'updateTime:dateTime'];
            $this->baseIndex(Prefix::class, $columns, $options);
        }else{
            return $this->render('index');
        }
    }

    /**
     * 前缀详情.
     *
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $columns = ['id', 'accountID:qiniuAccount', 'bucketID:qiniuBucket', 'prefix', 'createTime:dateTime', 'updateTime:dateTime'];
        return $this->baseView($this->findModel($id), $columns);
    }

    /**
     * 创建前缀.
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Prefix();
        if ($this->request()->isPost) {
            $post = Yii::$app->request->post();
            $response = PrefixService::createPrefix($post['prefix'], $post['bucketID']);
            if($response['status'] == 0){
                Session::error($response['msg']);
                return $this->baseForm($model, 'prefix/_form', $response['msg']);
            }
            Session::success('新建前缀成功');
            return $this->redirect(['content/close']);
        } else {
            return $this->baseForm($model, 'prefix/_form');
        }
    }

    /**
     * 编辑前缀.
     *
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        if($this->request()->isPost){
            if($post['bucketID']){
                $bucket = Bucket::get($post['bucketID']);
                $post['accountID'] = $bucket['accountID'];
            }else{
                $post['bucketID'] = $post['accountID'] = 0;
            }
        }
        if ($model->load($post, '') && $model->save()) {
            Session::success('编辑前缀成功');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->baseForm($model, 'prefix/_form');
        }
    }

    /**
     * 删除前缀.
     *
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Session::success('删除前缀成功');
        return $this->redirect(['index']);
    }

    /**
     * Finds the Prefix model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Prefix the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Prefix::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
