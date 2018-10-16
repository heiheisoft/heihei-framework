<?php
use yii\helpers\Url;

$this->title = '管理员管理';

$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('@web/resources/components/vue-2.5.17/dist/vue.min.js');

$this->registerCssFile('@web/resources/components/datatables/media/css/dataTables.bootstrap.css');
$this->registerJsFile('@web/resources/components/_mod/vue/vue.dataTables.js');
$this->registerJsFile('@web/js/vue.filters.js');
?>
<div class="widget-container-col">
    <!-- #section:custom/widget-box -->
    <div class="widget-box collapsed" id="widget-box-1">
        <div class="widget-header">
            <h5 class="widget-title">搜索</h5>

            <!-- #section:custom/widget-box.toolbar -->
            <div class="widget-toolbar">
                <a href="#" data-action="collapse">
                    <i class="ace-icon fa fa-chevron-down"></i>
                </a>
            </div>

            <!-- /section:custom/widget-box.toolbar -->
        </div>

        <div class="widget-body">
            <div class="widget-main">
                <form class="form-inline form-search" id="form-search">
                  <div class="form-group">
                    <label>账号</label>
                    <input type="text" class="form-control" name="username" id="username" placeholder="账号">
                  </div>
                  <div class="form-group">
                    <label>手机号</label>
                    <input type="text" class="form-control" name="mobile" id="mobile" placeholder="手机号">
                  </div>
                  <div class="form-group">
                    <label>全名</label>
                    <input type="text" class="form-control" name="full_name" id="full_name" placeholder="全名">
                  </div>
                  <div class="form-group">
                    <label>E-mail</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="全名">
                  </div>
                  <button type="button" class="btn btn-sm btn-success btn-search">搜索</button>
                </form>
            </div>
        </div>
    </div>

    <!-- /section:custom/widget-box -->
</div>

<br/>


<!-- div.table-responsive -->

<!-- div.dataTables_borderWrap -->
<div class="clearfix">
    <div class="pull-left">
        <a class ="btn btn-white btn-info btn-bold" href="<?=Url::to(['/manager/add'])?>"><i class="ace-icon glyphicon glyphicon-plus blue"></i>添加角色</a>
    </div>
    <div class="pull-right tableTools-container"></div>
</div>
<div id="manager-list">
    
    <table id="manager-table" class="table table-striped table-bordered table-hover dataTable">
        <thead>
            <tr>
                <th class="center" >
                    <label class="pos-rel">
                        <input type="checkbox" class="ace" />
                        <span class="lbl"></span>
                    </label>
                </th>
                <th data-orderable="true" data-col-name="username">账号</th>
                <th data-orderable="true" data-col-name="mobile" class="hidden-480">手机号</th>
                <th data-orderable="true" data-col-name="full_name" class="hidden-480">全名</th>
                <th data-orderable="true" class="hidden-480">E-MAIL</th>
                <th data-orderable="true" data-col-name="created_at" class="hidden-480"><i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>添加时间</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
        </thead>

        <tbody class="hide">
            <tr v-for="manager in list">
                <td class="center">                    
                    <label class="pos-rel">

                        <input type="checkbox" class="ace" />
                        <span class="lbl">ID:{{manager.id}}</span>
                    </label>
                </td>

                <td>{{manager.username}}</td>
                <td class="hidden-480">{{manager.mobile}}</td>
                <td class="hidden-480">{{manager.full_name}}</td>
                <td class="hidden-480">{{manager.email}}</td>
                <td class="hidden-480">{{manager.created_at|timestampFormat}}</td>
                <td>
                    <span class="label label-sm label-inverse arrowed-in">{{manager.status_text}}</span>
                </td>

                <td>
                    <div class="hidden-sm hidden-xs action-buttons">
                        <a class="blue" href="javascript:void(-1);" v-on:click="resetPassword(manager.id)">
                            <i class="ace-icon fa fa-key bigger-130"></i>重置密码
                        </a>

                        <a class="green" v-bind:href="toUpdate(manager.id)" v-on:click="">
                            <i class="ace-icon fa fa-pencil bigger-130"></i>修改
                        </a>

                        <a class="red" href="javascript:void(-1);" v-on:click="managerDelete(manager.id)">
                            <i class="ace-icon fa fa-trash-o bigger-130"></i>删除
                        </a>
                    </div>

                    <div class="hidden-md hidden-lg">
                        <div class="inline pos-rel">
                            <button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
                                <i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
                            </button>

                            <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
                                <li>
                                    <a href="javascript:void(-1);" v-on:click="resetPassword(manager.id)" class="tooltip-info" data-rel="tooltip" title="重置密码">
                                        <span class="blue">
                                            <i class="ace-icon fa fa-key bigger-120"></i>
                                        </span>
                                    </a>
                                </li>

                                <li>
                                    <a v-bind:href="toUpdate(manager.id)" class="tooltip-success" data-rel="tooltip" title="修改">
                                        <span class="green">
                                            <i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
                                        </span>
                                    </a>
                                </li>

                                <li>
                                    <a href="javascript:void(-1);" v-on:click="managerDelete(manager.id)" class="tooltip-error" data-rel="tooltip" title="删除">
                                        <span class="red">
                                            <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </td>
            </tr>

        </tbody>
    </table>
</div>
<?php $this->beginBlock('javascript'); ?>
<script type="text/javascript">
$("#form-search .btn-search").on('click', function(){
    var pata = $("#form-search").serializeArray();
    managerList.vueObj.reqParams = pata;
    managerList.vueObj.loadData();
});

var managerList = new VueDataTable({
    el: '#manager-list',
    ajaxOptions:{
        url:"<?=Url::to(['/api/manager/list'])?>"
    },
    methods:{
        toUpdate: function(id){
            var url = "<?=Url::to(['manager/update'])?>";
            return this.urlQuery(url, 'id=' + id);
        },
        managerDelete: function(id){
            var _that = this;     
            $.messager.confirm('删除提示','你确定要删除这个管理员吗？',function(){
                var url = _that.urlQuery("<?=Url::to(['/api/manager/delete'])?>", 'id=' + id);
                $.get(url, function(result){
                    if(result.code == 'SUCCESS'){
                        _that.loadData();
                    }
                    else{
                        $.messager.popup(result.message);
                    }
                }).error(function(){
                    $.messager.popup("网络请求出错！");
                });
            });
        },
        resetPassword: function(id){
            var _that = this;         
            $.messager.confirm('提示','你确定要重置这个管理员密码吗？',function(){
                var url = _that.urlQuery("<?=Url::to(['/api/manager/reset-password'])?>", 'id=' + id);
                $.get(url, function(result){
                    if(result.code == 'SUCCESS'){
                        $.messager.alert(result.data);
                    }
                    else{
                        $.messager.popup(result.message);
                    }
                }).error(function(){
                    $.messager.popup("网络请求出错！");
                });
            });
        }
    }
});



</script>
<?php $this->endBlock();?>