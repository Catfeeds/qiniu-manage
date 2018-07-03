<?php

namespace backend\models;

use common\libs\Cache;

/**
 * This is the model class for table "{{%permission_category}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $isShow
 * @property string $description
 *
 * @property Permission[] $permissions
 */
class PermissionCategory extends \yii\db\ActiveRecord
{

    public static $isShowLabels = ['隐藏', '显示'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%permission_category}}';
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
            [['name'], 'required'],
            [['name'], 'string', 'max' => 50],
            [['isShow'], 'integer', 'max' => 1],
            [['description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '权限分类ID',
            'name' => '分类名称',
            'isShow' => '是否显示',
            'description' => '分类描述',
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
     * @return \yii\db\ActiveQuery
     */
    public function getPermissions()
    {
        return $this->hasMany(Permission::class, ['categoryID' => 'id']);
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
        Cache::set('SHOW_PERMISSION_CATEGORY_OPTIONS', $cache);
        $cache = self::find()->select('name')->indexBy('id')->column();
        Cache::set('ALL_PERMISSION_CATEGORY_OPTIONS', $cache);
    }

    /**
     * 全部可见分类选项
     *
     * @return array|mixed
     */
    public static function showOptions(){
        $cacheName = 'SHOW_PERMISSION_CATEGORY_OPTIONS';
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
        $cacheName = 'ALL_PERMISSION_CATEGORY_OPTIONS';
        $cache = Cache::get($cacheName);
        if($cache === false){
            $cache = self::find()->select('name')->indexBy('id')->column();
            Cache::set($cacheName, $cache);
        }
        return $cache;
    }
}
