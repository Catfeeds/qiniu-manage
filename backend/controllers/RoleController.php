<?php

namespace backend\controllers;

use common\libs\Session;
use backend\models\Role;
use backend\services\RoleService;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Class RoleController
 * @package backend\controllers
 */
class RoleController extends ContentController
{

    /**
     * 角色列表
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
                'description',
                'createTime:dateTime',
            ];
            $this->baseIndex(Role::class, $column, $options);
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
            'permissions:rolePermissions',
            'description',
            'createTime:dateTime',
            'updateTime:dateTime',
        ];
        return $this->baseView($this->findModel($id), $columns);
    }

    /**
     * 新建角色
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Role();
        $post = Yii::$app->request->post();
        if($this->request()->isPost){
            $response = RoleService::saveRole(null, $post);
            if($response['status'] == 0){
                return $this->baseForm($model, 'role/_form', $response['msg']);
            }
            $model = $response['data'];
            Session::success('新建角色成功');
            return $this->redirect(['view', 'id' => $model->id]);
        }else{
            return $this->baseForm($model, 'role/_form');
        }
    }

    /**
     * 编辑角色
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
            $response = RoleService::saveRole($id, $post);
            if($response['status'] == 0){
                return $this->baseForm($model, 'role/_form', $response['msg']);
            }
            $model = $response['data'];
            Session::success('编辑角色成功');
            return $this->redirect(['view', 'id' => $model->id]);
        }else{
            return $this->baseForm($model, 'role/_form');
        }
    }

    /**
     * 删除角色成功
     *
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->permissions;
        $model->delete();
        Session::success('删除角色成功');
        return $this->redirect(['index']);
    }

    /**
     * Finds the Role model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Role the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Role::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
