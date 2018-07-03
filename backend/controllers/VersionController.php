<?php

namespace backend\controllers;

use common\libs\Session;
use common\models\Version;
use common\services\VersionService;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Class VersionController
 * @package backend\controllers
 */
class VersionController extends ContentController
{

    /**
     * 版本列表
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
                'typeID:versionType',
                'version',
                'adminID:adminName',
                'info',
                'url:versionUrl',
                'createTime:dateTime',
            ];
            $this->baseIndex(Version::class, $column, $options);
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
            'typeID:versionType',
            'version',
            'adminID:adminName',
            'info',
            [
                'attribute' => 'url',
                'format' => ['versionUrl', 'html'],
            ],
            'createTime:dateTime',
        ];
        return $this->baseView($this->findModel($id), $columns);
    }

    /**
     * 新建版本
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Version();
        $post = Yii::$app->request->post();
        if ($model->load($post, '') && $model->save()) {
            Session::success('新建版本成功');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->baseForm($model, 'version/_form');
        }
    }

    /**
     * 编辑版本
     *
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        if ($model->load($post, '') && $model->save()) {
            Session::success('编辑版本成功');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->baseForm($model, 'version/_form');
        }
    }

    /**
     * 删除版本成功
     *
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Session::success('删除版本成功');
        return $this->redirect(['index']);
    }

    /**
     * Finds the Version model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Version the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Version::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
