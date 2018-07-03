<?php

namespace common\services;

use common\libs\UserMsg;
use common\models\Product;
use common\models\ProductAttribute;
use common\models\ProductDetail;

/**
 * Class ProductService
 * @package common\models
 */
class ProductService
{

    /**
     * 保存保险
     *
     * @param $productID
     * @param $data
     * @return array
     */
    public static function saveProduct($productID, $data)
    {
        $return = ['status'=>0, 'msg'=>UserMsg::$timeOut];
        $transaction = \Yii::$app->db->beginTransaction();
        if($productID){
            $product = Product::findOne($productID);
            if(!$product){
                $return['msg'] = '保险不存在';
                return $return;
            }
            $productDetail = ProductDetail::findOne(['productID'=>$productID]);
            ProductAttribute::deleteAll(['productID'=>$productID]);
        }else{
            $product = new Product();
            $productDetail = new ProductDetail();
        }
        $data['price'] *= 100;
        if(!$product->load($data, '') || !$product->save()){
            $return['msg'] = '保存保险出错,出错原因:'.current($product->getFirstErrors());
            $transaction->rollBack();
            return $return;
        }
        $productDetail->productID = $product->id;
        $productDetail->content = $data['content'];
        $productDetail->process = $data['process'];
        $productDetail->notification = $data['notification'];
        if(!$productDetail->save()){
            $return['msg'] = '保存保险出错,出错原因:'.current($productDetail->getFirstErrors());
            $transaction->rollBack();
            return $return;
        }
        if(isset($data['attributes'])){
            $attributes = $data['attributes'];
            foreach ($attributes as $key => $attribute){
                $productAttribute = new ProductAttribute();
                $productAttribute->load($attribute, '');
                $productAttribute->productID = $product->id;
                $productAttribute->key = ''.$key;
                if(!$productAttribute->save()){
                    $return['msg'] = '保存保障内容出错,出错原因:'.current($productAttribute->getFirstErrors());
                    $transaction->rollBack();
                    return $return;
                }
            }
        }
        $transaction->commit();
        // 手动处理缓存
        $product->resetCache();
        $return = ['status'=>1, 'msg'=>'保存保险成功', 'data'=>$product];
        return $return;
    }
}
