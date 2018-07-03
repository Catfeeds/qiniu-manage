<?php
namespace backend\controllers;

/**
 * Content controller
 */
class ContentController extends AuthController
{
    /**
     * 操作前处理
     *
     * @param \yii\base\Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        parent::beforeAction($action);
        $this->layout = 'content';
        return true;
    }
}
