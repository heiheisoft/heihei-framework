<?php 
use yii\widgets\Breadcrumbs;
$links = $homeLink = [
    'label' => '首页',
    'url' => Yii::$app->homeUrl,
    'template'=>"<li><i class=\"ace-icon fa fa-home home-icon\"></i>{link}</li>\n"
];
$links = [];
if(isset($this->params['breadcrumbs'])){
    $links = $this->params['breadcrumbs'];
}
else{    
    unset($homeLink['url']);
    $links[] = $homeLink;
    $homeLink = false;
}
?>
                <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                    <?= Breadcrumbs::widget([
                        'links' => $links,
                        'homeLink' => $homeLink
                    ]) ?><!-- /.breadcrumb -->
                    

                    <!-- #section:basics/content.searchbox -->
                    <div class="nav-search hide" id="nav-search">
                        <form class="form-search">
                            <span class="input-icon">
                                <input type="text" placeholder="搜索 ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
                                <i class="ace-icon fa fa-search nav-search-icon"></i>
                            </span>
                        </form>
                    </div><!-- /.nav-search -->
                    <!-- /section:basics/content.searchbox -->
                </div>