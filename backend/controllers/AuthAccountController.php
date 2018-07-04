<?php

namespace backend\controllers;

use common\models\Bucket;
use common\models\Prefix;
use common\services\AuthAccountService;
use Yii;
use common\models\AuthAccount;
use backend\controllers\ContentController;
use yii\web\NotFoundHttpException;
use common\libs\Session;

/**
 * AuthAccountController implements the CRUD actions for AuthAccount model.
 */
class AuthAccountController extends ContentController
{

    /**
     * 七牛授权账号列表.
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
            $columns = ['id', 'alias', 'accessKey:qiniuAccount', 'secretKey:qiniuAccount', 'createTime:dateTime', 'updateTime:dateTime'];
            $this->baseIndex(AuthAccount::class, $columns, $options);
        }else{
            return $this->render('index');
        }
    }

    /**
     * 七牛授权账号详情.
     *
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $columns = ['id', 'alias', 'accessKey', 'secretKey', 'createTime:dateTime'];
        return $this->baseView($this->findModel($id), $columns);
    }

    /**
     * 创建七牛授权账号.
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new AuthAccount();
        if ($this->request()->isPost) {
            $post = Yii::$app->request->post();
            $response = AuthAccountService::createAuthAccount($post['alias'], $post['accessKey'], $post['secretKey']);
            if($response['status'] == 0){
                Session::error($response['msg']);
                return $this->baseForm($model, 'auth-account/_form', $response['msg']);
            }
            $model = $response['data'];
            Session::success('新建七牛授权账号成功');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->baseForm($model, 'auth-account/_form');
        }
    }

    /**
     * 编辑七牛授权账号.
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
            Session::success('编辑七牛授权账号成功');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->baseForm($model, 'auth-account/_form');
        }
    }

    /**
     * 删除七牛授权账号.
     *
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        Prefix::deleteAll(['bucket'=>$id]);
        Bucket::deleteAll(['accountID'=>$id]);
        $this->findModel($id)->delete();
        Session::success('删除七牛授权账号成功');
        return $this->redirect(['index']);
    }

    /**
     * Finds the AuthAccount model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AuthAccount the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AuthAccount::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
