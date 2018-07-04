<?php

use yii\helpers\Html;
use common\models\AuthAccount;

/* @var $this yii\web\View */
/* @var $params array */
?>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-header">
            刷新七牛缓存
        </div>
        <div class="layui-card-body"><form class="layui-form" lay-filter="layui-form" method="post">
                <div class="layui-form-item">
                    <label class="layui-form-label">授权账号</label>
                    <div class="layui-input-block">
                        <select name="accountID" lay-search="" lay-filter="accountID" lay-verify="required">
                            <?php foreach (AuthAccount::options() as $accountID => $alias): ?>
                                <option value="<?= $accountID ?>"><?= $alias ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">Url</label>
                    <div class="layui-input-block">
                        <textarea name="urls" placeholder="请输入Url，多个之间换行分隔，最多100个" class="layui-textarea"></textarea>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">目录</label>
                    <div class="layui-input-block">
                        <textarea name="dirs" placeholder="请输入目录，多个之间换行分隔，最多10个" class="layui-textarea"></textarea>
                    </div>
                </div>

                <div class="layui-form-item layui-layout-admin">
                    <div class="layui-input-block">
                        <div class="layui-footer" style="left: 0;">
                            <?= Html::submitButton('刷新', ['class' => 'btn btn-primary layui-btn' ]) ?>
                            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



<?php $this->beginBlock('js_footer') ?><script>
    layui.config({
        base: '/layuiadmin/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'form'], function() {
        var $ = layui.$
            ,admin = layui.admin
            ,layer = layui.layer
            ,form = layui.form;
        form.val("layui-form", <?= json_encode($params) ?>);
        form.render(null, 'layui-form');
    });
</script>
<?php $this->endBlock(); ?>