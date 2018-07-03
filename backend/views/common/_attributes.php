<?php

use yii\helpers\Url;

/* @var $name String */
/* @var $label String */
/* @var $initData array */

!isset($loadJS) && $loadJS = true;
?>
<div class="layui-form-item">
    <label class="layui-form-label"><?=$label?></label>
    <div class="layui-input-block" style="margin-bottom: 30px;">
        <div id="<?=$name?>AttributeWrap">
            <?php foreach ($initData as $itemData): ?>
                <div class="attribute-item">
                    <div class="layui-input-inline">
                        <input type="text" name="attributes[<?=$itemData['key']?>][name]" value="<?=$itemData['name']?>" placeholder="请输入属性名" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-input-inline">
                        <input type="text" name="attributes[<?=$itemData['key']?>][value]" value="<?=$itemData['value']?>" placeholder="请输入属性值" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-input-inline">
                        <button class="layui-btn btn-remove-attribute-item" type="button">删除</button>
                    </div>
                    <div class="clear"></div>
                </div>
            <?php endforeach; ?>
        </div>
        <div>
            <button class="layui-btn" onclick="addTemplate()" type="button">添加属性</button>
        </div>
    </div>
</div>

<script type="text/html" id="<?=$name?>AttributeTemplate">
    <div class="attribute-item">
        <div class="layui-input-inline">
            <input type="text" name="attributes[{attributesTime}][name]" placeholder="请输入属性名" autocomplete="off" class="layui-input">
        </div>
        <div class="layui-input-inline">
            <input type="text" name="attributes[{attributesTime}][value]" placeholder="请输入属性值" autocomplete="off" class="layui-input">
        </div>
        <div class="layui-input-inline">
            <button class="layui-btn btn-remove-attribute-item" type="button">删除</button>
        </div>
        <div class="clear"></div>
    </div>
</script>

<?php $this->beginBlock('js_attributes_'.time().random_int(100000, 999999)) ?>
    <script>
        var $ = layui.$;
        $(document).on("click", ".btn-remove-attribute-item", function () {
            $(this).closest(".attribute-item").remove();
        });
        function addTemplate() {
            var time = Date.parse(new Date());
            var attributesHtml = getTemplate(time);
            $("#<?=$name?>AttributeWrap").append(attributesHtml);
        }
        function getTemplate(time) {
            var template =  $("#<?=$name?>AttributeTemplate").html();
            template = template.replace(/{attributesTime}/g,time);
            return template;
        }
    </script>
<?php $this->endBlock(); ?>