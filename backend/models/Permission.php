<?php

namespace backend\models;
use common\libs\Cache;

/**
 * This is the model class for table "{{%permission}}".
 *
 * @property integer $id
 * @property integer $categoryID
 * @property string $name
 * @property string $controller
 * @property string $action
 * @property string $description
 * @property integer $createTime
 * @property integer $updateTime
 */
class Permission extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%permission}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'controller', 'action'], 'required'],
            [['categoryID', 'createTime', 'updateTime'], 'integer'],
            [['name', 'controller', 'action'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '权限ID',
            'categoryID' => '权限分类ID',
            'name' => '权限名称',
            'controller' => '绑定控制器',
            'action' => '绑定操作',
            'description' => '说明',
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
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(PermissionCategory::class, ['id' => 'categoryID']);
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
        $cache = PermissionCategory::find()->with('permissions')->asArray()->all();
        Cache::set('ALL_PERMISSION_OPTIONS', $cache);
    }

    /**
     * 获取权限选项
     *
     * @return mixed
     */
    public static function allPermissionOptions(){
        $cacheName = 'ALL_PERMISSION_OPTIONS';
        $cache = Cache::get($cacheName);
        if($cache === false){
            $cache = PermissionCategory::find()->with('permissions')->asArray()->all();
            Cache::set($cacheName, $cache);
        }
        return $cache;
    }
}
