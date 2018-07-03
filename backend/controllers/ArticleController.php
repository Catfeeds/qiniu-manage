<?php

namespace backend\controllers;

use common\libs\Session;
use common\models\Article;
use common\services\ArticleService;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Class ArticleController
 * @package backend\controllers
 */
class ArticleController extends ContentController
{

    /**
     * 文章列表
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
            $column = [
                'id',
                'title',
                'categoryID:articleCategory',
                'isShow:show',
                'createTime:dateTime',
            ];
            $this->baseIndex(Article::class, $column, $options);
        }else{
            return $this->render('index');
        }
    }

    /**
     * 详情页面
     *
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $columns = [
            'id',
            'title',
            'categoryID:articleCategory',
            'productID:articleProduct',
            [
                'attribute' => 'thumb',
                'format' => ['image', 'html'],
            ],
            'brief',
            'keywords',
            'isShow:show',
            'sort',
            'createTime:dateTime',
            'updateTime:dateTime',
            [
                'attribute' => 'articleDetail.content',
                'format' => 'html',
            ],
        ];
        return $this->baseView($this->findModel($id), $columns);
    }

    /**
     * 新建文章
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Article();
        $post = Yii::$app->request->post();
        if($post && !isset($post['isShow'])){
            $post['isShow'] = 'off';
        }
        if($this->request()->isPost){
            $response = ArticleService::saveArticle(null, $post);
            if($response['status'] == 0){
                Session::error($response['msg']);
                return $this->baseForm($model, 'article/_form', $response['msg']);
            }
            $model = $response['data'];
            Session::success('新建文章成功');
            return $this->redirect(['view', 'id' => $model->id]);
        }else{
            return $this->baseForm($model, 'article/_form');
        }
    }

    /**
     * 编辑文章
     *
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        if($post && !isset($post['isShow'])){
            $post['isShow'] = 'off';
        }
        if($this->request()->isPost){
            $response = ArticleService::saveArticle($id, $post);
            if($response['status'] == 0){
                return $this->baseForm($model, 'article/_form', $response['msg']);
            }
            $model = $response['data'];
            Session::success('编辑文章成功');
            return $this->redirect(['view', 'id' => $model->id]);
        }else{
            return $this->baseForm($model, 'article/_form');
        }
    }

    /**
     * 删除文章成功
     *
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->articleDetail->delete();
        $model->delete();
        Session::success('删除文章成功');
        return $this->redirect(['index']);
    }

    /**
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
