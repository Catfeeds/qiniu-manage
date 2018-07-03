<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use common\libs\Session;

AppAsset::register($this);
$globalTableLayData = json_encode([
    'url'=>Url::current(),
    'page'=>true,
    'limit'=>10,
    'limits' => [10, 20, 30, 50],
])
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
    <?= $content ?>
<?php $this->endBody() ?>
<script>
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
    var successInfo = "<?= Session::get('success') ?>";
    if(successInfo){
        toastr.success(successInfo);
    }
    <?php if($errors = Session::get('error')):?>
        <?php if(is_array($errors)): ?>
            <?php foreach ($errors as $error): ?>
                <?php if($error): ?>
                    toastr.error('<?= $error ?>');
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
                toastr.error('<?= $errors ?>');
        <?php endif; ?>
    <?php endif; ?>
</script>

<?php if(isset($this->blocks['js_footer'])):?>
    <?= $this->blocks['js_footer'] ?>
    <?php unset($this->blocks['js_footer']) ?>
<?php endif; ?>

<?php if(isset($this->blocks) && is_array($this->blocks)): ?>
    <?php foreach ($this->blocks as $key => $block): ?>
        <?= $block ?>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>
<?php $this->endPage() ?>

