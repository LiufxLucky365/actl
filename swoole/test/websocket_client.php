<?php
    define('DEBUG', 'on');
    define("WEBPATH", str_replace("\\","/", __DIR__));
    require __DIR__ . '/../libs/lib_config.php';

    $client = new Swoole\Client\WebSocket('10.0.2.15', 9505, '/');
    if(!$client->connect())
    {
        echo "connect to server failed.\n";
        exit;
    }
    while(true)
    {
        $client->send("hello world");
        $message = $client->recv();
        if($message === false) break;
        echo "Received from server: {$message}\n";
        sleep(1);
    }
    echo "Closed by server.\n";