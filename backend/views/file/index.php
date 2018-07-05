<?php
use yii\helpers\Url;
use common\models\Bucket;
use common\models\Prefix;

$options = Bucket::options();
$params = Yii::$app->request->get();
if(isset($params['marker'])){
    $params['marker'] = '';
}
if(!isset($params['bucketID'])){
    $params['bucketID'] = count($options) ? (count(current($options)) ? current(array_keys(current($options))) : '') : '';
}
$prefixOptions = [];
if($params['bucketID']){
    $prefixOptions = Prefix::find()->where(['in', 'bucketID', [0, $params['bucketID']]])->select('prefix')->indexBy('id')->column();
}
?>
<div class="layui-fluid">
    <div class="layui-card">
        <form class="layui-form layui-card-header layuiadmin-card-header-auto" id="file-filter-form" action="<?=Url::current()?>" lay-filter="layui-form">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <input type="hidden" name="marker" id="marker">
                    <div class="layui-input-inline">
                        <select name="bucketID" lay-filter="bucketID">
                            <option value="">请选择七牛空间</option>
                            <?php foreach (Bucket::options() as $accountAlias => $options): ?>
                                <optgroup label="<?=$accountAlias?>">
                                    <?php foreach ($options as $bucketID => $bucket): ?>
                                        <option value="<?= $bucketID ?>"><?= $bucket ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="layui-input-inline">
                        <select name="prefixID" id="prefixID">
                            <option value="">请选择文件前缀</option>
                            <?php foreach ($prefixOptions as $prefixID => $prefix): ?>
                                <option value="<?= $prefixID ?>"><?= $prefix ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="layui-input-inline">
                        <select name="limit" id="limit">
                            <option value="10">10条/页</option>
                            <option value="20">20条/页</option>
                            <option value="30">30条/页</option>
                            <option value="50">50条/页</option>
                            <option value="70">70条/页</option>
                            <option value="100">100条/页</option>
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <button class="layui-btn layuiadmin-btn-list" lay-filter="form-filter">
                        <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                    </button>
                </div>
            </div>
        </form>
        <div class="layui-card-body">
            <table class="layui-table" lay-filter="dataTable" id="dataTable">
            </table>
            <script type="text/html" id="tableBar">
                <a class="cmd-btn" lay-event='download' data-title="下载文件" >[下载]</a>
                <a class="cmd-btn" lay-event='update' data-url="<?= Url::to(['file/update']) ?>">[编辑]</a>
                <a class="cmd-btn btn-danger" lay-event='delete' data-url="<?= Url::to(['file/delete']) ?>" data-confirm="确定删除这个文件吗?">[删除]</a>
            </script>
        </div>
    </div>
</div>

<?php $this->beginBlock('js_footer') ?><script>
    layui.config({
        base: '/layuiadmin/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'table'], function() {
        var $ = layui.$
            ,table = layui.table
            ,form = layui.form
            ,admin = layui.admin;
        form.val("layui-form", <?= json_encode($params) ?>);
        form.on('select(bucketID)', function (data) {
            $.getJSON('<?=Url::to(['prefix/index'])?>', {bucketID:data.value}, function (response) {
                console.log(response);
                var html_str = '<option value="">请选择文件前缀</option>';
                $.each(response.data, function (index, prefix) {
                    html_str += '<option value="'+prefix.id+'">'+prefix.prefix+'</option>'
                });
                $('#prefixID').html(html_str);
                form.render('select');
            });
        });
        var currentPage = 1;
        var tableOptions = {
            url: '<?=Url::current()?>'
            ,where: $('#file-filter-form').serializeJson()
            ,cols: [[
                {field:'key', title:'文件名'},
                {field:'hash', width:260, title:'哈希值'},
                {field:'mimeType', width:260, title:'MIME'},
                {field:'fsize', width:100, title:'大小'},
                {field:'typeLabel', width:100, title:'存储类型'},
                {field:'putTime', width:200, title:'最后更新'},
                {toolbar:'#tableBar', width:260, title:'操作'}
            ]]
            ,done: function (res, curr, count) {
                var params = $('#file-filter-form').serializeJson();
                var html = '<span class="layui-laypage-count">共 '+count+' 条，当前第 '+currentPage+' 页，总 '+Math.ceil(count/params.limit)+' 页</span>';
                $("#marker").val(res.marker);
                if(res.marker){
                    html += '<span class="layui-laypage-skip"><button type="button" layadmin-event="nextPage" class="layui-laypage-btn">下一页</button></span>';
                }
                $(".layui-table-page").remove();
                if(count){
                    var pageHtml = '<div class="layui-table-page"><div id="layui-table-page2"><div class="layui-box layui-laypage layui-laypage-default" id="layui-laypage-1" style="text-align: center;"><span class="layui-laypage-skip"><button type="button" layadmin-event="nextPage" class="layui-laypage-btn">下一页</button></span><span class="layui-laypage-count"></span></div></div></div>';
                    $(".layui-table-view").append(pageHtml);
                    $('.layui-laypage').html(html);
                    $(".layui-table-page").css('text-align', 'center');
                    currentPage++;
                }
            }
        };
        table.init('dataTable', tableOptions);

        admin.events.nextPage = function(){
            table.reload('dataTable', {
                where: $('#file-filter-form').serializeJson()
            });
        };
    });
</script>
<?php $this->endBlock(); ?>