MiniAdmin
====================

MiniAdmin 是基于 MiniFramework 和 Layui 开发的一套后台基础模板。


安装部署
--------------------

[![Latest Stable Version](https://img.shields.io/packagist/v/jasonweicn/miniadmin.svg)](https://packagist.org/packages/jasonweicn/miniadmin)
[![Total Downloads](https://img.shields.io/packagist/dt/jasonweicn/miniadmin.svg)](https://packagist.org/packages/jasonweicn/miniadmin)

通过 Composer 可以快速安装部署一个基于 MiniAdmin 的项目，步骤如下：

### 1.安装 Composer

> 如果已经安装好了 Composer 可跳过本节内容。

在 Linux 系统中，全局安装 Composer 的命令如下：

```
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```

> Windows 和 MacOS 系统的开发者可前往 Composer 的官网 [https://getcomposer.org/](https://getcomposer.org/) 下载对应的安装包进行安装。

### 2.通过 Composer 安装 MiniAdmin

在命令行执行如下命令：

```
composer create-project --prefer-dist --stability=dev jasonweicn/miniadmin myapp
```

> 上述命令结尾的 myapp 为要创建的项目目录，可根据实际情况修改。

### 3.配置

#### 3.1.配置入口文件

找到 myapp/backend/Public/index.php 文件，这是项目后台部分的入口文件，可在其中定义所需的配置常量，例如：

```
<?php
// 命名空间
const APP_NAMESPACE = 'backend';

// 是否显示错误信息
const SHOW_ERROR = true;

// 是否开启日志（生产环境建议关闭）
const LOG_ON = false;

// 是否启用布局功能
const LAYOUT_ON = true;

// 开启数据库自动连接
const DB_AUTO_CONNECT = true;

const APP_NAME = 'MiniAdmin';

const PAGE_SIZE = 10;

// 引入 MiniFramework
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/jasonweicn/miniframework/Bootstrap.php';
```

> 上述代码已经包含在文件中了，最后两行是引入 MiniFramework 框架，通常不需要进行修改即可使用。

#### 3.2.配置数据库

找到 myapp/backend/Config/database.php 文件，这是数据库配置文件，可在其中定义所需的数据库连接信息，例如：

```
<?php
$database['default'] = [
    'host'          => 'localhost', //主机地址
    'port'          => 3306,        //端口
    'dbname'        => 'miniadmin', //库名
    'username'      => 'root',      //用户名
    'passwd'        => '',          //密码
    'charset'       => 'utf8mb4',   //字符编码
    'persistent'    => false        //是否启用持久连接 （ true | false ）
];
?>
```

> 上述数据连接信息可根据实际情况进行修改。

#### 3.3.导入数据

请将 miniadmin.sql 导入你的数据库中。

> 默认的登录用户名为：admin，密码为：123456

#### 3.4.配置站点

请将 myapp/backend/Public 目录配置到 Apache 或 Nginx 作为站点的根目录。

### 4.运行

完成所有配置后，可尝试通过浏览器访问，例如：

http://你的域名/index.php

如能正常显示 MiniAdmin 的登录界面，那么恭喜你，一个基于 MiniAdmin 的项目已经运行起来了。


参与开发
--------------------

参与开发的流程：

* 首先，开发者应具有一个 GitHub 账号，在 GitHub 登录账号；
* 进入 MiniAdmin 项目页面 [https://github.com/jasonweicn/miniadmin](https://github.com/jasonweicn/miniadmin)；
* 将 MiniAdmin 项目源码 Fork 到开发者自己的账号下，然后 Clone 到本地计算机硬盘中；
* 完成代码编写并 Commit 到开发者账号下的 MiniAdmin 副本中；
* 开发者通过 Pull request 提交代码（提交时请详细填写改动细节），等待审核通过。


关于作者
--------------------

作者：Jason Wei

信箱：jasonwei06@hotmail.com

博客：[http://www.sunbloger.com](http://www.sunbloger.com)

微博：[https://weibo.com/jasonweicn](https://weibo.com/jasonweicn)


开源协议
--------------------

MiniAdmin 遵循 Apache License Version 2.0 开源协议发布。

协议详细内容请浏览项目目录中的 LICENSE 文件。


相关链接
--------------------

MiniFramework：[https://github.com/jasonweicn/miniframework](https://github.com/jasonweicn/miniframework)

Layui：[https://www.layui.com/](https://www.layui.com/)
