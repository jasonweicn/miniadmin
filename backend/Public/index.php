<?php
// +---------------------------------------------------------------------------
// | MiniAdmin
// +---------------------------------------------------------------------------
// | Copyright (c) 2019-2020 http://www.sunbloger.com
// +---------------------------------------------------------------------------
// | Licensed under the Apache License, Version 2.0 (the "License");
// | you may not use this file except in compliance with the License.
// | You may obtain a copy of the License at
// |
// | http://www.apache.org/licenses/LICENSE-2.0
// |
// | Unless required by applicable law or agreed to in writing, software
// | distributed under the License is distributed on an "AS IS" BASIS,
// | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// | See the License for the specific language governing permissions and
// | limitations under the License.
// +---------------------------------------------------------------------------
// | Source: https://github.com/jasonweicn/miniadmin
// +---------------------------------------------------------------------------
// | Author: Jason Wei <jasonwei06@hotmail.com>
// +---------------------------------------------------------------------------
// | Website: http://www.sunbloger.com/
// +---------------------------------------------------------------------------

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
