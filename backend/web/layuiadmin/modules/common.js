/**
 @Name：layuiAdmin 公共业务
 @Author：贤心
 @Site：http://www.layui.com/admin/
 @License：LPPL
 */
 
layui.define(['table', 'form'], function(exports){
    var $ = layui.$,
        layer = layui.layer,
        laytpl = layui.laytpl,
        setter = layui.setter,
        view = layui.view,
        table = layui.table,
        form = layui.form,
        admin = layui.admin;

    //退出
    admin.events.logout = function(){
        //执行退出接口
        admin.req({
            url: "/auth/logout.html",
            type: 'POST',
            data: {},
            done: function(res){
                layer.msg(res.msg, {anim: 0}, function(){
                    if(res.code === 0){
                        location.href = '/login.html';
                    }
                });
            }
        });
    };

    admin.events.tree_expand = function(){
        $(this).attr('layadmin-event', 'tree_collapse').find('i').removeClass('fa-plus').addClass('fa-minus');
        $(this).parent().children('ol.dd-list').toggle();
    };
    admin.events.tree_collapse = function(){
        $(this).attr('layadmin-event', 'tree_expand').find('i').removeClass('fa-minus').addClass('fa-plus');
        $(this).parent().children('ol.dd-list').toggle();
    };
    admin.events.form_search = function(){
        table.reload('dataTable', {
            where: $(this).closest('form').serializeJson(),
            page: {
                curr: 1
            }
        });
    };
    admin.events.delete = function(){
        var confirm = $(this).data("confirm");
        var url = $(this).data("url");
        var confirmIndex = layer.confirm(confirm, function(index){
            layer.close(confirmIndex);
            layer.load(2);
            location.href = url;
        });
    };
    admin.events.create = function(){
        var url = $(this).data("url");
        layerOpen($(this), url);
    };
    admin.events.update = function(){
        var url = $(this).data("url");
        layerOpen($(this), url);
    };
    admin.events.view = function(){
        var url = $(this).data("url");
        layerOpen($(this), url);
    };

    //监听工具条
    table.on('tool', function(obj){
        var data = obj.data;
        switch (obj.event){
            case 'delete':
                var confirm = $(this).data("confirm");
                var url = $(this).data("url");
                var confirmIndex = layer.confirm(confirm, function(index){
                    layer.close(confirmIndex);
                    layer.load(2);
                    location.href = url+"?id="+data.id;
                });
                break;
            case 'update':
            case 'view':
                var url = $(this).data("url")+"?id="+data.id;
                layerOpen($(this), url);
                break;
        }
    });

    form.on('submit', function(data){
        layer.load(2);
    });

    function layerOpen(obj, url) {
        var title = obj.data("title");
        var full = obj.data('full');
        var width = obj.data('width');
        var height = obj.data('height');

        if(!width){
            width = '550px';
        }
        if(!height){
            height = '550px';
        }
        var layerIndex = layer.open({
            type: 2
            ,title: title
            ,content: url
            ,maxmin: true
            ,area: [width, height],
            end: layerOpenEndCallback
        });
        if(full){
            layer.full(layerIndex);
        }
    }
    
    function refreshTab() {
        layer.load(2);
        location.reload();
    }

    var layerOpenEndCallback = function () {
        refreshTab();
    };

    exports('common', {});
});