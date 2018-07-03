<?php

namespace backend\controllers;

use common\libs\Session;
use common\models\AdPosition;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Class AdPositionController
 * @package backend\controllers
 */
class AdPositionController extends ContentController
{

    /**
     * 广告位列表
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
                'key',
                'name',
                'isShow:show',
                'remark',
            ];
            $this->baseIndex(AdPosition::class, $column, $options);
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
            'key',
            'name',
            'remark',
            'isShow:show',
            'createTime:dateTime',
            'updateTime:dateTime',
        ];
        return $this->baseView($this->findModel($id), $columns);
    }

    /**
     * 新建广告位
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new AdPosition();
        $post = Yii::$app->request->post();
        if($post && !isset($post['isShow'])){
            $post['isShow'] = 'off';
        }
        if ($model->load($post, '') && $model->save()) {
            Session::success('新建广告位成功');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->baseForm($model, 'ad-position/_form');
        }
    }

    /**
     * 编辑广告位
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
            Session::success('编辑广告位成功');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->baseForm($model, 'ad-position/_form');
        }
    }

    /**
     * 删除广告位成功
     *
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if(count($model->ads)){
            Session::error('该分类下已有文章，不允许删除');
            return $this->redirect(['index']);
        }
        $model->delete();
        Session::success('删除广告位成功');
        return $this->redirect(['index']);
    }

    /**
     * Finds the AdPosition model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdPosition the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdPosition::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
