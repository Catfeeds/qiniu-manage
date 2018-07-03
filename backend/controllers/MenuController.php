<?php

namespace backend\controllers;

use common\libs\Session;
use Yii;
use backend\models\Menu;
use yii\web\NotFoundHttpException;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class MenuController extends ContentController
{

    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex()
    {
        $parentMenus = Menu::find()->where(['parentID'=>0])->orderBy('sort asc')->all();
        return $this->render('index', [
            'parentMenus' => $parentMenus,
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
            'permission.controller',
            'permission.action',
            'permissionID:menuPermission',
            'icon',
            'parentID:menuParent',
            'isShow:show',
            'sort',
            'createTime:datetime',
            'updateTime:datetime',
        ];
        return $this->baseView($this->findModel($id), $columns);
    }

    /**
     * 新建菜单
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Menu();
        if($this->request()->isGet){
            $params = $this->get();
            if(isset($params['parentID']) && $params['parentID']){
                $model->parentID = $params['parentID'];
            }
        }
        $post = Yii::$app->request->post();
        if($post && !isset($post['isShow'])){
            $post['isShow'] = 'off';
        }
        if ($model->load($post, '') && $model->save()) {
            Session::success('新建菜单成功');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->baseForm($model, 'menu/_form');
        }
    }

    /**
     * 编辑菜单
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
            Session::success('编辑菜单成功');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->baseForm($model, 'menu/_form');
        }
    }

    /**
     * 删除菜单成功
     *
     * @param $id
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if(count($model->children)){
            Session::error('该分类下已有子菜单，不允许删除');
            return $this->redirect(['index']);
        }
        $model->delete();
        Session::success('删除菜单成功');
        return $this->redirect(['index']);
    }

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Menu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
