<?php
namespace backend\controllers;

use common\libs\Session;
use common\libs\UserMsg;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use common\components\Formatter;

/**
 * Base controller
 */
class BaseController extends Controller
{
    public $admin;

    /**
     * 操作前处理
     *
     * @param \yii\base\Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        $this->admin = \Yii::$app->session->get('admin');
        return true;
    }

    /**
     * 请求错误
     */
    protected function error(){
        die(json_encode(['status'=>0, 'msg'=>UserMsg::$timeOut])) ;
    }

    /**
     * 返回请求结果
     *
     * @param $data
     */
    protected function result($data){
        die(json_encode($data)) ;
    }

    /**
     * 操作成功
     *
     * @param $data
     * @param null $msg
     * @param null $count
     * @param null $extraData
     */
    protected function success($data, $msg=null, $count=null, $extraData=null){
        !$msg && $msg=UserMsg::$success;
        $response = ['code'=>0, 'data'=>$data, 'msg'=>$msg];
        if($count){
            $response['count'] = $count;
        }
        if(is_array($extraData) && count($extraData)){
            $response = array_merge($response, $extraData);
        }
        $this->result($response);
    }

    /**
     * 操作失败
     *
     * @param string $msg
     */
    protected function fail($msg){
        !$msg && $msg=UserMsg::$fail;
        $response = ['code'=>1, 'msg'=>$msg];
        $this->result($response);
    }

    /**
     * 需要登录
     *
     * @param $msg
     */
    protected function authFail($msg=null){
        !$msg && $msg=UserMsg::$userNotLogin;
        $response = ['code'=>1001, 'msg'=>$msg];
        $this->result($response);
    }

    /**
     * 针对Service函数返回格式自动处理
     *
     * @param $return
     */
    protected function autoResult($return){
        if($return['status'] == 1){
            $this->success(isset($return['data']) ? $return['data'] : null, $return['msg']);
        }else{
            $this->fail($return['msg']);
        }
    }

    /**
     * 当前请求
     *
     * @return \yii\console\Request|\yii\web\Request
     */
    protected function request(){
        return \Yii::$app->request;
    }

    /**
     * POST参数
     *
     * @return array|mixed
     */
    protected function post(){
        return $this->request()->post();
    }

    /**
     * GET参数
     *
     * @return array|mixed
     */
    protected function get(){
        return $this->request()->get();
    }

    /**
     * @param $model
     * @param $column
     * @param array $options
     * @return array|string
     */
    protected function baseIndex($model, $column, $options=[]){
        $modelArr = explode('\\', $model);
        $modelName = end($modelArr);
        $request = \Yii::$app->request;
        if($request->isAjax)
        {
            $params = $request->get();
            unset($params['s']);
            $modelQuery = $model::find();
            if(isset($options['join'])){
                foreach ($options['join'] as $v)
                    $modelQuery->joinWith($v);
            }
            foreach ($params as $key => $value){
                if(!$value && $value !== '0'){
                    unset($params[$key]);
                }
            }
            $page = isset($params['page']) ? $params['page'] : 1;
            $limit = isset($params['limit']) ? $params['limit'] : 10;
            unset($params['page']);
            unset($params['limit']);
            !empty($options['where']) &&  $params = array_merge($params, $options['where']);
            $likeArgs = ['name', 'title', 'content', 'order'];
            $likeParams = [];
            foreach ($params as $paramKey => $paramValue){
                if(in_array($paramKey, $likeArgs)){
                    $likeParams[$paramKey] = $paramValue;
                    unset($params[$paramKey]);
                }
            }
            !empty($options['like']) && $likeParams = array_merge($likeParams, $options['like']);
            foreach ($likeParams as $likeParamKey => $likeParamValue){
                $modelQuery->andWhere(['like', $likeParamKey, $likeParamValue]);
            }
            $modelQuery->andWhere($params);
            $count = $modelQuery->count();
            if(!empty($options['order'])){
                $_order = [];
                foreach (explode(',', $options['order']) as $k){
                    if(strstr($k, '.')) {
                        list($t, $c) = explode('.', $k);
                    }else{
                        $t = lcfirst($modelName);
                        $c = $k;
                    }
                    $t = preg_replace_callback('/([A-Z]{1})/', function ($matches) {
                        return '_' . strtolower($matches[0]);
                    }, $t);
                    $k = \Yii::$app->db->tablePrefix . $t . '.' . $c;
                    $_order[] = $k;
                }
                $modelQuery->orderBy(implode($_order, ','));
            }
            if(isset($options['with'])){
                $modelQuery->with($options['with']);
            }
            $models = $modelQuery->limit($limit)->offset(($page - 1) * $limit)->asArray()->all();
            $data = [];
            foreach ($models as $model){
                $row = [];
                foreach ($column as $col){
                    $format = null;
                    if(strstr($col, ':'))
                        list($col, $format) = explode(':', $col);
                    $col_name = $col;
                    if(!isset($model[$col_name])){
                        continue;
                    }
                    $value = '';
                    if(strstr($col_name, '.')) {
                        list($t, $c) = explode('.', $col_name);
                        if(!empty($model[$t])){
                            $value = $model[$t][$c];
                        }
                    }else{
                        $value = $model[$col_name];
                    }
                    if($format)
                        $value = (new Formatter())->format($value, $format);
                    $col_name = str_replace('.', '_', $col_name);
                    $row[$col_name] = $value;
                }
                $data[] = $row;
            }
            $this->success($data, '获取数据成功', $count);
        }else{
            $view = ArrayHelper::getValue($options, 'view', '/common/_list');
            $params = ArrayHelper::getValue($options, 'params', []);
            $params['model']  = $model;
            $params['column'] = $column;
            return $this->render($view, $params);
        }
    }

    /**
     * 渲染表单
     *
     * @param ActiveRecord $model
     * @param $formView
     * @param $sessionError
     * @return string
     */
    protected function baseForm($model, $formView, $sessionError=null){
        $sessionError = $sessionError ? $sessionError : $model->getFirstErrors();
        Session::error($sessionError);
        return $this->render('@app/views/common/_form', ['model'=>$model, 'formView'=>$formView]);
    }

    /**
     * 渲染详情页
     *
     * @param ActiveRecord $model
     * @param $columns
     * @return string
     */
    protected function baseView($model, $columns){
        Session::error($model->getFirstErrors());
        return $this->render('@app/views/common/_view', ['model'=>$model, 'columns'=>$columns]);
    }
}
