<?php
/**
 * Created by PhpStorm.
 * User: sleet
 * Date: 2018/9/5
 * Time: 13:47
 */
namespace app\server\controller;

use Workerman\Worker;
use GatewayWorker\Register;
use GatewayWorker\BusinessWorker;
use GatewayWorker\Gateway;

class Run
{


//控制器无需继承Controller
    /**
     * 构造函数
     * @access public
     */
    public function __construct()
    {
        //****************初始化各个GatewayWorker************//

        //////////////////初始化register//////////////////////
        $register = new Register('text://0.0.0.0:12398');

        //////////////////初始化 bussinessWorker 进程//////////

        // bussinessWorker 进程
        $worker = new BusinessWorker();
        // worker名称
        $worker->name = 'DakaWorker';
        // bussinessWorker进程数量
        $worker->count = 1;
        // 服务注册地址
        $worker->registerAddress = '127.0.0.1:12398';
        //设置处理业务的类,此处制定Events的命名空间
        $worker->eventHandler = '\app\server\controller\Events';

        ///////////////// 初始化 gateway 进程//////////////////

        // gateway 进程，这里使用Text协议，可以用telnet测试
        $gateway = new Gateway("tcp://0.0.0.0:38238");
        // gateway名称，status方便查看
        $gateway->name = 'DakaGateway';
        // gateway进程数
        $gateway->count = 2;
        // 本机ip，分布式部署时使用内网ip
        $gateway->lanIp = '127.0.0.1';
        // 内部通讯起始端口，假如$gateway->count=4，起始端口为4000
        // 则一般会使用4000 4001 4002 4003 4个端口作为内部通讯端口
        $gateway->startPort = 29000;
        // 服务注册地址
        $gateway->registerAddress = '127.0.0.1:12398';


        // 心跳间隔
        //$gateway->pingInterval = 10;
        // 心跳数据
        //$gateway->pingData = '{"type":"ping"}';

        /*
        // 当客户端连接上来时，设置连接的onWebSocketConnect，即在websocket握手时的回调
        $gateway->onConnect = function($connection)
        {
            $connection->onWebSocketConnect = function($connection , $http_header)
            {
                // 可以在这里判断连接来源是否合法，不合法就关掉连接
                // $_SERVER['HTTP_ORIGIN']标识来自哪个站点的页面发起的websocket链接
                if($_SERVER['HTTP_ORIGIN'] != 'http://kedou.workerman.net')
                {
                    $connection->close();
                }
                // onWebSocketConnect 里面$_GET $_SERVER是可用的
                // var_dump($_GET, $_SERVER);
            };
        };
        */

        ///////////运行所有Worker;//////////////
        Worker::runAll();
    }


}