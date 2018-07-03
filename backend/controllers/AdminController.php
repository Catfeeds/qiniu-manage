<?php

namespace backend\controllers;

use common\libs\Session;
use backend\models\Admin;
use backend\services\AdminService;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Class AdminController
 * @package backend\controllers
 */
class AdminController extends ContentController
{

    /**
     * 管理员列表
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
                'with' => ['roles']
            ];
            $column = [
                'id',
                'username',
                'realName',
                'roles:adminRoles',
                'status:adminStatus',
                'createTime:dateTime',
            ];
            $this->baseIndex(Admin::class, $column, $options);
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
            'username',
            'realName',
            'phone',
            'email',
            'roles:adminRoles',
            'status:adminStatus',
            'createTime:dateTime',
            'updateTime:dateTime',
        ];
        return $this->baseView($this->findModel($id), $columns);
    }

    /**
     * 新建管理员
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Admin();
        $post = Yii::$app->request->post();
        if($post && !isset($post['status'])){
            $post['status'] = 'off';
        }
        if($this->request()->isPost){
            $response = AdminService::saveAdmin(null, $post);
            if($response['status'] == 0){
                return $this->baseForm($model, 'admin/_form', $response['msg']);
            }
            $model = $response['data'];
            Session::success('新建管理员成功');
            return $this->redirect(['view', 'id' => $model->id]);
        }else{
            return $this->baseForm($model, 'admin/_form');
        }
    }

    /**
     * 编辑管理员
     *
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        if($post && !isset($post['status'])){
            $post['status'] = 'off';
        }
        if($this->request()->isPost){
            $response = AdminService::saveAdmin($id, $post);
            if($response['status'] == 0){
                return $this->baseForm($model, 'admin/_form', $response['msg']);
            }
            $model = $response['data'];
            Session::success('编辑管理员成功');
            return $this->redirect(['view', 'id' => $model->id]);
        }else{
            return $this->baseForm($model, 'admin/_form');
        }
    }

    /**
     * 删除管理员成功
     *
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->roles;
        $model->delete();
        Session::success('删除管理员成功');
        return $this->redirect(['index']);
    }

    /**
     * Finds the Admin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Admin the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Admin::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
