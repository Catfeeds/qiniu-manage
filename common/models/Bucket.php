<?php

namespace common\models;

use common\libs\Cache;

/**
 * This is the model class for table "{{%bucket}}".
 *
 * @property integer $id
 * @property integer $accountID
 * @property string $bucket
 * @property string $domain
 * @property integer $createTime
 * @property integer $updateTime
 *
 * @property AuthAccount $authAccount
 */
class Bucket extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%bucket}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['accountID', 'bucket', 'domain'], 'required'],
            [['accountID', 'createTime', 'updateTime'], 'integer'],
            [['bucket'], 'string', 'max' => 50],
            [['domain'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'accountID' => '授权账号',
            'bucket' => '空间名称',
            'domain' => '空间域名',
            'createTime' => '创建时间',
            'updateTime' => '更新时间',
        ];
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
     * 授权账号
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAccount(){
        return $this->hasOne(AuthAccount::class, ['id'=>'accountID']);
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
        Cache::set('BUCKET_'.$this->id, $this->attributes);
        $cache = self::find()->select('bucket')->indexBy('id')->column();
        Cache::set('ALL_BUCKET_OPTIONS', $cache);
    }

    /**
     * 根据ID查找
     *
     * @param $id
     * @param bool $isModel
     * @return array|null|\common\models\Bucket
     */
    public static function get($id, $isModel=false){
        if($isModel){
            return self::find()->where(['id'=>$id])->one();
        }else{
            $cacheName = 'BUCKET_'.$id;
            $cache = Cache::get($cacheName);
            if($cache === false){
                $cache = self::find()->where(['id'=>$id])->asArray()->one();
                Cache::set($cacheName, $cache);
            }
            $cache['account'] = AuthAccount::get($cache['accountID']);
            return $cache;
        }
    }

    /**
     * 选项
     *
     * @return array|mixed
     */
    public static function options(){
        $cacheName = 'ALL_BUCKET_OPTIONS';
        $cache = Cache::get($cacheName);
        if($cache === false){
            $cache = self::find()->select('bucket')->indexBy('id')->column();
            Cache::set($cacheName, $cache);
        }
        return $cache;
    }
}
