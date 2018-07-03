<?php

namespace common\services;

use common\libs\UserMsg;
use common\models\Article;
use common\models\ArticleDetail;
use common\models\Product;

/**
 * Class ArticleService
 * @package common\models
 */
class ArticleService
{

    /**
     * 保存文章
     *
     * @param $articleID
     * @param $data
     * @return array
     */
    public static function saveArticle($articleID, $data)
    {
        $return = ['status'=>0, 'msg'=>UserMsg::$timeOut];
        $transaction = \Yii::$app->db->beginTransaction();
        if($articleID){
            $article = Article::findOne($articleID);
            if(!$article){
                $return['msg'] = '文章不存在';
                return $return;
            }
            $articleDetail = ArticleDetail::findOne(['articleID'=>$articleID]);
        }else{
            $article = new Article();
            $articleDetail = new ArticleDetail();
        }
        if(!$article->load($data, '') || !$article->save()){
            $return['msg'] = '保存文章出错,出错原因:'.current($article->getFirstErrors());
            $transaction->rollBack();
            return $return;
        }
        $articleDetail->articleID = $article->id;
        $articleDetail->content = $data['content'];
        if(!$articleDetail->save()){
            $return['msg'] = '保存文章出错,出错原因:'.current($articleDetail->getFirstErrors());
            $transaction->rollBack();
            return $return;
        }
        $transaction->commit();
        // 手动处理缓存
        $article->resetCache();
        if($article->productID){
            $product = Product::findOne($article->productID);
            $product->resetCache();
        }
        $return = ['status'=>1, 'msg'=>'保存文章成功', 'data'=>$article];
        return $return;
    }
}
