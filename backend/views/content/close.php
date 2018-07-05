<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body" align="center" style="margin-top: 50px;">
            <div style="margin-bottom: 10px;">
                <span>弹窗将在<span style="color: red" id="time">5</span>秒后关闭</span>
            </div>
            <button class="layui-btn" layadmin-event="close_layer_window">立即关闭</button>
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
        function closeLayerWindow() {
            var index = parent.layer.getFrameIndex(window.name);
            parent.layer.close(index);
        }
        var count = 5;
        setInterval(function(){
            if(count>0){
                count--;
                $("#time").html(count);
            }else{
                closeLayerWindow();
            }
        },1000);

        admin.events.close_layer_window = function(){
            closeLayerWindow();
        };

    });
</script>
<?php $this->endBlock(); ?>