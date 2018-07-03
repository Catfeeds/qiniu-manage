<?php
use yii\helpers\Url;
use common\models\Bucket;
?>
<div class="layui-fluid">
    <div class="layui-card">
        <form class="layui-form layui-card-header layuiadmin-card-header-auto">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <select name="bucketID">
                            <option value="">请选择七牛空间</option>
                            <?php foreach (Bucket::options() as $bucketID => $bucket): ?>
                                <option value="<?= $bucketID ?>"><?= $bucket ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="layui-input-inline">
                        <select name="prefix">
                            <option value="">请选择文件前缀</option>
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <button class="layui-btn layuiadmin-btn-list" lay-filter="form-filter" layadmin-event="form_search" type="button">
                        <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                    </button>
                </div>
            </div>
        </form>
        <div class="layui-card-body">
            <table class="layui-table" lay-data="{url:'<?= Url::current()?>', page:true, limit:10, id:'dataTable'}" lay-filter="dataTable">
                <thead>
                <tr>
                    <th lay-data="{field:'path'}">文件名</th>
                    <th lay-data="{field:'extension'}">文件类型</th>
                    <th lay-data="{field:'size'}">文件大小</th>
                    <th lay-data="{field:'timestamp'}">最后更新</th>
                    <th lay-data="{field:'url'}">链接</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<?php $this->beginBlock('js_footer') ?><script>
    layui.config({
        base: '/layuiadmin/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'table']);
</script>
<?php $this->endBlock(); ?>