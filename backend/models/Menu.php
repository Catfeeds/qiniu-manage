<?php

namespace backend\models;
use common\libs\Cache;

/**
 * This is the model class for table "{{%menu}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $permissionID
 * @property string $icon
 * @property integer $parentID
 * @property integer $isShow
 * @property integer $level
 * @property integer $sort
 * @property integer $createTime
 * @property integer $updateTime
 *
 * @property Menu $parent
 * @property Permission $permission
 * @property array $children
 */
class Menu extends \yii\db\ActiveRecord
{

    public static $isShowLabels = ['隐藏', '显示'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%menu}}';
    }

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->isShow = 1;
        $this->parentID = 0;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['permissionID', 'parentID', 'createTime', 'updateTime', 'isShow', 'level', 'sort'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['icon'], 'string', 'max' => 255],
            [['parentID'], 'default', 'value' => 0],
            [['sort'], 'default', 'value' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '菜单ID',
            'name' => '菜单名称',
            'permissionID' => '关联权限',
            'icon' => '图标',
            'parentID' => '上级',
            'level' => '等级',
            'sort' => '排序',
            'createTime' => '创建时间',
            'updateTime' => '更新时间',
            'isShow' => '是否显示',
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
        if($this->parentID){
            $this->level = $this->parent->level + 1;
        }else{
            $this->level = 1;
        }
        return parent::beforeSave($insert);
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
        $cache = self::getAllMenuOptions();
        Cache::set('ALL_MENU_OPTIONS', $cache);
    }

    /**
     * 下级菜单
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChildren(){
        return $this->hasMany(Menu::className(), ['parentID'=>'id'])->orderBy('sort');
    }

    /**
     * 上级菜单
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParent(){
        return $this->parentID ? $this->hasOne(Menu::className(), ['id'=>'parentID']) : null;
    }

    /**
     * 关联权限
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPermission(){
        return $this->hasOne(Permission::class, ['id'=>'permissionID']);
    }

    /**
     * 获取菜单选项
     *
     * @return mixed
     */
    public static function allMenuOptions(){
        $cacheName = 'ALL_MENU_OPTIONS';
        $cache = Cache::get($cacheName);
        if($cache === false){
            $cache = self::getAllMenuOptions();
            Cache::set($cacheName, $cache);
        }
        return $cache;
    }

    /**
     * 查询所有菜单数据
     *
     * @return array
     */
    public static function getAllMenuOptions(){
        $menus = [];
        $parentMenus = self::find()->where(['parentID'=>0])->orderBy('sort')->asArray()->all();
        $childrenMenus = self::find()->where(['<>', 'parentID', 0])->orderBy('sort')->asArray()->all();
        foreach ($parentMenus as $key => $parentMenu){
            $menus[] = $parentMenu;
            $subMenus = self::loopMenuData($parentMenu, $childrenMenus);
            if(count($subMenus)){
                $menus = array_merge($menus, $subMenus);
            }
        }
        return $menus;
    }

    /**
     * 循环操作菜单数据
     *
     * @param $parentMenu
     * @param $childrenMenus
     * @return array
     */
    public static function loopMenuData($parentMenu, $childrenMenus){
        $menus = [];
        foreach ($childrenMenus as $childrenMenu){
            if($parentMenu['id'] == $childrenMenu['parentID']){
                $menus[] = $childrenMenu;
                $subMenus = self::loopMenuData($childrenMenu, $childrenMenus);
                if(count($subMenus)){
                    $menus = array_merge($menus, $subMenus);
                }
            }
        }
        return $menus;
    }
}
