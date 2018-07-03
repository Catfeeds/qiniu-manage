(function($){
	// 表单JSON序列化
    $.fn.serializeJson=function(){  
        var serializeObj={};  
        var array=this.serializeArray();  
        var str=this.serialize();  
        $(array).each(function(){  
            if(serializeObj[this.name]){  
                if($.isArray(serializeObj[this.name])){  
                    serializeObj[this.name].push(this.value);  
                }else{  
                    serializeObj[this.name]=[serializeObj[this.name],this.value];  
                }  
            }else{  
                serializeObj[this.name]=this.value;   
            }  
        });  
        return serializeObj;  
    };  
    // 获取URL参数
    $.fn.getURLParameter=function(){  
        return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,''])[1].replace(/\+/g, '%20'))||null;
    };
    // 获取更新后的html
    var oldHTML = $.fn.html;
    $.fn.formhtml = function () {
        if (arguments.length) return oldHTML.apply(this, arguments);
        $("input,textarea,button", this).each(function () {
            this.setAttribute('value', this.value);
        });
        $(":radio,:checkbox", this).each(function () {
            // im not really even sure you need to do this for "checked"
            // but what the heck, better safe than sorry
            if (this.checked) this.setAttribute('checked', 'checked');
            else this.removeAttribute('checked');
        });
        $("option", this).each(function () {
            // also not sure, but, better safe...
            if (this.selected) this.setAttribute('selected', 'selected');
            else this.removeAttribute('selected');
        });
        return oldHTML.apply(this);
    };
    $.fn.html = $.fn.formhtml;
})(jQuery); 

function isWeiXin(){
    var ua = window.navigator.userAgent.toLowerCase();
    if(ua.match(/MicroMessenger/i) == 'micromessenger'){
        return true;
    }else{
        return false;
    }
}
var ajax = function(type, params, url, callback, async) {
    if(!callback){
        callback = function(msg){
            if(msg.result == 0){
                layer.msg(msg.description,{shift: -1,icon:1},function(){
                    window.location.reload();
                })
            }else{
                layer.msg(msg.description,{icon:2});
            }
        }
    }
    $.ajax({
        url: url,
        async: !async,
        type: type,
        data: params,
        dataType: 'json',
        success: function(data){
            callback(data);
        },
        error:function(){
            if(window.console){
                console.error('*******************************************************************');
                console.error('on  '+url+'  error');
            }
        }
    });
};
$('.selectbox').each(function () {
    var _this = $(this);
    var vals = $(this).data('val');
    if(vals){
        $(vals.toString().split(',')).each(function (index,item) {
            _this.find('option[value='+item+']').attr('selected',true);
        })
    }
    var leftplaceholder = $(this).data('left-placeholder') ?  $(this).data('left-placeholder') : 'search';
    var rightplaceholder = $(this).data('right-placeholder') ?  $(this).data('right-placeholder') : 'search';
    $(this).multiSelect({
        selectableHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='"+leftplaceholder+"'>",
        selectionHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='"+rightplaceholder+"'>",
        afterInit: function(ms){
            var that = this,
                $selectableSearch = that.$selectableUl.prev(),
                $selectionSearch = that.$selectionUl.prev(),
                selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
                selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';
            that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                .on('keydown', function(e){
                    if (e.which === 40){
                        that.$selectableUl.focus();
                        return false;
                    }
                });
            that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                .on('keydown', function(e){
                    if (e.which == 40){
                        that.$selectionUl.focus();
                        return false;
                    }
                });
        },
        afterSelect: function(){
            this.qs1.cache();
            this.qs2.cache();
        },
        afterDeselect: function(){
            this.qs1.cache();
            this.qs2.cache();
        }
    });
});