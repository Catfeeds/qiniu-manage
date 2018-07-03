<?php
/**
 * Created by PhpStorm.
 * User: ZL
 * Date: 2017/7/10
 * Time: 16:18
 */

namespace common\models;

use common\libs\Cache;
use yii\db\ActiveRecord;

/**
 * 用户实名认证
 *
 * @property integer $id
 * @property integer $userID
 * @property string $realName
 * @property string $identity
 * @property integer $createTime
 */
class UserAuth extends ActiveRecord
{

    /**
     * 从缓存中读取用户认证信息
     *
     * @param $userID
     * @return array|\common\models\缓存值|null|ActiveRecord
     */
    public static function getByUserID($userID){
        $cacheName = 'USER_AUTH_'.$userID;
        $cache = Cache::get($cacheName);
        if($cache === false){
            $cache = self::find()->where(['userID'=>$userID])->asArray()->one();
            Cache::set($cacheName, $cache);
        }
        return $cache;
    }
}