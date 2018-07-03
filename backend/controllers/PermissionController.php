<?php

namespace backend\controllers;

use backend\models\PermissionCategory;
use common\libs\Session;
use backend\models\Permission;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Class PermissionController
 * @package backend\controllers
 */
class PermissionController extends ContentController
{

    /**
     * 权限列表
     *
     * @return string
     */
    public function actionIndex()
    {
        $permissionCategories = PermissionCategory::find()->all();
        return $this->render('index', [
            'permissionCategories' => $permissionCategories,
        ]);
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
            'categoryID:permissionCategory',
            'controller',
            'action',
            'description',
            'createTime:dateTime',
            'updateTime:dateTime',
        ];
        return $this->baseView($this->findModel($id), $columns);
    }

    /**
     * 新建权限
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Permission();
        if($this->request()->isGet){
            $params = $this->get();
            if(isset($params['categoryID']) && $params['categoryID']){
                $model->categoryID = $params['categoryID'];
            }
        }
        $post = Yii::$app->request->post();
        if($post && !isset($post['isShow'])){
            $post['isShow'] = 'off';
        }
        if ($model->load($post, '') && $model->save()) {
            Session::success('新建权限成功');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->baseForm($model, 'permission/_form');
        }
    }

    /**
     * 编辑权限
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
            Session::success('编辑权限成功');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->baseForm($model, 'permission/_form');
        }
    }

    /**
     * 删除权限成功
     *
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();
        Session::success('删除权限成功');
        return $this->redirect(['index']);
    }

    /**
     * Finds the Permission model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Permission the loPermissioned model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Permission::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
