<?php
/**
 * run with command
 * php start.php start
 */

// [ 应用入口文件 ]
namespace think;

ini_set('display_errors', 'on');
if(strpos(strtolower(PHP_OS), 'win') === 0)
{
    exit("start.php not support windows.\n");
}
// 检查扩展
if(!extension_loaded('pcntl'))
{
    exit("Please install pcntl extension. See http://doc3.workerman.net/appendices/install-extension.html\n");
}
if(!extension_loaded('posix'))
{
    exit("Please install posix extension. See http://doc3.workerman.net/appendices/install-extension.html\n");
}

define('APP_PATH', __DIR__ . '/../application/');

// 标记是全局启动
define('GLOBAL_START', 1);

// 加载基础文件
require __DIR__ . '/../thinkphp/base.php';


// 绑定当前访问到index模块的index控制器
//Container::get('app')->bind('index/index')->run()->send();

// 执行应用并响应
Container::get('app')->bind('server/Run')->run()->send();
