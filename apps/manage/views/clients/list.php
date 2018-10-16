<?php
use yii\helpers\Url;

$this->title = '客户端管理';

$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('@web/resources/components/vue-2.5.17/dist/vue.min.js');

$this->registerCssFile('@web/resources/components/datatables/media/css/dataTables.bootstrap.css');
$this->registerJsFile('@web/resources/components/_mod/vue/vue.dataTables.js');
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
<div id="client-list">
    
    <table id="client-table" class="table table-striped table-bordered table-hover dataTable">
        <thead>
            <tr>
                <th class="center" >
                    <label class="pos-rel">
                        <input type="checkbox" class="ace" />
                        <span class="lbl"></span>
                    </label>
                </th>
                <th data-orderable="true" data-col-name="client_id">客户端ID</th>
                <th class="hidden-480">秘钥</th>
                <th class="hidden-480">名称</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
        </thead>

        <tbody class="hide">
            <tr v-for="client in list">
                <td class="center">                    
                    <label class="pos-rel">

                        <input type="checkbox" class="ace" />
                    </label>
                </td>

                <td>{{client.client_id}}</td>
                <td class="hidden-480">{{client.secret}}</td>
                <td class="hidden-480">{{client.name}}</td>
                <td>
                    <span class="label label-sm label-inverse arrowed-in">{{client.status}}</span>
                </td>

                <td>
                    <div class="hidden-sm hidden-xs action-buttons">
                        <a class="blue" href="#">
                            <i class="ace-icon fa fa-search-plus bigger-130"></i>
                        </a>

                        <a class="green" href="#">
                            <i class="ace-icon fa fa-pencil bigger-130"></i>
                        </a>

                        <a class="red" href="#">
                            <i class="ace-icon fa fa-trash-o bigger-130"></i>
                        </a>
                    </div>

                    <div class="hidden-md hidden-lg">
                        <div class="inline pos-rel">
                            <button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
                                <i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
                            </button>

                            <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
                                <li>
                                    <a href="#" class="tooltip-info" data-rel="tooltip" title="View">
                                        <span class="blue">
                                            <i class="ace-icon fa fa-search-plus bigger-120"></i>
                                        </span>
                                    </a>
                                </li>

                                <li>
                                    <a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">
                                        <span class="green">
                                            <i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
                                        </span>
                                    </a>
                                </li>

                                <li>
                                    <a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
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


var clientList = new VueDataTable({
    el: '#client-list',
    ajaxOptions:{
        url:"<?=Url::to(['/api/client/list'])?>"
    }
});



</script>
<?php $this->endBlock();?>