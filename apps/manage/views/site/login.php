<?php
use yii\helpers\Url;
$baseUrl = Yii::$app->getRequest()->getBaseUrl();
$request = Yii::$app->getRequest();
$appName = Yii::$app->name;
$appFullName = $appName . '管理系统';
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="<?= Yii::$app->charset ?>" />
    <title>登录 - <?=$appFullName?></title>

    <meta name="description" content="User login page" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="<?=$baseUrl ?>/resources/ace-admin/css/bootstrap.css" />
    <link rel="stylesheet" href="<?=$baseUrl ?>/resources/components/bootstrap-jquery-plugin/css/jquery.bootstrap.css" />
    <link rel="stylesheet" href="<?=$baseUrl ?>/resources/components/font-awesome/css/font-awesome.css" />

    <!-- text fonts -->
    <link rel="stylesheet" href="<?=$baseUrl ?>/resources/ace-admin/css/ace-fonts.css" />

    <!-- ace styles -->
    <link rel="stylesheet" href="<?=$baseUrl ?>/resources/ace-admin/css/ace.css" />

    <!--[if lte IE 9]>
      <link rel="stylesheet" href="<?=$baseUrl ?>/resources/ace-admin/css/ace-part2.css" />
    <![endif]-->
    <link rel="stylesheet" href="<?=$baseUrl ?>/resources/ace-admin/css/ace-rtl.css" />

    <!--[if lte IE 9]>
      <link rel="stylesheet" href="<?=$baseUrl ?>/resources/ace-admin/css/ace-ie.css" />
    <![endif]-->

    <!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

    <!--[if lte IE 8]>
    <script src="<?=$baseUrl ?>/resources/components/html5shiv/dist/html5shiv.min.js"></script>
    <script src="<?=$baseUrl ?>/resources/components/respond/dest/respond.min.js"></script>
    <![endif]-->
  </head>

  <body class="login-layout blur-login">
    <div class="navbar ace-save-state navbar-fixed-top align-right" style="background-color:transparent;">
      <br/>
      &nbsp;
      <a id="btn-login-dark" href="#">暗色</a>
      &nbsp;
      <span class="blue">/</span>
      &nbsp;
      <a id="btn-login-blur" href="#">淡色</a>
      &nbsp;
      <span class="blue">/</span>
      &nbsp;
      <a id="btn-login-light" href="#">亮色</a>
      &nbsp; &nbsp; &nbsp;
    </div>
    <div class="main-container">
      <div class="main-content">
        <div class="row">
          <div class="col-sm-10 col-sm-offset-1">
            <div class="login-container">
              <div class="center">
                <h1>
                  <i class="ace-icon fa fa-leaf green"></i>
                  <span class="red"><?=$appName?></span>
                  <span class="white" id="id-text2">管理系统</span>
                </h1>
                <h4 class="blue" id="id-company-text">&copy; <?=$appName?>信息科技有限公司</h4>
              </div>

              <div class="space-6"></div>

              <div class="position-relative">
                <div id="login-box" class="login-box visible widget-box no-border">
                  <div class="widget-body">
                    <div class="widget-main">
                      <h4 class="header blue lighter bigger">
                        <i class="ace-icon fa fa-coffee green"></i>
                        请输入登录信息
                      </h4>

                      <div class="space-6"></div>

                      <form id="login-form" method="post" action="<?php echo Url::to(['api/login'])?>">
                        <input type="hidden" name="<?=$request->csrfParam?>"  value="<?=$request->getCsrfToken()?>" />
                        <fieldset>
                          <label class="block clearfix">
                            <span class="block input-icon input-icon-right">
                              <input type="text" name="username" class="form-control" placeholder="用户名" required data-msg-required="请输入用户名！"/>
                              <i class="ace-icon fa fa-user"></i>
                            </span>
                          </label>

                          <label class="block clearfix ">
                            <span class="block input-icon input-icon-right">
                              <input type="password" name="password" class="form-control" placeholder="密码" required data-msg-required="请输入密码！"/>
                              <i class="ace-icon fa fa-lock"></i>
                            </span>
                          </label>

                          <div class="space"></div>

                          <div class="clearfix">
                            <label class="inline">
                              <input type="checkbox" class="ace" />
                              <span class="lbl"> 记住密码</span>
                            </label>

                            <button type="button" id="btn-login" class="width-35 pull-right btn btn-sm btn-primary">
                              <i class="ace-icon fa fa-key"></i>
                              <span class="bigger-110">登录</span>
                            </button>
                          </div>

                          <div class="space-4"></div>
                        </fieldset>
                      </form>

                      <!-- <div class="social-or-login center">
                        <span class="bigger-110">Or Login Using</span>
                      </div>
                      
                      <div class="space-6"></div>
                      
                      <div class="social-login center">
                        <a class="btn btn-primary">
                          <i class="ace-icon fa fa-facebook"></i>
                        </a>
                      
                        <a class="btn btn-info">
                          <i class="ace-icon fa fa-twitter"></i>
                        </a>
                      
                        <a class="btn btn-danger">
                          <i class="ace-icon fa fa-google-plus"></i>
                        </a>
                      </div> -->
                    </div><!-- /.widget-main -->

                    <div class="toolbar clearfix">
                      <div>
                        &nbsp;
                        <!-- <a href="#" data-target="#forgot-box" class="forgot-password-link">
                          <i class="ace-icon fa fa-arrow-left"></i>
                          忘记密码
                        </a> -->
                      </div>

                      <!-- <div>
                        <a href="#" data-target="#signup-box" class="user-signup-link">
                          注册
                          <i class="ace-icon fa fa-arrow-right"></i>
                        </a>
                      </div> -->
                    </div>
                  </div><!-- /.widget-body -->
                </div><!-- /.login-box -->

                <div id="forgot-box" class="forgot-box widget-box no-border">
                  <div class="widget-body">
                    <div class="widget-main">p
                      <h4 class="header red lighter bigger">
                        <i class="ace-icon fa fa-key"></i>
                        Retrieve Password
                      </h4>

                      <div class="space-6"></div>
                      <p>
                        Enter your email and to receive instructions
                      </p>

                      <form>
                        <fieldset>
                          <label class="block clearfix">
                            <span class="block input-icon input-icon-right">
                              <input type="email" class="form-control" placeholder="Email" />
                              <i class="ace-icon fa fa-envelope"></i>
                            </span>
                          </label>

                          <div class="clearfix">
                            <button type="button" class="width-35 pull-right btn btn-sm btn-danger">
                              <i class="ace-icon fa fa-lightbulb-o"></i>
                              <span class="bigger-110">Send Me!</span>
                            </button>
                          </div>
                        </fieldset>
                      </form>
                    </div><!-- /.widget-main -->

                    <div class="toolbar center">
                      <a href="#" data-target="#login-box" class="back-to-login-link">
                        还回登陆
                        <i class="ace-icon fa fa-arrow-right"></i>
                      </a>
                    </div>
                  </div><!-- /.widget-body -->
                </div><!-- /.forgot-box -->

                <div id="signup-box" class="signup-box widget-box no-border">
                  <div class="widget-body">
                    <div class="widget-main">
                      <h4 class="header green lighter bigger">
                        <i class="ace-icon fa fa-users blue"></i>
                        新用户注册
                      </h4>

                      <div class="space-6"></div>
                      <p> Enter your details to begin: </p>
                      <form>
                        <fieldset>
                          <label class="block clearfix">
                            <span class="block input-icon input-icon-right">
                              <input type="email" class="form-control" placeholder="Email" />
                              <i class="ace-icon fa fa-envelope"></i>
                            </span>
                          </label>

                          <label class="block clearfix">
                            <span class="block input-icon input-icon-right">
                              <input type="text" class="form-control" placeholder="Username" />
                              <i class="ace-icon fa fa-user"></i>
                            </span>
                          </label>

                          <label class="block clearfix">
                            <span class="block input-icon input-icon-right">
                              <input type="password" class="form-control" placeholder="Password" />
                              <i class="ace-icon fa fa-lock"></i>
                            </span>
                          </label>

                          <label class="block clearfix">
                            <span class="block input-icon input-icon-right">
                              <input type="password" class="form-control" placeholder="Repeat password" />
                              <i class="ace-icon fa fa-retweet"></i>
                            </span>
                          </label>

                          <label class="block">
                            <input type="checkbox" class="ace" />
                            <span class="lbl">
                              I accept the
                              <a href="#">User Agreement</a>
                            </span>
                          </label>

                          <div class="space-24"></div>

                          <div class="clearfix">
                            <button type="reset" class="width-30 pull-left btn btn-sm">
                              <i class="ace-icon fa fa-refresh"></i>
                              <span class="bigger-110">Reset</span>
                            </button>

                            <button type="button" class="width-65 pull-right btn btn-sm btn-success">
                              <span class="bigger-110">Register</span>

                              <i class="ace-icon fa fa-arrow-right icon-on-right"></i>
                            </button>
                          </div>
                        </fieldset>
                      </form>
                    </div>

                    <div class="toolbar center">
                      <a href="#" data-target="#login-box" class="back-to-login-link">
                        <i class="ace-icon fa fa-arrow-left"></i>
                        Back to login
                      </a>
                    </div>
                  </div><!-- /.widget-body -->
                </div><!-- /.signup-box -->
              </div><!-- /.position-relative -->              
            </div>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.main-content -->
    </div><!-- /.main-container -->

    <!-- basic scripts -->

    <!--[if !IE]> -->
    <script src="<?=$baseUrl ?>/resources/components/jquery/dist/jquery.js"></script>

    <!-- <![endif]-->

    <!--[if IE]>
    <script src="<?=$baseUrl ?>/resources/components/jquery.1x/dist/jquery.js"></script>
    <![endif]-->
    <script type="text/javascript">
      if('ontouchstart' in document.documentElement) document.write("<script src='<?=$baseUrl ?>/resources/components/_mod/jquery.mobile.custom/jquery.mobile.custom.js'>"+"<"+"/script>");
    </script>

    <script src="<?=$baseUrl ?>/resources/components/bootstrap/dist/js/bootstrap.js"></script>
    <script src="<?=$baseUrl ?>/resources/components/bootstrap-jquery-plugin/js/jquery.bootstrap.min.js"></script>
    <script src="<?=$baseUrl ?>/resources/components/jquery.form-4.2.2/dist/jquery.form.min.js"></script>
    <script src="<?=$baseUrl ?>/resources/components/jquery-validation/dist/jquery.validate.js"></script>
    <script src="<?=$baseUrl ?>/resources/components/jquery-validation/dist/additional-methods.js"></script>
    <script src="<?=$baseUrl ?>/resources/components/jquery-validation/src/localization/messages_zh.js"></script>


    <!-- inline scripts related to this page -->
    <script type="text/javascript">
      jQuery(function($) {
        $(document).on('click', '.toolbar a[data-target]', function(e) {
          e.preventDefault();
          var target = $(this).data('target');
          $('.widget-box.visible').removeClass('visible');//hide others
          $(target).addClass('visible');//show target
        });
      });
      
      
      
      //you don't need this, just used for changing background
      jQuery(function($) {
        $('#btn-login-dark').on('click', function(e) {
          $('body').attr('class', 'login-layout');
          $('#id-text2').attr('class', 'white');
          $('#id-company-text').attr('class', 'blue');

          e.preventDefault();
        });
        $('#btn-login-light').on('click', function(e) {
          $('body').attr('class', 'login-layout light-login');
          $('#id-text2').attr('class', 'grey');
          $('#id-company-text').attr('class', 'blue');

          e.preventDefault();
        });
        $('#btn-login-blur').on('click', function(e) {
          $('body').attr('class', 'login-layout blur-login');
          $('#id-text2').attr('class', 'white');
          $('#id-company-text').attr('class', 'light-blue');

          e.preventDefault();
        });

        $('#login-form').validate({
          onfocusout:false,
          onkeyup:false,
          showErrors:function(errorMap,errorList){
            if(errorList.length > 0){
              $.messager.alert("提示！",errorList[0].message);
              console.log(errorList[0].message);
            }
          },
          submitHandler:function(form){
            console.log("提交!");
            $(form).ajaxSubmit({
              success:function(result) {
                if(result.code == "SUCCESS"){
                  $.messager.popup("登录成功！");
                  setTimeout(function(){
                    window.location.href="<?php echo Url::to(['/'])?>";
                  },100);
                }
                else{
                  $.messager.alert("出错！",result.message); 
                  console.log(result);
                }
              },
              error:function(result) {
                console.log(result);
                $.messager.alert("提示！","请求失败！"); 
              },
              dataType:'json'
            });
            return false; 
          }  
        });
        $("#btn-login").on('click',function(){
          $(this).parents('form').submit();
        });
      });
    </script>
  </body>
</html>
