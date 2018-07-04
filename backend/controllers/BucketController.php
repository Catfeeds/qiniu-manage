<?php

namespace backend\controllers;

use Yii;
use common\models\Bucket;
use yii\web\NotFoundHttpException;
use common\libs\Session;

/**
 * BucketController implements the CRUD actions for Bucket model.
 */
class BucketController extends ContentController
{

    /**
     * 七牛空间列表.
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
                'order' => 'createTime desc',
            ];
            $columns = ['id', 'accountID:qiniuAccount', 'bucket', 'domains:domains', 'defaultDomain', 'createTime:dateTime'];
            $this->baseIndex(Bucket::class, $columns, $options);
        }else{
            return $this->render('index');
        }
    }

    /**
     * 七牛空间详情.
     *
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $columns = ['id', 'accountID:qiniuAccount', 'bucket', 'domains:domains', 'defaultDomain', 'createTime:dateTime', 'updateTime:dateTime'];
        return $this->baseView($this->findModel($id), $columns);
    }

    /**
     * 创建七牛空间.
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Bucket();
        $post = Yii::$app->request->post();
        if ($model->load($post, '') && $model->save()) {
            Session::success('新建七牛空间成功');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->baseForm($model, 'bucket/_form');
        }
    }

    /**
     * 编辑七牛空间.
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
            $post = ['defaultDomain' => $post['defaultDomain']];
        }
        if ($model->load($post, '') && $model->save()) {
            Session::success('编辑七牛空间成功');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->baseForm($model, 'bucket/_form');
        }
    }

    /**
     * 删除七牛空间.
     *
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Session::success('删除七牛空间成功');
        return $this->redirect(['index']);
    }

    /**
     * Finds the Bucket model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Bucket the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Bucket::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
