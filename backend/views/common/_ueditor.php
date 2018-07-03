<?php

use yii\helpers\Url;

/* @var $name String */
/* @var $label String */
/* @var $value String */
/* @var $loadJS String */

!isset($loadJS) && $loadJS = true;
?>
<div class="layui-form-item">
    <label class="layui-form-label"><?=$label?></label>
    <div class="layui-input-block">
        <textarea id="ueditor_<?=$name?>" name="<?=$name?>" type="text/plain">
            <?= $value ?>
        </textarea>
    </div>
</div>

<?php $this->beginBlock('js_ueditor_'.time().random_int(100000, 999999)) ?>
    <?php if($loadJS): ?>
        <script src="/vendor/ueditor/ueditor.config.js"></script>
        <script src="/vendor/ueditor/ueditor.all.js"></script>
    <?php endif; ?>
    <script>
        var varname="ueditor_<?=time().random_int(100000, 999999)?>";
        window[varname] = UE.getEditor('ueditor_<?=$name?>');
    </script>
<?php $this->endBlock(); ?>