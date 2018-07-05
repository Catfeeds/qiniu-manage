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
        if(!parent::beforeAction($action)){
            return false;
        }
        $this->layout = 'content';
        return true;
    }

    /**
     * 关闭layer弹窗
     *
     * @return bool
     */
    public function actionClose()
    {
        $this->layout = 'content';
        return $this->render('close');
    }
}
