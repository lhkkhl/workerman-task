<?php
namespace app\index\controller;

use think\worker\Server;
use Workerman\Lib\Timer;
use Channel\Client;

class Socket extends Server
{
    protected $socket = 'webSocket://0.0.0.0:12234';
    protected $clients=array();
    protected $processes = 100;
    protected $name = 'TaskWorker';

    /**
     * worker启动.
     */
    public function onWorkerStart($worker)
    {
        $this->worker = $worker;
        $worker->name = 'TaskWorker';

    }

    public function onConnect($connection)
    {
        // global $worker;
        Client::connect('127.0.0.1', 2206);

        Client::on('mkMsg',function($data){
            var_dump($data);
            //$this->sendMessageByUid(1,"111111====1111");
        });
         var_dump($connection->id);
         $this->clients[$connection->id] = $connection;
    }

    public function sendMessageByUid($uid, $message) {
        if(isset($this->clients[$uid]))$this->clients[$uid]->send($message);
	}

    public function onMessage($connection, $data)
    {
        try{
            echo '定时任务收到你的消息了';
            echo 'data:' . $data . "\n";
            //$this->sendMessageByUid(1,"111111====1111");
            //$data = \json_decode($data, true);
            //call_user_func([$this, $data['task']], [$connection, $data]);}catch($e){

        }catch(Exception $e){
            var_dump($e);
        }
        
    }

    public function onClose($connection)
    {
        
        unset($this->clients[$connection->id]);
    }

    // protected function sayHelloWorld($data)
    // {
    //     list($connection, $params) = $data;
    //     echo "正在处理task: sayHelloWorld\n";
    //     $event_name = 'sayHello';
    //     // 广播事件
    //     Client::publish($event_name, $params);
    //     $connection->send('完成');
    // }
}