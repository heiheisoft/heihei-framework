<?php
use yii\helpers\Url;
$this->title = $actionId == 'update' ? '修改管理员' : '添加管理员';
$this->params['breadcrumbs'][] = [
  'label' => '管理员列表',
  'url' => Url::to(['manager/list'])
];
$this->params['breadcrumbs'][] = $this->title;

$authAssignment = $item->getAuthAssignment('role');
$this->registerJsFile('@web/resources/components/vue-2.5.17/dist/vue.js');
$this->registerJsFile('@web/resources/components/jquery-validation/dist/jquery.validate.js');
$this->registerJsFile('@web/resources/components/_mod/bootstrap-tag/bootstrap-tag.js');

?>

<form class="form-horizontal" method="post" id='form-<?=$item->formName()?>' action="<?=Url::to(['/api/role/add'])?>">
  <div class="form-group">
    <label class="col-sm-3 control-label no-padding-right" for="form-username"><span class="red">*</span>登录账号</label>
    <div class="col-sm-9">
      <input type="text" id="form-field-username" name="username" placeholder="登录账号" class="col-xs-10 col-sm-5" value="<?=$item->username?>" required="required" data-msg-required="请输入登录账号"/>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label no-padding-right" for="form-full_name"><span class="red">*</span>管理员名称</label>
    <div class="col-sm-9">
      <input type="text" id="form-field-name" name="full_name" placeholder="管理员名称" class="col-xs-10 col-sm-5" value="<?=$item->full_name?>" required="required" data-msg-required="请输入管理员名称"/>
    </div>
  </div>
  <?php if($actionId == 'add'): ?>
  <div class="form-group" >
    <label class="col-sm-3 control-label no-padding-right" for="form-password"><span class="red">*</span>密码</label>
    <div class="col-sm-9">
      <input type="password" id="form-field-password" name="password" placeholder="密码" class="col-xs-10 col-sm-5" value="<?=$item->password?>" required="required" data-msg-required="请输入密码"/>
    </div>
  </div>
  <?php endif; ?>
  <div class="form-group" >
    <label class="col-sm-3 control-label no-padding-right" for="form-mobile">手机号码</label>
    <div class="col-sm-9">
      <input type="text" id="form-field-mobile" name="mobile" placeholder="手机号码" class="col-xs-10 col-sm-5" value="<?=$item->mobile?>"/>
    </div>
  </div>
  <div class="form-group" >
    <label class="col-sm-3 control-label no-padding-right" for="form-email">电子邮件</label>
    <div class="col-sm-9">
      <input type="text" id="form-field-email" name="email" placeholder="电子邮件" class="col-xs-10 col-sm-5" value="<?=$item->email?>"/>
    </div>
  </div>
  <div class="form-group" >
    <label class="col-sm-3 control-label no-padding-right" for="form-status">状态</label>
    <div class="col-sm-9">
      <input id="switch-field-status" name="status" value=1 class="ace ace-switch ace-switch-4 btn-flat" type="checkbox"  <?=$item->status == 1 || $actionId != 'update' ? 'checked="checked"' : ''?>/>
      <span class="lbl"></span>
    </div>
  </div>
  <div class="form-group">
    <label  class="col-sm-3 control-label no-padding-right">角色</label>
    <div class="col-sm-9">      
      <input type="hidden" id="form-field-roles"    name="roles"        placeholder="选择角色" class="input-tag col-xs-10 col-sm-5" v-bind:value="roles" required="required" data-msg-required="请选择角色" />
      <div class="tags labels" v-if="authRoleTagArr && authRoleTagArr.length > 0">
        <span class="tag tag-inverse" v-for="item in authRoleTagArr">{{item}}</span>
      </div>
      <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#dialogModal" data-type="role" data-title="选择角色" >选择角色</button>
    </div>    
  </div>
  <div class="form-group">
    <label  class="col-sm-3 control-label no-padding-right">权限</label>
    <div class="col-sm-9">      
      <input type="hidden" id="form-field-permissions" name="permissions" placeholder="选择权限" v-bind:value="permissions" required="required" data-msg-required="请选择权限" />
      <div class="tags" v-if="permissionTagArr && permissionTagArr.length > 0">
        <span class="tag tag-inverse" v-for="item in permissionTagArr">{{item}}</span>
      </div>
      <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#dialogModal" data-type="permission"  data-title="选择权限">选择权限</button>
    </div>    
  </div>
  <div class="form-group">
    <label  class="col-sm-3 control-label no-padding-right">禁用权限</label>
    <div class="col-sm-9">      
      <input type="hidden" id="form-field-disallowed_permissions" name="disallowed_permissions" placeholder="选择禁用权限" v-bind:value="disallowedPermissions" required="required" data-msg-required="请选择禁用权限" />
      <div class="tags" v-if="disallowedPermissionTagArr && disallowedPermissionTagArr.length > 0">
        <span class="tag tag-inverse" v-for="item in disallowedPermissionTagArr">{{item}}</span>
      </div>
      <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#dialogModal" data-type="disallowedPermission"  data-title="选择禁用权限">选择禁用权限</button>
    </div>    
  </div>
  <div class="clearfix form-actions">
    <div class="col-md-offset-3 col-md-9">
      <button class="btn btn-info" type="button" v-on:click="formSubmit();">
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
<?php $this->beginBlock('footer'); ?>
<div id="vue-dialog" class="form-horizontal">
  
  <!-- Modal -->
  <div class="modal fade" id="dialogModal" tabindex="-1" role="dialog" aria-labelledby="dialogModalTitle">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="dialogModalTitle">{{title}}</h4>
        </div>
        <div class="modal-body">          
          <keep-alive>
            <checkbox-list v-bind:options="roleOptions"  v-bind:select-value="'<?=$authAssignment ? implode(',', $authAssignment->getRoles()) : ''?>'" v-if="currentDialogComponent == 'role'"></checkbox-list>
          </keep-alive>
          <keep-alive>
            <checkbox-list v-bind:options="permissionOptions" v-bind:select-value="'<?=$authAssignment ? implode(',', $authAssignment->getPermissions()) : ''?>'"  v-if="currentDialogComponent == 'permission'"></checkbox-list>
          </keep-alive>
          <keep-alive>
            <checkbox-list v-bind:options="disallowedPermissionOptions" v-bind:select-value="'<?=$authAssignment ? implode(',', $authAssignment->getDisallowedPermissions()) : ''?>'" v-if="currentDialogComponent == 'disallowedPermission'"></checkbox-list>
          </keep-alive>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">关闭</button>
          <button type="button" class="btn btn-sm btn-primary" id="btn-dialog-ok">保存</button>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $this->endBlock(); ?>
<?php $this->beginBlock('javascript'); ?>
<script type="text/javascript">
  // 定义一个名为 button-counter 的新组件
  jQuery(function(){
    $("#form-<?=$item->formName()?>").validate({
      submitHandler:function(form){        
        //form.submit();
        console.log('验证成功！');
        var url = "<?=Url::to($actionId == 'update' ? ['/api/manager/update','id'=>$item->id] : ['/api/manager/add'])?>";
        var postData = $(form).serializeArray();
        console.log(postData, url);
        $.post(url, postData, function(result){
          if(result.code == 'SUCCESS'){
            //window.location.href = "<?=Url::to(['role/list']) ?>";
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

    //对话框显示
    $('#dialogModal').on('show.bs.modal', function(e){
      var _that = $(e.relatedTarget);
      vueDialog.title = _that.data('title');
      var type = _that.data('type');
      vueDialog.currentDialogComponent = type;     
    });

    $("#btn-dialog-ok").on('click', function(){
      $ckbs = $('#dialogModal input[type="checkbox"]:checked');
      if($ckbs.length == 0){
        return;
      }
      $sels = new Array();
      $selTags = new Array();
      for (var i = $ckbs.length - 1; i >= 0; i--) {
        $ckItem = $($ckbs[i]);
        $sels.unshift($ckItem.val());
        $selTags.unshift($ckItem.siblings('.lbl').text());
      }
      if(vueDialog.currentDialogComponent == 'role'){
        formVue.authRoleTagArr = $selTags;
        formVue.roles = $sels.join(',');
      }
      else if(vueDialog.currentDialogComponent == 'permission'){
        formVue.permissionTagArr = $selTags;
        formVue.permissions = $sels.join(',');
      }
      else if(vueDialog.currentDialogComponent == 'disallowedPermission'){
        formVue.disallowedPermissionTagArr = $selTags;
        formVue.disallowedPermissions = $sels.join(',');
      }
      $('#dialogModal').modal('hide');
    });
  });

  Vue.component('checkboxList', function(resolve, reject){
    var tpl = '<div v-if="isload == false">正在加载...</div><div class="row" v-else><div class="checkbox col-xs-6 col-sm-4 col-lg-3" v-for="item in list">' 
              + ' <label>'
              + '   <input type="checkbox" class="ace" v-bind:value="item[options.value]" v-bind:checked="checkExist(item[options.value])" />'
              + '   <span class="lbl">{{item[options.name]}}</span>'
              + ' </label>'
              + '</div></div>';
    resolve({
      props: {
        options: Object,
        selectValue: String
      },
      data: function ($vueComponent) {
        if(this.selectValue){
          this.options.selectValue = this.selectValue;
        }
        var options = Object.assign(this.options, Object.assign({name:'name',value:'value',url:'',selectValue:''}, this.options));
        if(options.url == ''){
          return {list:[], isload: false};
        }
        console.log(options, this.selectValue);    
        var _that = this;
        $.get(options.url,function(result){
          _that.isload = true;
          if(result.code == 'FAIL'){
            $.messager.popup(result.message);
            return;
          }
          _that.list = result.data;
        });
        return {list:[], isload: false};
      },
      methods: {
        checkExist: function(value){
          var selectValue = ',' + this.options.selectValue + ',';
          return selectValue.indexOf(',' + value + ',') >= 0;
        }
      },
      template: tpl
    });
  });

  var vueDialog = new Vue({
    el: '#vue-dialog',
    data: {
      title: '',
      currentDialogComponent:'',      
      roleOptions: {
        url: '<?=Url::to(['/api/auth-role/list'])?>',
        value: 'id'
      },
      permissionOptions: {
        url: '<?=Url::to(['/api/auth-permission/list'])?>',
        value: 'id'
      },
      disallowedPermissionOptions: {
        url: '<?=Url::to(['/api/auth-permission/list'])?>',
        value: 'id'
      },
      dialogDataList: null
    }
  });
  var formVue = new Vue({
    el: '#form-<?=$item->formName()?>',
    data: {
      authRoleTagArr: [],      
      roles:'<?= $authAssignment ? implode(',', $authAssignment->getRoles()) : ''?>',
      permissionTagArr: [],
      permissions:'<?= $authAssignment ? implode(',', $authAssignment->getPermissions()) : ''?>',
      disallowedPermissionTagArr: [],
      disallowedPermissions:'<?= $authAssignment ? implode(',', $authAssignment->getDisallowedPermissions()) : ''?>'
    },
    created:function(){
      this.authRoleTagArr = '<?= $authAssignment ? implode(',', $authAssignment->getRoleTags()) : ''?>';
      if(this.authRoleTagArr != ''){
        this.authRoleTagArr = this.authRoleTagArr.split(',');
      }
      this.permissionTagArr = '<?= $authAssignment ? implode(',', $authAssignment->getPermissionTags()) : ''?>';
      if(this.permissionTagArr != ''){
        this.permissionTagArr = this.permissionTagArr.split(',');
      }
      this.disallowedPermissionTagArr = '<?= $authAssignment ? implode(',', $authAssignment->getDisallowedPermissionTags()) : ''?>';
      if(this.disallowedPermissionTagArr != ''){
        this.disallowedPermissionTagArr = this.disallowedPermissionTagArr.split(',');
      }
      console.log(this.authRoleTagArr,this.permissionTagArr,this.disallowedPermissionTagArr);
    },
    methods:{
      formSubmit:function(){        
        $("#form-<?=$item->formName()?>").submit();
      }      
    }
  });   
</script>
<?php $this->endBlock(); ?>