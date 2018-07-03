<?php

namespace common\libs;


class Session {

    /**
     * 根据缓存名称获取缓存
     *
     * @param $name
     * @return mixed
     */
    public static function get($name){
        return \Yii::$app->session->getFlash($name);
    }

    /**
     * 设置缓存
     *
     * @param $name
     * @param $val
     * @return bool
     */
    public static function set($name, $val){
        return \Yii::$app->session->setFlash($name, $val);
    }

    /**
     * 成功信息
     * @param 缓存名称
     * @return bool
     */
    public static function success($val){
        return self::set('success', $val);
    }

    /**
     * 失败信息
     * @param 缓存名称
     * @return bool
     */
    public static function error($val){
        return self::set('error', $val);
    }
}