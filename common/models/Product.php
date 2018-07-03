<?php

namespace common\models;

use common\libs\Cache;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%product}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property integer $companyID
 * @property integer $categoryID
 * @property integer $price
 * @property string $thumb
 * @property string $image
 * @property integer $isShow
 * @property integer $isRecommend
 * @property string $insurerAge
 * @property string $guaranteePeriod
 * @property string $professionLimit
 * @property string $keywords
 * @property string $brief
 * @property integer $saleAmount
 * @property integer $sort
 * @property string $remark
 * @property integer $createTime
 * @property integer $updateTime
 * @property integer $deleteTime
 *
 * @property ProductCategory $category
 * @property ProductCategory $productCategory
 * @property ProductDetail $productDetail
 * @property ProductAttribute[] $productAttributes
 */
class Product extends \yii\db\ActiveRecord
{

    public static $labelIsShow = ['下架', '上架'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product}}';
    }

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->isShow = 1;
        $this->isRecommend = 1;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'code', 'categoryID', 'thumb', 'image', 'insurerAge', 'guaranteePeriod', 'professionLimit'], 'required'],
            [['companyID', 'categoryID', 'price', 'saleAmount', 'createTime', 'updateTime', 'deleteTime', 'sort'], 'integer'],
            [['name', 'thumb', 'image', 'keywords', 'brief', 'remark'], 'string', 'max' => 255],
            [['code', 'insurerAge', 'professionLimit'], 'string', 'max' => 50],
            [['isShow', 'isRecommend'], 'integer', 'max' => 1],
            [['guaranteePeriod'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '商品ID',
            'name' => '商品名称',
            'code' => '保险编号',
            'companyID' => '保险公司',
            'categoryID' => '商品分类ID',
            'price' => '起价',
            'thumb' => '缩略图',
            'image' => '头图',
            'isShow' => '是否上架',
            'isRecommend' => '是否推荐',
            'insurerAge' => '投保年龄',
            'guaranteePeriod' => '保障期限',
            'professionLimit' => '职业限制',
            'keywords' => '关键词',
            'brief' => '摘要',
            'saleAmount' => '前台显示销量',
            'sort' => '排序',
            'remark' => '商家备注,仅商家可见',
            'productAttributes' => '保障内容',
            'createTime' => '创建时间',
            'updateTime' => '更新时间',
            'deleteTime' => '删除时间',
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
        $this->isRecommend === 'on' && $this->isRecommend = 1;
        ($this->isRecommend === 'off' || is_null($this->isRecommend) ) && $this->isRecommend = 0;
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
     * 保险公司
     */
    public function getProductCompany(){
        return $this->hasOne(ProductCompany::class, ['id'=>'companyID']);
    }

    /**
     * 商品分类
     */
    public function getProductCategory(){
        return $this->hasOne(ProductCategory::class, ['id'=>'categoryID']);
    }

    /**
     * 商品详情
     */
    public function getProductDetail(){
        return $this->hasOne(ProductDetail::class, ['productID'=>'id']);
    }

    /**
     * 商品属性
     */
    public function getProductAttributes(){
        return $this->hasMany(ProductAttribute::class, ['productID'=>'id']);
    }



    public function afterSave($insert, $changedAttributes)
    {
        $this->resetCache();
        parent::afterSave($insert, $changedAttributes);
    }

    public function afterDelete()
    {
        $this->resetCache();
        parent::afterDelete();
    }

    public function resetCache(){
        $cacheName = 'ALL_RECOMMEND_PRODUCTS';
        $cache = self::find()->where(['isRecommend'=>1, 'isShow'=>1, 'deleteTime'=>null])->orderBy('sort')->asArray()->all();
        Cache::set($cacheName, $cache);
        $cacheName = 'PRODUCT_'.$this->id;
        $cache = self::combileProduct($this->attributes);
        Cache::set($cacheName, $cache);
    }

    /**
     * 获取推荐商品
     *
     * @return mixed
     */
    public static function getAllRecommend(){
        $cacheName = 'ALL_RECOMMEND_PRODUCTS';
        $cache = Cache::get($cacheName);
        if($cache === false){
            $cache = self::find()->where(['isRecommend'=>1, 'isShow'=>1, 'deleteTime'=>null])->orderBy('sort')->asArray()->all();
            Cache::set($cacheName, $cache);
        }
        return $cache;
    }

    /**
     * 获取商品详情
     *
     * @param $productID
     * @return 缓存值|null|static
     */
    public static function get($productID){
        $cacheName = 'PRODUCT_'.$productID;
        $cache = Cache::get($cacheName);
        if($cache === false){
            $cache = self::findOne(['id'=>$productID]);
            if($cache){
                $cache = self::combileProduct($cache->attributes);
            }
            Cache::set($cacheName, $cache);
        }
        return $cache;
    }

    /**
     * 组合产品数据
     *
     * @param $product
     * \
     * @return mixed
     */
    public static function combileProduct($product){
        $productID = $product['id'];
        $productDetail = ProductDetail::findOne(['productID'=>$productID]);
        $product['price'] /= 100;
        $product['content'] = $productDetail['content'];
        $product['process'] = $productDetail['process'];
        $product['notification'] = $productDetail['notification'];
        $product['notificationUrl'] = \Yii::$app->params['wap_domain'].'/product/notification.html?id='.$productID;
        $productAttributes = ProductAttribute::find()->where(['productID'=>$productID])->asArray()->all();
        $product['attributes'] = $productAttributes;
        $agreements = [];
        $articles = Article::findAll(['isShow'=>1, 'productID'=>$product['id']]);
        foreach ($articles as $article){
            $agreements[] = [
                'title' => $article['title'],
                'url' => Article::articleUrl($article['id']),
            ];
        }
        $product['agreements'] = $agreements;
        return $product;
    }

    /**
     * 全部产品选项
     *
     * @return array|mixed
     */
    public static function allOptions(){
        $options = self::find()->where(['deleteTime'=>null])->select('name')->indexBy('id')->column();
        return $options;
    }
}
