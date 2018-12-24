<?php
namespace app\index\controller;

use Workerman\Worker;
use PHPSocketIO\SocketIO;
use Channel\Client;
use GlobalData\Client as GlobalClient;
use Workerman\Connection\AsyncTcpConnection;
/**
 * HTTP服务.
 */
class Http
{
    public function __construct()
    {   
        $http_worker = new Worker('http://0.0.0.0:4237');
        $http_worker->name = 'publisher';

        /**
         * 收到客户新消息
         */
        $http_worker->onMessage = function ($connection, $data) {
            \Workerman\Protocols\Http::header('Access-Control-Allow-Origin: *');
            $data = $data['get'];
            call_user_func([$this,'helloWorld'], $data);
            $connection->send('ok');
            // $data = $data['get'];
            // if (isset($data['task'])) {
            //     //call_user_func([$this, $data['task']], $data);
            //     //$connection->send('收到你的定时任务了');
            //     return;
            // }else{
            //     exit();
            // }
            //$connection->send('访问出错');
        };

        if (!defined('GLOBAL_START')) {
            Worker::runAll();
        }
    }

    protected function helloWorld($data)
    {
        Client::connect('127.0.0.1', 2206);
        Client::publish('mkMsg',$data);
    }
}
