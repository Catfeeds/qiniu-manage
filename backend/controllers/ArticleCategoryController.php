<?php

namespace backend\controllers;

use common\libs\Session;
use common\models\ArticleCategory;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Class ArticleCategoryController
 * @package backend\controllers
 */
class ArticleCategoryController extends ContentController
{

    /**
     * 文章分类列表
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
            ];
            $column = [
                'id',
                'name',
                'isShow:show',
                'description',
            ];
            $this->baseIndex(ArticleCategory::class, $column, $options);
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
            'name',
            'description',
            'isShow:show',
        ];
        return $this->baseView($this->findModel($id), $columns);
    }

    /**
     * 新建文章分类
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new ArticleCategory();
        $post = Yii::$app->request->post();
        if($post && !isset($post['isShow'])){
            $post['isShow'] = 'off';
        }
        if ($model->load($post, '') && $model->save()) {
            Session::success('新建文章分类成功');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->baseForm($model, 'article-category/_form');
        }
    }

    /**
     * 编辑文章分类
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
        if ($model->load($post, '') && $model->save()) {
            Session::success('编辑文章分类成功');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->baseForm($model, 'article-category/_form');
        }
    }

    /**
     * 删除文章分类成功
     *
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if(count($model->articles)){
            Session::error('该分类下已有文章，不允许删除');
            return $this->redirect(['index']);
        }
        $model->delete();
        Session::success('删除文章分类成功');
        return $this->redirect(['index']);
    }

    /**
     * Finds the ArticleCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ArticleCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ArticleCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
