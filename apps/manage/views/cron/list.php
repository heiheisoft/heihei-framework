<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '计划任务';

$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('@web/resources/components/vue-2.5.17/dist/vue.min.js');

$this->registerCssFile('@web/resources/components/datatables/media/css/dataTables.bootstrap.css');
$this->registerJsFile('@web/resources/components/_mod/vue/vue.dataTables.js');
$this->registerJsFile('@web/js/vue.filters.js');
?>
<div class="clearfix">
    <div class="pull-left">
        <button class ="btn btn-white btn-info btn-bold" id="fetch-jobs"><i class="ace-icon glyphicon glyphicon-refresh blue"></i>更新任务工作</button>
    </div>
    <div class="pull-right tableTools-container"></div>
</div>
<!-- div.table-responsive -->

<!-- div.dataTables_borderWrap -->
<div id="job-list">
    <table class="table table-striped table-bordered table-hover dataTable">
        <thead>
            <tr>
                <th data-orderable="true" data-col-name="job_name" class="hidden-480">工作名</th>
                <th data-orderable="true" data-col-name="run_name">运行名</th>
                <th data-orderable="true" data-col-name="class_name" class="hidden-480">工作类名</th>
                <th data-orderable="true" data-col-name="last_run_at" class="hidden-480"><i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>最后运行时间</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
        </thead>

        <tbody class="hide">
            <tr v-for="job in list">
                <td  class="hidden-480">{{job.job_name}}</td>
                <td>{{job.run_name}}</td>
                <td class="hidden-480">{{job.class_name}}</td>
                <td class="hidden-480">{{job.last_run_at|timestampFormat}}</td>
                <td>
                    <span class="label label-sm label-inverse arrowed-in">{{job.status_text}}</span>
                </td>
                <td>
                    <div class="hidden-sm hidden-xs action-buttons">
                        <a v-if="job.status == 'normal'" class="blue" href="javascript:void(-1);" v-on:click="stop(job.class_name)" >
                            <i class="ace-icon fa fa-pause-circle bigger-130"></i>暂停
                        </a>

                        <a v-if="job.status == 'stop'" class="green" href="javascript:void(-1);" v-on:click="start(job.class_name)">
                            <i class="ace-icon fa fa-play-circle bigger-130"></i>启动
                        </a>
                    </div>

                    <div class="hidden-md hidden-lg">
                        <div class="inline pos-rel">
                            <button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
                                <i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
                            </button>

                            <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
                                <li v-if="job.status == 'normal'">
                                    <a href="javascript:void(-1);" v-on:click="start(job.class_name)" class="tooltip-info" data-rel="tooltip" title="暂停">
                                        <span class="blue">
                                            <i class="ace-icon fa pause-circle bigger-120"></i>
                                        </span>
                                    </a>
                                </li>

                                <li v-if="job.status == 'stop'">
                                    <a href="javascript:void(-1);" v-on:click="stop(job.class_name)" class="tooltip-success" data-rel="tooltip" title="启动">
                                        <span class="green">
                                            <i class="ace-icon fa fa-play-circle bigger-120"></i>
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
    $('#fetch-jobs').on('click',function(){
        $.get("<?=Url::to(['/api/cron/fetch-jobs'])?>", function(result){
            if(result.code == 'SUCCESS'){
                $.messager.popup("更新任务工作成功！");
                jobList.vueObj.loadData();
            }
            else{
                $.messager.popup(result.message);
            }
        });
    });
});
var jobList = new VueDataTable({
    el: '#job-list',
    ajaxOptions:{
        url:"<?=Url::to(['/api/cron/list'])?>"
    },
    dtOptions:{
        bNoPage:true
    },
    methods:{
        stop:function(className){
            var _that = this;
            $.messager.confirm('提示','你确定要停止当前工作吗？',function(){
                var url = _that.urlQuery("<?=Url::to(['/api/cron/stop'])?>", 'class_name=' + className);
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
        start:function(className){
            var _that = this;
            $.messager.confirm('提示','你确定要开启当前工作吗？',function(){
                var url = _that.urlQuery("<?=Url::to(['/api/cron/start'])?>", 'class_name=' + className);
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