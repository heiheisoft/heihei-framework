<?php
/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Html;
$baseUrl = Yii::getAlias('@web');
$appName = Yii::$app->name;
$appFullName = $appName . '管理系统';
$title = $this->title ? $this->title . ' - ' . $appFullName : $appFullName;
$isGuest = Yii::$app->user->getIsGuest();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />    
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <meta name="description" content="<?=$appFullName?>" />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($title) ?></title>    
    
    <!-- bootstrap & fontawesome -->

    <link rel="stylesheet" href="<?=$baseUrl ?>/resources/ace-admin/css/bootstrap.css" />    
    <link rel="stylesheet" href="<?=$baseUrl ?>/resources/components/font-awesome/css/font-awesome.css" />

    <!-- page specific plugin styles -->
    <link rel="stylesheet" href="<?=$baseUrl ?>/resources/components/bootstrap-jquery-plugin/css/jquery.bootstrap.css" />

    <!-- text fonts -->
    <link rel="stylesheet" href="<?=$baseUrl ?>/resources/ace-admin/css/ace-fonts.css" />

    <!-- ace styles -->
    <link rel="stylesheet" href="<?=$baseUrl ?>/resources/ace-admin/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
    

    <!--[if lte IE 9]>
        <link rel="stylesheet" href="<?=$baseUrl ?>/resources/ace-admin/css/ace-part2.css" class="ace-main-stylesheet" />
    <![endif]-->
    <link rel="stylesheet" href="<?=$baseUrl ?>/resources/ace-admin/css/ace-skins.css" />
    <link rel="stylesheet" href="<?=$baseUrl ?>/resources/ace-admin/css/ace-rtl.css" />
    <link rel="stylesheet" href="<?=$baseUrl ?>/resources/ace-admin/css/ace-my.css"/>

    <!--[if lte IE 9]>
      <link rel="stylesheet" href="<?=$baseUrl ?>/resources/ace-admin/css/ace-ie.css" />
    <![endif]-->

    <!-- inline styles related to this page -->

    <!-- ace settings handler -->
    <script src="<?=$baseUrl ?>/resources/ace-admin/js/ace-extra.js"></script>

    <!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

    <!--[if lte IE 8]>
    <script src="<?=$baseUrl ?>/resources/components/html5shiv/dist/html5shiv.min.js"></script>
    <script src="<?=$baseUrl ?>/resources/components/respond/dest/respond.min.js"></script>
    <![endif]-->

    <?php $this->head() ?>
</head>
<?php $this->beginBody() ?>
<body class="no-skin">

    <!-- #section:basics/navbar.layout -->
    <?= $isGuest ? '' : $this->render('_navbar',['baseUrl'=>$baseUrl]) ?>
    <!-- /section:basics/navbar.layout -->

    <div class="main-container ace-save-state" id="main-container">
        <script type="text/javascript">
            try{ace.settings.loadState('main-container')}catch(e){}
        </script>

        <!-- #section:basics/sidebar -->
        <?= $isGuest ? '' : $this->render('_sidebar') ?>
        <!-- /section:basics/sidebar -->

        <div class="main-content">
            <div class="main-content-inner">
                <!-- #section:basics/content.breadcrumbs -->
                <?= $isGuest ? '' : $this->render('_breadcrumbs') ?>
                <!-- /section:basics/content.breadcrumbs -->
                
                <div class="page-content">
                    <!-- #section:settings.box -->
                    <?= $isGuest ? '' : $this->render('_settings-container') ?>
                    <!-- /section:settings.box -->
                    
                    <!-- /section:settings.box -->
                    <div class="page-header">
                        <h1>
                            <?= Html::encode($this->title) ?>
                        </h1>
                    </div><!-- /.page-header -->

                    <div class="row">
                        <div class="col-xs-12">
                            <!-- PAGE CONTENT BEGINS -->
                            <?= $content ?>
                            <!-- PAGE CONTENT ENDS -->
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.page-content -->
            </div>
        </div><!-- /.main-content -->

        <?= $this->render('_footer') ?>

        <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
            <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
        </a>
    </div><!-- /.main-container -->

    
    <?php if (isset($this->blocks['footer'])): ?>
        <?= $this->blocks['footer'] ?>
    <?php endif; ?>
</body>
<!-- basic scripts -->

<!--[if !IE]> -->
<script src="<?=$baseUrl ?>/resources/components/jquery/dist/jquery.js"></script>

<!-- <![endif]-->

<!--[if IE]>
<script src="<?=$baseUrl ?>/resources/components/jquery.1x/dist/jquery.js"></script>
<![endif]-->
<script type="text/javascript">
    if('ontouchstart' in document.documentElement) document.write("<script src='<?=$baseUrl ?>/resources/components/_mod/jquery.mobile.custom/jquery.mobile.custom.js'>"+"<"+"/script>");
</script>
<script src="<?=$baseUrl ?>/resources/components/bootstrap/dist/js/bootstrap.js"></script>
<script src="<?=$baseUrl ?>/resources/components/bootstrap-jquery-plugin/js/jquery.bootstrap.min.js"></script>
<script src="<?=$baseUrl ?>/resources/components/bootstrap-jquery-plugin/js/jquery.bootstrap.zh-cn.js"></script>
<!-- page specific plugin scripts -->

<!-- ace scripts -->
<script src="<?=$baseUrl ?>/resources/ace-admin/js/ace-elements.js"></script>
<script src="<?=$baseUrl ?>/resources/ace-admin/js/ace.js"></script>
<?php $this->endBody() ?>
<?php if (isset($this->blocks['javascript'])): ?>
    <?= $this->blocks['javascript'] ?>
<?php endif; ?>
</html>
<?php $this->endPage() ?>
