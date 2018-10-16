<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '权限管理';

$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('@web/resources/components/vue-2.5.17/dist/vue.min.js');

$this->registerCssFile('@web/resources/components/datatables/media/css/dataTables.bootstrap.css');
$this->registerJsFile('@web/resources/components/_mod/vue/vue.dataTables.js');
?>
<div class="clearfix">
    <div class="pull-left">
        <button class ="btn btn-white btn-info btn-bold" id="fetch-auth-permission"><i class="ace-icon glyphicon glyphicon-refresh blue"></i>抓取所有权限</button>
    </div>
    <div class="pull-right tableTools-container"></div>
</div>
<!-- div.table-responsive -->

<!-- div.dataTables_borderWrap -->
<div id="cron-list">
    <table class="table table-striped table-bordered table-hover dataTable">
        <thead>
            <tr>
                <th data-orderable="true" data-col-name="run_name">ID</th>
                <th data-orderable="true" data-col-name="run_name">上级ID</th>
                <th data-orderable="true" data-col-name="job_name">名称</th>
                <th>操作</th>
            </tr>
        </thead>

        <tbody class="hide">
            <tr v-for="permission in list">
                <td>{{permission.id}}</td>
                <td>{{permission.parent_id}}</td>
                <td>{{permission.name}}</td>                
                <td>
                    <div class="hidden-sm hidden-xs action-buttons">

                        <a class="green" :href="toUpdate(permission.id)" >
                            <i class="ace-icon fa fa-pencil bigger-130"></i>修改
                        </a>

                        <a class="red" href="javascript:void(-1);" v-on:click="itemDelete(permission.id)">
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
                                    <a :href="toUpdate(permission.id)" class="tooltip-success" data-rel="tooltip" title="修改">
                                        <span class="green">
                                            <i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
                                        </span>
                                    </a>
                                </li>

                                <li>
                                    <a href="javascript:void(-1);" v-on:click="itemDelete(permission.id)" class="tooltip-error" data-rel="tooltip" title="删除">
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
jQuery(function(){
    $('#fetch-auth-permission').on('click',function(){
        $.get("<?=Url::to(['/api/auth-permission/fetch-auth-permission'])?>", function(result){
            if(result.code == 'SUCCESS'){
                $.messager.popup("更新权限列表成功！");
                permissionList.vueObj.loadData();
                //console.log(permissionList.vueObj);
            }
            else{
                $.messager.popup(result.message);
            }
        });
    });
});
var permissionList = new VueDataTable({
    el: '#cron-list',
    ajaxOptions:{
        url:"<?=Url::to(['/api/auth-permission/list'])?>"
    },
    dtOptions:{
        bNoPage:true
    },
    methods:{
        toUpdate:function(id){
            var url = "<?=Url::to(['auth-permission/update'])?>";
            return this.urlQuery(url, 'id=' + escape(id));
        },
        itemDelete:function(id){
            var _that = this;
            $.messager.confirm('删除提示','你确定要删除这个权限吗？',function(){
                var url = _that.urlQuery("<?=Url::to(['/api/auth-permission/delete'])?>", 'id=' + escape(id));
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
        }
    }
});

</script>
<?php $this->endBlock();?>