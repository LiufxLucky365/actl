<?php
    $server = new \Swoole\Network\Server("10.0.2.15", 9500);
    $server->addlistener("10.0.2.15", 9501, SWOOLE_SOCK_TCP);
    $server->addlistener("10.0.2.15", 9502, SWOOLE_SOCK_TCP);
    // foreach($PROJECT_LIST as $project){
    //     
    // }

    $mainProtocol = new MainProtocol();
    $server->setProtocol($mainProtocol);
    // $server->daemonize(); //作为守护进程
    $server->run(array('worker_num' => 1, 'max_request' => 5000, 'max_conn'=>100, 'task_worker_num'=>2));
