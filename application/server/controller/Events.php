<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */
//declare(ticks=1);
namespace app\server\controller;

use GatewayWorker\Lib\Gateway;

/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 */
class Events
{

    /**
     * 当客户端的连接上发生错误时触发
     * @param $connection
     * @param $code
     * @param $msg
     */
    public function onError($connection, $code, $msg)
    {
        echo "error $code $msg\n";
    }


    /**
     * 当客户端连接时触发
     * 如果业务不需此回调可以删除onConnect
     * 
     * @param int $client_id 连接id
     */
    public static function onConnect($client_id)
    {
        file_put_contents('./test.log','来自：'.$_SERVER['REMOTE_ADDR'].':'.$_SERVER['REMOTE_PORT'].' 的客户：'.$client_id.' 在 '.date('Y-m-d H:i:s',time()).' 与服务器连接成功!'.PHP_EOL,FILE_APPEND);
        // 向当前client_id发送数据 
        Gateway::sendToClient($client_id, "Hello $client_id\r\n");

        // 向所有人发送
        //Gateway::sendToAll("$client_id login\r\n");
    }
    
   /**
    * 当客户端发来消息时触发
    * @param int $client_id 连接id
    * @param mixed $message 具体消息
    */
   public static function onMessage($client_id, $message)
   {
        file_put_contents('./test.log','客户：'.$client_id.' 在 '.date('Y-m-d H:i:s',time()).'  发来消息：'.$message.PHP_EOL,FILE_APPEND);
        // 向所有人发送 
        //Gateway::sendToAll("$client_id said $message\r\n");

        Gateway::sendToClient($client_id, "I've got your msg : $message --- $client_id\r\n");
   }
   
   /**
    * 当用户断开连接时触发
    * @param int $client_id 连接id
    */
   public static function onClose($client_id)
   {
       file_put_contents('./test.log','客户：'.$client_id.' 断开连接'.PHP_EOL,FILE_APPEND);
       // 向所有人发送 
       GateWay::sendToAll("$client_id logout\r\n");
   }
}
