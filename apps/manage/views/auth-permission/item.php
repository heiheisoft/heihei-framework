<?php
use yii\helpers\Url;
$this->title = $actionId == 'update' ? '修改权限' : '添加权限';
$this->params['breadcrumbs'][] = [
  'label' => '权限列表',
  'url' => Url::to(['auth-permission/list'])
];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('@web/resources/components/vue-2.5.17/dist/vue.min.js');
$this->registerJsFile('@web/resources/components/jquery-validation/dist/jquery.validate.js');
?>

<form class="form-horizontal" method="post" id='form-<?=$item->formName()?>'>
  <div class="form-group">
    <label class="col-sm-3 control-label no-padding-right" for="form-id"><?php if($actionId == 'add'):?><span class="red">*</span> <?php endif;?>权限ID</label>
    <div class="col-sm-9">
      <?php if($actionId == 'add'):?>
      <input type="text" id="form-field-id" name="id" placeholder="路由地址" class="col-xs-10 col-sm-8" required="required" data-msg-required="请输入权限路由地址"/>
      <?php else: ?>
      <input type="text" id="form-field-id" name="id" class="col-xs-10 col-sm-8" value="<?=$item->id?>" readolny="readolny"/>
      <?php endif;?>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label no-padding-right" for="form-name"><span class="red">*</span>名称</label>
    <div class="col-sm-9">
      <input type="text" id="form-field-name" name="name" placeholder="权限名称" class="col-xs-10 col-sm-5" value="<?=$item->name?>" required="required" data-msg-required="请输入权限名称"/>
    </div>
  </div>
  <div class="clearfix form-actions">
    <div class="col-md-offset-3 col-md-9">
      <button class="btn btn-info btn-confirm" type="button">
        <i class="ace-icon fa fa-check bigger-110"></i>
        提交
      </button>

      <!-- &nbsp; &nbsp; &nbsp;
      <button class="btn" type="reset">
        <i class="ace-icon fa fa-undo bigger-110"></i>
        重置
      </button> -->
    </div>
  </div>          
</form>
<?php $this->beginBlock('javascript'); ?>
<script type="text/javascript">
  jQuery(function(){
    $("#form-<?=$item->formName()?>").validate({
      submitHandler:function(form){        
        //form.submit();
        console.log('验证成功！');
        var url = "<?=Url::to($actionId == 'update' ? ['/api/auth-permission/update','id'=>$item->id] : ['/api/auth-permission/add'])?>";
        var postData = $(form).serializeArray();
        console.log(postData, url);
        $.post(url, postData, function(result){
          if(result.code == 'SUCCESS'){
            //window.location.href = "<?=Url::to(['auth-role/list']) ?>";
            $.messager.popup("<?=$actionId == 'update' ? '修改成功！' : '添加成功！'?>");
          }
          else{
            $.messager.popup(result.message);
          }
        }).error(function(){
          $.messager.popup("网络请求出错！");
        });
        return false;
      },
      invalidHandler:function(event, validator){
        var errorCount = validator.numberOfInvalids();
        if(errorCount > 0){
          var msg = validator.errorList[0].message;
          //salert(msg);
          $.messager.popup(msg);
        }
      },
      errorPlacement: function(error, element) {  
          //console.log(error, element); 
      }
    });

    $(".btn-confirm").on('click', function(){
      $("#form-<?=$item->formName()?>").submit();
    });
  });
    
</script>
<?php $this->endBlock(); ?>