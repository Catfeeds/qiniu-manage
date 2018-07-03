<?php

namespace common\models;
use common\libs\Cache;

/**
 * This is the model class for table "{{%ad_position}}".
 *
 * @property integer $id
 * @property string $key
 * @property string $name
 * @property string $remark
 * @property integer $isShow
 * @property integer $createTime
 * @property integer $updateTime
 *
 * @property Ad[] $ads
 */
class AdPosition extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ad_position}}';
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
            [['createTime', 'updateTime'], 'integer'],
            [['key'], 'string', 'max' => 30],
            [['name', 'remark'], 'string', 'max' => 100],
            [['isShow'], 'integer', 'max' => 1],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键id',
            'key' => '标示',
            'name' => '标题',
            'remark' => '备注',
            'isShow' => '是否显示',
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
    public function getAds()
    {
        return $this->hasMany(Ad::class, ['positionID' => 'id']);
    }

    /**
     * 保存后操作
     *
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        $this->resetCache();
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * 删除后操作
     */
    public function afterDelete()
    {
        $this->resetCache();
        parent::afterDelete();
    }

    /**
     * 重置缓存
     */
    public function resetCache(){
        $cache = self::find()->where(['isShow'=>1])->select('name')->indexBy('id')->column();
        Cache::set('SHOW_AD_POSITION_OPTIONS', $cache);
        $cache = self::find()->select('name')->indexBy('id')->column();
        Cache::set('ALL_AD_POSITION_OPTIONS', $cache);
    }

    /**
     * 全部可见分类选项
     *
     * @return array|mixed
     */
    public static function showOptions(){
        $cacheName = 'SHOW_AD_POSITION_OPTIONS';
        $cache = Cache::get($cacheName);
        if($cache === false){
            $cache = self::find()->where(['isShow'=>1])->select('name')->indexBy('id')->column();
            Cache::set($cacheName, $cache);
        }
        return $cache;
    }

    /**
     * 全部分类选项
     *
     * @return array|mixed
     */
    public static function allOptions(){
        $cacheName = 'ALL_AD_POSITION_OPTIONS';
        $cache = Cache::get($cacheName);
        if($cache === false){
            $cache = self::find()->select('name')->indexBy('id')->column();
            Cache::set($cacheName, $cache);
        }
        return $cache;
    }
}
