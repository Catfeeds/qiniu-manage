<?php

namespace backend\controllers;

use common\libs\Session;
use common\models\ProductCompany;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Class ProductCompanyController
 * @package backend\controllers
 */
class ProductCompanyController extends ContentController
{

    /**
     * 保险公司列表
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
                'code',
                'isShow:show',
                'description',
            ];
            $this->baseIndex(ProductCompany::class, $column, $options);
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
            'code',
            'description',
            'isShow:show',
        ];
        return $this->baseView($this->findModel($id), $columns);
    }

    /**
     * 新建保险公司
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new ProductCompany();
        $post = Yii::$app->request->post();
        if($post && !isset($post['isShow'])){
            $post['isShow'] = 'off';
        }
        if ($model->load($post, '') && $model->save()) {
            Session::success('新建保险公司成功');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->baseForm($model, 'product-company/_form');
        }
    }

    /**
     * 编辑保险公司
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
            Session::success('编辑保险公司成功');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->baseForm($model, 'product-company/_form');
        }
    }

    /**
     * 删除保险公司成功
     *
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if(count($model->products)){
            Session::error('该公司下已有保险，不允许删除');
            return $this->redirect(['index']);
        }
        $model->delete();
        Session::success('删除保险公司成功');
        return $this->redirect(['index']);
    }

    /**
     * Finds the ProductCompany model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProductCompany the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProductCompany::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
