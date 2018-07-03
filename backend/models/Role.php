<?php

namespace backend\models;
use common\libs\Cache;

/**
 * This is the model class for table "{{%role}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $createTime
 * @property integer $updateTime
 *
 * @property Permission[] $permissions
 */
class Role extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%role}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['createTime', 'updateTime'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '角色ID',
            'name' => '角色名称',
            'description' => '描述',
            'permissions' => '权限',
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
     * 角色- 权限
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRolePermissions(){
        return $this->hasMany(RolePermission::class, ['roleID'=>'id']);
    }

    /**
     * 角色包含权限
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPermissions(){
        return $this->hasMany(Permission::class, ['id'=>'permissionID'])->via('rolePermissions');
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
        $cache = Role::find()->asArray()->all();
        Cache::set('ALL_ROLE_OPTIONS', $cache);
    }

    /**
     * 获取权限选项
     *
     * @return mixed
     */
    public static function allOptions(){
        $cacheName = 'ALL_ROLE_OPTIONS';
        $cache = Cache::get($cacheName);
        if($cache === false){
            $cache = Role::find()->asArray()->all();
            Cache::set($cacheName, $cache);
        }
        return $cache;
    }
}
