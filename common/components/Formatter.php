<?php
/**
 * Created by PhpStorm.
 * Date: 2018/1/4
 * Time: 16:31
 */

namespace common\components;

use backend\models\Admin;
use backend\models\Menu;
use backend\models\Permission;
use backend\models\PermissionCategory;
use common\libs\CommonFunction;
use common\models\Ad;
use common\models\AdPosition;
use common\models\ArticleCategory;
use common\models\Product;
use common\models\ProductCategory;
use common\models\ProductCompany;
use common\models\User;
use common\models\UserOrder;
use common\models\UserOrderInsured;
use common\models\Version;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

class Formatter extends \yii\i18n\Formatter
{

    /**
     * 将手机号中间四位加密
     * @param string $value
     * @return string mixed
     */
    public function asPhone($value){
        return substr_replace($value, '****', 3, 4);
    }

    public function asDatetime($value, $format = null)
    {
        if(!$value) return '';
        if(!$format)
            $format = 'Y-m-d H:i:s';
        return date($format, $value);
    }

    public function asImage($value, $format = null){
        if(!$value) return '无图像';
        return Html::a(Html::img($value, ['style'=>'max-width:200px;']), $value, ['target'=>'_blank']);
    }

    /*
     * 解密被加密的字符串，会自动判断
     */
    public function asDecrypt($value){
        if(preg_match('/^\d/', $value))
            return $value;
        return CommonFunction::decrypt($value);
    }

    /**
     * 格式化金额
     *
     * @param $value
     * @return string
     */
    public function asMoney($value)
    {
        return CommonFunction::formatMoney($value);
    }

    /**
     * 格式化比例
     *
     * @param $value
     * @return string
     */
    public function asRate($value)
    {
        return $value / 10000 . '%';
    }

    /**
     * 格式化状态
     * 
     * @param $v
     * @param $l
     * @return mixed
     */
    public function asStatus($v, $l)
    {
        return $l[$v];
    }

    /**
     * 格式化显示状态
     *
     * @param $value
     * @return mixed
     */
    public function asShow($value)
    {
        $arr = ['隐藏', '显示'];
        return $arr[$value];
    }

    /**
     * 菜单上级
     *
     * @param $value
     * @return string
     */
    public function asMenuParent($value)
    {
        if(!$value){
            return '无';
        }
        $parent = Menu::findOne($value);
        return $parent ? $parent->name : '无';
    }

    /**
     * 菜单关联权限
     *
     * @param $value
     * @return string
     */
    public function asMenuPermission($value)
    {
        if(!$value){
            return '无';
        }
        $permission = Permission::findOne($value);
        return $permission ? $permission->name : '无';
    }

    /**
     * 文章分类
     *
     * @param $value
     * @return string
     */
    public function asArticleCategory($value)
    {
        if(!$value){
            return '无';
        }
        $articleCategory = ArticleCategory::findOne($value);
        return $articleCategory ? $articleCategory->name : '无';
    }

    /**
     * 文章关联产品
     *
     * @param $value
     * @return string
     */
    public function asArticleProduct($value)
    {
        if(!$value){
            return '无';
        }
        $product = Product::findOne($value);
        return $product ? $product->name : '无';
    }

    /**
     * 显示图片
     *
     * @param $value
     * @return string
     */
    public function asArticleImage($value)
    {
        return "<img src='".$value."' width='100' height='100' />";
    }

    /**
     * 广告类型
     *
     * @param $value
     * @return string
     */
    public function asAdType($value)
    {
        return Ad::$labelTypes[$value];
    }

    /**
     * 广告位
     *
     * @param $value
     * @return string
     */
    public function asAdPosition($value)
    {
        if(!$value){
            return '无';
        }
        $adPosition = AdPosition::findOne($value);
        return $adPosition ? $adPosition->name : '无';
    }

    /**
     * 广告打开方式
     *
     * @param $value
     * @return string
     */
    public function asAdOpenType($value)
    {
        return Ad::$labelOpenTypes[$value];
    }

    /**
     * 保险分类
     *
     * @param $value
     * @return string
     */
    public function asProductCategory($value)
    {
        if(!$value){
            return '无';
        }
        $productCategory = ProductCategory::findOne($value);
        return $productCategory ? $productCategory->name : '无';
    }

    /**
     * 保险公司
     *
     * @param $value
     * @return string
     */
    public function asProductCompany($value)
    {
        if(!$value){
            return '无';
        }
        $productCompany = ProductCompany::findOne($value);
        return $productCompany ? $productCompany->name : '无';
    }

    /**
     * 保险公司
     *
     * @param $value
     * @return string
     */
    public function asProductShow($value)
    {
        return Product::$labelIsShow[$value];
    }

    /**
     * 价格
     *
     * @param $value
     * @return string
     */
    public function asPrice($value){
        return CommonFunction::formatMoney($value);
    }

    /**
     * 推荐
     *
     * @param $value
     * @return string
     */
    public function asRecommend($value){
        $arr = ['否', '是'];
        return $arr[$value];
    }

    /**
     * 权限分类
     *
     * @param $value
     * @return string
     */
    public function asPermissionCategory($value)
    {
        if(!$value){
            return '无';
        }
        $permissionCategory = PermissionCategory::findOne($value);
        return $permissionCategory ? $permissionCategory->name : '无';
    }

    /**
     * 角色权限
     *
     * @param $value
     * @return string
     */
    public function asRolePermissions($value)
    {
        $permissionCategories = PermissionCategory::allOptions();
        $permissionArr = $permissionStrArr = [];
        foreach ($value as $permission){
            $categoryID = $permission['categoryID'];
            if(!array_key_exists($categoryID, $permissionArr)){
                $permissionArr[$categoryID] = [
                    'id' => $permission,
                    'name' => $permissionCategories[$categoryID],
                    'permissions' => []
                ];
            }
            $permissionArr[$categoryID]['permissions'][] = $permission['name'];
        }
        $permissionStr = ' <table class="layui-table"><tbody>';
        $permissionTrArr = [];
        foreach ($permissionArr as $item){
            $permissionTrArr[] = '<tr><td>'.$item['name'].'</td><td>'.implode(' | ', $item['permissions']).'</td>';
        }
        $permissionStr .= implode('', $permissionTrArr);
        $permissionStr .= '</tbody></table>';
        return $permissionStr;
    }

    /**
     * 权限分类
     *
     * @param $value
     * @return string
     */
    public function asAdminStatus($value)
    {
        return Admin::$labelStatus[$value];
    }

    /**
     * 管理员角色
     *
     * @param $value
     * @return string
     */
    public function asAdminRoles($value)
    {
        $roleNameArr = [];
        foreach ($value as $role){
            $roleNameArr[] = $role['name'];
        }
        return implode(' | ', $roleNameArr);
    }

    /**
     * 商品属性
     *
     * @param $value
     * @return string
     */
    public function asProductAttributes($value)
    {
        $attributesArr = [];
        foreach ($value as $role){
            $attributesArr[] = $role['name'].' : '.$role['value'];
        }
        return implode('<br>', $attributesArr);
    }

    /**
     * 认证状态
     *
     * @param $value
     * @return string
     */
    public function asUserAuth($value)
    {
        return $value ? $value['realName'] : '未认证';
    }

    /**
     * 认证状态
     *
     * @param $value
     * @return string
     */
    public function asUserStatus($value)
    {
        return User::$labelStatus[$value];
    }

    /**
     * 解密手机号
     *
     * @param $value
     * @return string
     */
    public function asDecryptPhone($value)
    {
        return CommonFunction::decrypt($value);
    }

    /**
     * 公用用户信息
     *
     * @param $value
     * @return string
     */
    public function asCommonUser($value)
    {
        $user = User::get($value);
        return Html::a("[".CommonFunction::decrypt($user['phone'])."]", 'javascript:;', ['data-url'=>Url::to(['user/view', 'id'=>$value]), "class"=>"cmd-btn", "layadmin-event"=>'view']);
    }

    /**
     * 公用商品信息
     *
     * @param $value
     * @return string
     */
    public function asCommonProduct($value)
    {
        $product = Product::get($value);
        return Html::a("[".$product['name']."]", 'javascript:;', ['data-url'=>Url::to(['product/view', 'id'=>$value]), "class"=>"cmd-btn", "layadmin-event"=>'view']);
    }

    /**
     * 订单状态
     *
     * @param $value
     * @return mixed
     */
    public function asOrderStatus($value){
        return UserOrder::$labelStatus[$value];
    }

    /**
     * 版本类型
     *
     * @param $value
     * @return mixed
     */
    public function asVersionType($value){
        return Version::$labelTypes[$value];
    }

    /**
     * 管理员名称
     *
     * @param $value
     * @return mixed
     */
    public function asAdminName($value){
        $admin = Admin::findOne($value);
        return $admin ? $admin['realName'] : '';
    }

    /**
     * 版本链接
     *
     * @param $value
     * @return mixed
     */
    public function asVersionUrl($value){
        return Html::a('[下载]', $value, ['target'=>'_blank', 'class'=>'cmd-btn']);
    }

    /**
     * 管理员名称
     *
     * @param $value
     * @return mixed
     */
    public function asIdentity($value){
        return CommonFunction::dealIdentity(CommonFunction::decrypt($value));
    }

    /**
     * 管理员名称
     *
     * @param $value
     * @return mixed
     */
    public function asRelationship($value){
        return UserOrderInsured::$relationships[$value];
    }

}