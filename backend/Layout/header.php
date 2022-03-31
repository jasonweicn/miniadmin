<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="format-detection" content="telephone=no">
  <title>{$title}</title>
  <link rel="stylesheet" type="text/css" href="<?php echo $this->baseUrl();?>/assets/layui/css/layui.css" media="all">
  <link rel="stylesheet" type="text/css" href="<?php echo $this->baseUrl();?>/css/backend.css" media="all">
</head>

<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">

  <!-- header -->
  <div class="layui-header">
    <div class="layui-logo">MiniAdmin</div>
    <ul class="layui-nav layui-layout-left">
    </ul>
    <ul class="layui-nav layui-layout-right">
      <li class="layui-nav-item">
        <a href="javascript:;">
          <?php echo $this->admin_nickname;?>
        </a>
        <dl class="layui-nav-child">
          <dd><a href="<?php echo $this->baseUrl();?>/adminuser/profile">我的账号</a></dd>
          <dd><a href="javascript:;" data-link="<?php echo $this->baseUrl();?>/logout" id="logout">退出登录</a></dd>
        </dl>
      </li>
    </ul>
  </div>
