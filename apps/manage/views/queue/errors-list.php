<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '队列错误日志';

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
                
            </div>
        </div>
    </div>

    <!-- /section:custom/widget-box -->
</div>

<br/>
<!-- div.table-responsive -->

<!-- div.dataTables_borderWrap -->
<div id="job-list">
    <table class="table table-striped table-bordered table-hover dataTable">
        <thead>
            <tr>
                <th data-orderable="true" data-col-name="key">工作类名</th>
                <th>传递值</th>
                <th class="hidden-480">运行结果</th>
                <th data-orderable="true" data-col-name="created_at" class="hidden-480"><i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>最后运行时间</th>
            </tr>
        </thead>

        <tbody class="hide">
            <tr v-for="job in list">
                <td>{{job.key}}</td>
                <td>{{job.value}}</td>
                <td class="hidden-480">{{job.result_desc}}</td>
                <td class="hidden-480">{{job.created_at|timestampFormat}}</td>
            </tr>

        </tbody>
    </table>
</div>
<?php $this->beginBlock('javascript'); ?>
<script type="text/javascript">
var jobList = new VueDataTable({
    el: '#job-list',
    ajaxOptions:{
        url:"<?=Url::to(['/api/queue/errors-list'])?>"
    }
});

</script>
<?php $this->endBlock();?>