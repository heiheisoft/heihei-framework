<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '队列工作';

$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('@web/resources/components/vue-2.5.17/dist/vue.min.js');

$this->registerCssFile('@web/resources/components/datatables/media/css/dataTables.bootstrap.css');
$this->registerJsFile('@web/resources/components/_mod/vue/vue.dataTables.js');
$this->registerJsFile('@web/js/vue.filters.js');
?>
<div class="clearfix">
    <div class="pull-left">
        <button class ="btn btn-white btn-info btn-bold" id="fetch-jobs"><i class="ace-icon glyphicon glyphicon-refresh blue"></i>更新队列工作</button>
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
            </tr>
        </thead>

        <tbody class="hide">
            <tr v-for="job in list">
                <td  class="hidden-480">{{job.job_name}}</td>
                <td>{{job.run_name}}</td>
                <td class="hidden-480">{{job.class_name}}</td>
                <td class="hidden-480">{{job.last_run_at|timestampFormat}}</td>
            </tr>

        </tbody>
    </table>
</div>
<?php $this->beginBlock('javascript'); ?>
<script type="text/javascript">
jQuery(function(){
    $('#fetch-jobs').on('click',function(){
        $.get("<?=Url::to(['/api/queue/fetch-jobs'])?>", function(result){
            if(result.code == 'SUCCESS'){
                $.messager.popup("更新队列工作成功！");
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
        url:"<?=Url::to(['/api/queue/list'])?>"
    },
    dtOptions:{
        bNoPage:true
    }
});

</script>
<?php $this->endBlock();?>