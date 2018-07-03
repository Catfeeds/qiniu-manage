<?php

namespace backend\controllers;

use common\libs\Session;
use common\models\Product;
use common\services\ProductService;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Class ProductController
 * @package backend\controllers
 */
class ProductController extends ContentController
{

    /**
     * 保险列表
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
                'where' => ['deleteTime'=>null],
            ];
            $column = [
                'id',
                'name',
                'categoryID:productCategory',
                'companyID:productCompany',
                'price:price',
                'isShow:productShow',
                'isRecommend:recommend',
                'createTime:dateTime',
            ];
            $this->baseIndex(Product::class, $column, $options);
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
            'categoryID:productCategory',
            'companyID:productCompany',
            [
                'attribute' => 'thumb',
                'format' => ['image', 'html'],
            ],
            [
                'attribute' => 'image',
                'format' => ['image', 'html'],
            ],
            'insurerAge',
            'guaranteePeriod',
            'professionLimit',
            'productAttributes:productAttributes',
            'brief',
            'keywords',
            'price:price',
            'saleAmount',
            'sort',
            'remark',
            'isShow:productShow',
            'isRecommend:recommend',
            'createTime:dateTime',
            'updateTime:dateTime',
            'deleteTime:dateTime',
            [
                'attribute' => 'productDetail.content',
                'format' => 'html',
            ],
            [
                'attribute' => 'productDetail.process',
                'format' => 'html',
            ],
            [
                'attribute' => 'productDetail.notification',
                'format' => 'html',
            ],
        ];
        return $this->baseView($this->findModel($id), $columns);
    }

    /**
     * 新建保险
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Product();
        $post = Yii::$app->request->post();
        if($post){
            if(!isset($post['isShow'])){
                $post['isShow'] = 'off';
            }
            if(!isset($post['isRecommend'])){
                $post['isRecommend'] = 'off';
            }
        }
        if($this->request()->isPost){
            $response = ProductService::saveProduct(null, $post);
            if($response['status'] == 0){
                Session::error($response['msg']);
                return $this->baseForm($model, 'product/_form');
            }
            $model = $response['data'];
            Session::success('新建保险成功');
            return $this->redirect(['view', 'id' => $model->id]);
        }else{
            $model->price /= 100;
            return $this->baseForm($model, 'product/_form');
        }
    }

    /**
     * 编辑保险
     *
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        if($post){
            if(!isset($post['isShow'])){
                $post['isShow'] = 'off';
            }
            if(!isset($post['isRecommend'])){
                $post['isRecommend'] = 'off';
            }
        }
        if($this->request()->isPost){
            $response = ProductService::saveProduct($id, $post);
            if($response['status'] == 0){
                Session::error($response['msg']);
                return $this->baseForm($model, 'product/_form');
            }
            $model = $response['data'];
            Session::success('编辑保险成功');
            return $this->redirect(['view', 'id' => $model->id]);
        }else{
            $model->price /= 100;
            return $this->baseForm($model, 'product/_form');
        }
    }

    /**
     * 删除保险成功
     *
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->deleteTime = time();
        if(!$model->save()){
            Session::error($model->getFirstErrors());
            return $this->redirect(['index']);
        }
        Session::success('删除保险成功');
        return $this->redirect(['index']);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
