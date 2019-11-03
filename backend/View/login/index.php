<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="format-detection" content="telephone=no">
<title>登录 - <?php echo APP_NAME;?></title>
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseUrl();?>/assets/layui/css/layui.css" media="all">
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseUrl();?>/css/login.css">
<style type="text/css">
.mini-icon-username,.mini-icon-password,.mini-icon-vercode{position:absolute; width:38px; line-height:36px; text-align:center; color:#d2d2d2}
</style>
</head>

<body>
  <div class="layui-container" style="width:320px;padding-top:10%">
    <div class="layui-elip" style="text-align: center;padding:5px;color:#666"><h1><?php echo APP_NAME;?></h1></div>
    <form id="login_form" class="layui-form" action="login/check" method="post">
      <div class="layui-form-item">
        <label class="layui-icon layui-icon-username mini-icon-username" for="login_username"></label>
        <input type="text" id="login_username" class="layui-input admin-login-input" name="username" lay-verify="required" placeholder="用户名" aria-required="true" style="padding-left:38px">
      </div>
      <div class="layui-form-item field-login-password required">
        <label class="layui-icon layui-icon-password mini-icon-password" for="login_password"></label>
        <input type="password" id="login_password" class="layui-input" name="password" lay-verify="required" placeholder="密码" aria-required="true" style="padding-left:38px"><span class='glyphicon glyphicon-lock form-control-feedback'></span>
        <p class="help-block help-block-error"></p>
      </div>
      <div class="layui-form-item">
        <label class="layui-icon layui-icon-vercode mini-icon-vercode" for="login_verifycode"></label>
        <input type="text" id="login_verifycode" class="layui-input" name="verifycode" lay-verify="required" placeholder="验证码" aria-required="true" autocomplete="off" style="padding-left:38px">
      </div>
      <div class="layui-form-item" style="text-align:center;"><img id="verifycode_img" src="/index/verifycode?t=<?php echo time();?>" style="cursor:pointer;" /></div>
      <div class="layui-form-item">
        <button class="layui-btn" lay-submit="" style="width: 100%">登录</button>
      </div>
    </form>
  </div>
<script src="<?php echo $this->baseUrl();?>/assets/jquery.js"></script>
<script src="<?php echo $this->baseUrl();?>/assets/layui/layui.js"></script>
<script>
layui.use('form', function() {
    var form = layui.form;
    var layer = layui.layer;

    function openAlert(msg) {
        layer.alert(msg,{icon:0,anim:1,offset:"50px"});
    }
    
    var errcode = <?php echo $this->errcode;?>;
    if (errcode == 1) {
        openAlert("用户名或密码错误");
    } else if (errcode == 2) {
        openAlert("用户名包含有不被允许的字符，请检查后重新输入");
    } else if (errcode == 3) {
        openAlert("验证码错误");
    }
});

$("#verifycode_img").click(function () {
    $(this).attr("src", '/index/verifycode?t=' + Math.random());
});
</script>
</body>
</html>
