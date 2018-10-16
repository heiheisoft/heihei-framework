<?php
use yii\helpers\Url;
//$route = $this->context->getRoute();
?>
        <div id="sidebar" class="sidebar responsive ace-save-state">
            <script type="text/javascript">
                try{ace.settings.loadState('sidebar')}catch(e){}
            </script>

            <div class="sidebar-shortcuts" id="sidebar-shortcuts">
                <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
                    <button class="btn btn-success">
                        <i class="ace-icon fa fa-signal"></i>
                    </button>

                    <button class="btn btn-info" title="密码修改">
                        <i class="ace-icon fa fa-pencil"></i>
                    </button>

                    <!-- #section:basics/sidebar.layout.shortcuts -->
                    <button class="btn btn-warning" title="管理员管理">
                        <i class="ace-icon fa fa-users"></i>
                    </button>

                    <button class="btn btn-danger" title="系统设置">
                        <i class="ace-icon fa fa-cogs"></i>
                    </button>

                    <!-- /section:basics/sidebar.layout.shortcuts -->
                </div>

                <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
                    <span class="btn btn-success"></span>

                    <span class="btn btn-info"></span>

                    <span class="btn btn-warning"></span>

                    <span class="btn btn-danger"></span>
                </div>
            </div><!-- /.sidebar-shortcuts -->

            <ul class="nav nav-list">
                <li class="">
                    <a href="<?= Url::to(['/'])?>">
                        <i class="menu-icon fa fa-tachometer"></i>
                        <span class="menu-text"> 控制台     </span>
                    </a>

                    <b class="arrow"></b>
                </li>

                <li class="">
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-cog"></i>
                        <span class="menu-text">
                            系统设置
                        </span>

                        <b class="arrow fa fa-angle-down"></b>
                    </a>

                    <b class="arrow"></b>

                    <ul class="submenu">
                        <li class="">
                            <a href="<?= Url::to(['/auth-role/list'])?>">
                                <i class="menu-icon fa fa-caret-right"></i>
                                角色管理
                            </a>
                            <b class="arrow"></b>
                        </li>
                        <li class="">
                            <a href="<?= Url::to(['/auth-permission/list'])?>">
                                <i class="menu-icon fa fa-caret-right"></i>
                                权限管理
                            </a>
                            <b class="arrow"></b>
                        </li>
                        <li class="">
                            <a href="<?= Url::to(['/manager/list'])?>">
                                <i class="menu-icon fa fa-caret-right"></i>
                                管理员管理
                            </a>
                            <b class="arrow"></b>
                        </li>                        
                        <li class="">
                            <a href="<?= Url::to(['/cron/list'])?>">
                                <i class="menu-icon fa fa-caret-right"></i>
                                计划任务
                            </a>
                            <b class="arrow"></b>
                        </li>
                        <li class="">
                            <a href="<?= Url::to(['/queue/list'])?>">
                                <i class="menu-icon fa fa-caret-right"></i>
                                消息队列
                            </a>
                            <b class="arrow"></b>
                        </li>
                    </ul>
                </li>
            </ul><!-- /.nav-list -->

            <!-- #section:basics/sidebar.layout.minimize -->
            <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
                <i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
            </div>

            <!-- /section:basics/sidebar.layout.minimize -->
        </div>