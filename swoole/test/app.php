<?php
    require '../class/Frame.class.php';
    require '../class/ehomeFrame.class.php';

    $client = stream_socket_client('tcp://10.0.2.15:9501', $errno, $errstr, 30);

    fwrite($client, "550c082011ffc40000000000000b1175736572267077640001aa");  // login
    echo "-->: 550c082011ffc40000000000000b1175736572267077640001aa\n";
    while(1){
        $data = fread($client, 1024);
        if(empty($data)){
            continue;
        }
        echo "<--: $data\n";
    }