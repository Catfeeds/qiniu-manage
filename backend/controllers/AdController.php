<?php

namespace backend\controllers;

use common\libs\Session;
use common\models\Ad;
use common\services\AdService;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Class AdController
 * @package backend\controllers
 */
class AdController extends ContentController
{

    /**
     * 广告列表
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
                'positionID:adPosition',
                'type:adType',
                'title',
                'content:image',
                'isShow:show',
                'createTime:dateTime',
            ];
            $this->baseIndex(Ad::class, $column, $options);
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
            'type:adType',
            'positionID:adPosition',
            'url',
            [
                'attribute' => 'content',
                'format' => ['image', 'html'],
            ],
            'openType:adOpenType',
            'isShow:show',
            'sort',
            'createTime:dateTime',
            'updateTime:dateTime',
        ];
        return $this->baseView($this->findModel($id), $columns);
    }

    /**
     * 新建广告
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Ad();
        $post = Yii::$app->request->post();
        if($post && !isset($post['isShow'])){
            $post['isShow'] = 'off';
        }
        if ($model->load($post, '') && $model->save()) {
            Session::success('新建广告成功');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->baseForm($model, 'ad/_form');
        }
    }

    /**
     * 编辑广告
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
            Session::success('编辑广告成功');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->baseForm($model, 'ad/_form');
        }
    }

    /**
     * 删除广告成功
     *
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();
        Session::success('删除广告成功');
        return $this->redirect(['index']);
    }

    /**
     * Finds the Ad model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ad the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ad::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
