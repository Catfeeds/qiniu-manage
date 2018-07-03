<?php

use yii\helpers\Url;

/* @var $name String */
/* @var $value String */
/* @var $label String */
/* @var $btnLabel String */
/* @var $isImage false */
/* @var $maxFileSize \yii\db\ActiveRecord */
/* @var $extensions \yii\db\ActiveRecord */
/* @var $imgWidth \yii\db\ActiveRecord */
/* @var $imgHeight \yii\db\ActiveRecord */
/* @var $loadJS String */

!isset($maxFileSize) && $maxFileSize = '100M';
!isset($extensions) && $extensions = '';
!isset($imgWidth) && $imgWidth = 100;
!isset($imgHeight) && $imgHeight = 100;
!isset($loadJS) && $loadJS = true;

$qiniuConfig = \Yii::$app->params['qiniu'];
$qiniuDomain = $isImage ? $qiniuConfig['imgDomain'] : $qiniuConfig['downloadDomain'];
?>
<div class="layui-form-item">
    <label class="layui-form-label"><?=$label?></label>
    <div class="layui-input-block">
        <button type="button" class="layui-btn" id="btn-upload-<?=$name?>">
            <i class="layui-icon">&#xe67c;</i><?=$btnLabel?>
        </button>
        <div class="layui-upload-list" <?php if(!$value): ?>style="display: none;"<?php endif; ?> id="upload-result-<?=$name?>">
            <?php if($isImage): ?>
                <img class="layui-upload-img" id="upload-img-<?=$name?>" src="<?=$value?>" width="<?=$imgWidth?>" height="<?=$imgHeight?>">
                <p id="test-upload-demoText"></p>
            <?php else: ?>
                <a href="<?=$value?>" class="btn-cmd" id="upload-file-<?=$name?>">[下载上传文件]</a>
            <?php endif; ?>
        </div>
        <input type="hidden" name="<?=$name?>" id="upload-<?=$name?>">
    </div>
</div>

<?php $this->beginBlock('js_upload_'.$name) ?>
    <?php if($loadJS): ?>
    <script src="/vendor/plupload-2.1.1/js/moxie.js"></script>
    <script src="/vendor/plupload-2.1.1/js/plupload.min.js"></script>
    <script src="/vendor/qiniu/qiniu.min.js"></script>
    <?php endif; ?>
    <script>
        var $ = layui.jquery;
        var jQuery = layui.jquery;
        var uploader = Qiniu.uploader({
            runtimes: 'html5,flash,html4',
            browse_button: 'btn-upload-<?=$name?>',
            uptoken_url: '<?= Url::to(['upload/token']), false ?>'+'?isImg=<?=$isImage ? 1 : 0?>',
            get_new_uptoken: false,
            unique_names: true,
            domain: '<?= $qiniuDomain ?>',
            max_file_size: '<?=$maxFileSize?>',
            flash_swf_url: '/vendor/plupload-2.1.1/js/Moxie.swf',
            max_retries: 3,
            dragdrop: true,
            drop_element: 'container',
            chunk_size: '4mb',
            auto_start: true,
            filters : {
                max_file_size : '<?=$maxFileSize?>',
                prevent_duplicates: true,
                <?php if ($extensions): ?>
                    mime_types: [
                        {extensions : "<?=$extensions?>"}
                    ]
                <?php endif; ?>
            },
            init: {
                'UploadProgress': function(up, file) {
                    layer.load(2);
                },
                'FileUploaded': function(up, file, info) {
                    var domain = up.getOption('domain');
                    var res = jQuery.parseJSON(info);
                    var sourceLink = domain +"/"+ res.key;
                    $("#upload-<?=$name?>").val(sourceLink);
                    $("#upload-file-<?=$name?>").attr('href', sourceLink);
                    $("#upload-img-<?=$name?>").attr("src", sourceLink);
                    $("#upload-result-<?=$name?>").show();
                    layer.closeAll();
                },
                'Error': function(up, err, errTip) {
                    layer.closeAll();
                    switch(err.code){
                        case -600:
                            layer.msg('文件大小超出最大限制(<?=$maxFileSize?>)');
                            break;
                        case -601:
                            layer.msg('文件格式错误');
                            break;
                        default:
                            layer.msg('上传出错,请重试');
                            break;
                    }
                },
            }
        });
    </script>
<?php $this->endBlock(); ?>