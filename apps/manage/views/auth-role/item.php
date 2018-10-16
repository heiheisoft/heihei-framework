<?php
use yii\helpers\Url;
$this->title = $actionId == 'update' ? '修改角色' : '添加角色';
$this->params['breadcrumbs'][] = [
  'label' => '角色列表',
  'url' => Url::to(['auth-role/list'])
];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('@web/resources/components/vue-2.5.17/dist/vue.min.js');
$this->registerJsFile('@web/resources/components/jquery-validation/dist/jquery.validate.js');
?>

<form class="form-horizontal" method="post" id='form-<?=$item->formName()?>' action="<?=Url::to(['/api/auth-role/add'])?>">
  <div class="form-group">
    <label class="col-sm-3 control-label no-padding-right" for="form-name"><span class="red">*</span>名称</label>
    <div class="col-sm-9">
      <input type="text" id="form-field-name" name="name" placeholder="角色名" class="col-xs-10 col-sm-5" value="<?=$item->name?>" required="required" data-msg-required="请输入角色名"/>
    </div>
  </div>
  <div class="form-group">
    <label  class="col-sm-3 control-label no-padding-right">权限列表</label>

    <div class="col-sm-9 row">
      <div class="checkbox">
        <label>
          <input name="data[]" type="checkbox" class="ace" value="all" :checked="isAllChecked" v-on:click="changePermissionAll();"/>
          <span class="lbl">所有</span>
        </label>
      </div>
      <hr class="hr2" />
      <div v-show="!isAllChecked">
        <keep-alive>
            <checkbox-list v-bind:options="permissionOptions" v-bind:select-value="'<?=$item->data?>'"></checkbox-list>
        </keep-alive>
      </div>
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
<?php $this->beginBlock('javascript'); ?>
<script type="text/javascript">
  jQuery(function(){
    $("#form-<?=$item->formName()?>").validate({
      submitHandler:function(form){        
        //form.submit();
        console.log('验证成功！');
        var url = "<?=Url::to($actionId == 'update' ? ['/api/auth-role/update','id'=>$item->id] : ['/api/auth-role/add'])?>";
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
  })

  Vue.component('checkboxList', function(resolve, reject){
    var tpl = '<div v-if="isload == false">正在加载...</div><div class="row" v-else><div class="checkbox col-xs-6 col-sm-4 col-lg-3" v-for="item in list">' 
              + ' <label>'
              + '   <input type="checkbox" name="data[]" class="ace" v-bind:value="item[options.value]" v-bind:checked="checkExist(item[options.value])" />'
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


  var rolePermissions = ',<?=$item->data?>,';
  var authPermissionVue = new Vue({
    el: '#form-<?=$item->formName()?>',
    data: {
      authPermissionList: [],
      isAllChecked: false,
      permissionOptions: {
        url: '<?=Url::to(['/api/auth-permission/list','type'=>'root'])?>',
        value: 'id'
      }
    },
    created: function () {
      this.isAllChecked = <?= $item->data == 'all' ? 'true' : 'false'?>;
    },
    methods:{
      formSubmit:function(){        
        $("#form-<?=$item->formName()?>").submit();
      },
      changePermissionAll: function(){
        this.isAllChecked = !this.isAllChecked;
      }      
    }
});
    
</script>
<?php $this->endBlock(); ?>