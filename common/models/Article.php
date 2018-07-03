<?php

namespace common\models;

use common\libs\Cache;

/**
 * This is the model class for table "{{%article}}".
 *
 * @property integer $id
 * @property integer $categoryID
 * @property integer $productID
 * @property string $title
 * @property string $thumb
 * @property string $brief
 * @property string $keywords
 * @property integer $isShow
 * @property integer $sort
 * @property integer $createTime
 * @property integer $updateTime
 *
 * @property ArticleCategory $category
 * @property ArticleDetail $articleDetail
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article}}';
    }

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->isShow = 1;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['categoryID', 'title'], 'required'],
            [['categoryID', 'productID', 'createTime', 'updateTime'], 'integer'],
            [['title'], 'string', 'max' => 100],
            [['thumb', 'brief', 'keywords'], 'string', 'max' => 255],
            [['isShow'], 'integer', 'max' => 1],
            [['sort'], 'integer', 'max' => 8],
            [['sort', 'isShow'], 'default', 'value' => 1],
            [['categoryID'], 'exist', 'skipOnError' => true, 'targetClass' => ArticleCategory::className(), 'targetAttribute' => ['categoryID' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'categoryID' => '文章分类ID',
            'productID' => '关联产品ID',
            'title' => '标题',
            'thumb' => '缩略图',
            'brief' => '摘要',
            'keywords' => '关键词',
            'isShow' => '是否显示',
            'sort' => '排序',
            'createTime' => '创建时间',
            'updateTime' => '更新时间',
        ];
    }

    /**
     * 校验前处理数据
     *
     * @return bool
     */
    public function beforeValidate()
    {
        $this->isShow === 'on' && $this->isShow = 1;
        ($this->isShow === 'off' || is_null($this->isShow) ) && $this->isShow = 0;
        return parent::beforeValidate();
    }

    /**
    * 写入数据库前处理
    *
    * @param bool $insert
    * @return bool
    */
    public function beforeSave($insert)
    {
        if($insert){
            $this->createTime = $this->updateTime = time();
        } else {
            $this->updateTime = time();
        }
        return parent::beforeSave($insert);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(ArticleCategory::class, ['id' => 'categoryID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleDetail()
    {
        return $this->hasOne(ArticleDetail::class, ['articleID' => 'id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $this->resetCache();
        parent::afterSave($insert, $changedAttributes);
    }

    public function afterDelete()
    {
        $cacheName = 'ARTICLE_'.$this->id;
        Cache::clear($cacheName);
        if($this->productID){
            $product = Product::findOne($this->productID);
            $product->resetCache();
        }
        parent::afterDelete();
    }

    public function resetCache(){
        $cacheName = 'ARTICLE_'.$this->id;
        $cache = self::combileArticle($this->attributes);
        Cache::set($cacheName, $cache);
    }

    /**
     * 获取文章详情
     *
     * @param $articleID
     * @return 缓存值|null|static
     */
    public static function get($articleID){
        $cacheName = 'ARTICLE_'.$articleID;
        $cache = Cache::get($cacheName);
        if($cache === false){
            $cache = self::findOne(['id'=>$articleID]);
            if($cache){
                $cache = self::combileArticle($cache->attributes);
            }
            Cache::set($cacheName, $cache);
        }
        return $cache;
    }

    /**
     * 组合文章数据
     *
     * @param $article
     * @return mixed
     */
    public static function combileArticle($article){
        $articleID = $article['id'];
        $articleDetail = ArticleDetail::findOne(['articleID'=>$articleID]);
        $cache['content'] = $articleDetail['content'];
        return $cache;
    }

    /**
     * 生成文章Url
     *
     * @param $articleID
     * @return string
     */
    public static function articleUrl($articleID){
        return \Yii::$app->params['wap_domain'].'/article/view.html?id='.$articleID;
    }
}
