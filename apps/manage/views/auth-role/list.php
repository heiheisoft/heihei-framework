<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '角色管理';

$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('@web/resources/components/vue-2.5.17/dist/vue.min.js');

$this->registerCssFile('@web/resources/components/datatables/media/css/dataTables.bootstrap.css');
$this->registerJsFile('@web/resources/components/_mod/vue/vue.dataTables.js');
$this->registerJsFile('@web/js/vue.filters.js');
?>
<!-- div.table-responsive -->

<!-- div.dataTables_borderWrap -->
<div class="clearfix">
    <div class="pull-left">
        <a class ="btn btn-white btn-info btn-bold" href="<?=Url::to(['/auth-role/add'])?>"><i class="ace-icon glyphicon glyphicon-plus blue"></i>添加角色</a>
    </div>
    <div class="pull-right tableTools-container"></div>
</div>
<div id="cron-list">
    <table class="table table-striped table-bordered table-hover dataTable">
        <thead>
            <tr>
                <th data-orderable="true" data-col-name="job_name">名称</th>
                <th data-orderable="true" data-col-name="run_name" class="hidden-480">数据</th>
                <th data-orderable="true" data-col-name="run_name"><i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>更新时间</th>
                <th>操作</th>
            </tr>
        </thead>

        <tbody class="hide">
            <tr v-for="role in list">
                <td>{{role.name}}</td>
                <td  class="hidden-480">{{role.data}}</td>
                <td>{{role.updated_at|timestampFormat}}</td>
                <td>
                    <div class="hidden-sm hidden-xs action-buttons">

                        <a class="green" :href="toUpdate(role.id)">
                            <i class="ace-icon fa fa-pencil bigger-130"></i>修改
                        </a>

                        <a class="red" href="javascript:void(-1);" v-on:click="roleDelete(role.id)">
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
                                    <a :href="toUpdate(role.id)" class="tooltip-success" data-rel="tooltip" title="修改">
                                        <span class="green">
                                            <i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
                                        </span>
                                    </a>
                                </li>

                                <li>
                                    <a href="javascript:void(-1)" v-on:click="roleDelete(role.id)" class="tooltip-error" data-rel="tooltip" title="删除">
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
var cronList = new VueDataTable({
    el: '#cron-list',
    ajaxOptions:{
        url:"<?=Url::to(['/api/auth-role/list'])?>"
    },
    dtOptions:{
        bNoPage:true
    },
    methods:{
        toUpdate:function(id){
            var url = "<?=Url::to(['auth-role/update'])?>";
            return this.urlQuery(url, 'id=' + id);
        },
        roleDelete:function(id){
            var _that = this;
            $.messager.confirm('删除提示','你确定要删除这个角色吗？',function(){
                var url = _that.urlQuery("<?=Url::to(['/api/auth-role/delete'])?>", 'id=' + id);
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