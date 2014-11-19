<?php
    require '../class/Frame.class.php';
    require '../class/ehomeFrame.class.php';

    $client = stream_socket_client('tcp://10.0.2.15:9501', $errno, $errstr, 30);

    fwrite($client, "550c082011ffc40000000000000495020001aa");
    $frame = Frame::getInstance("ehome");

    while(1){
        $data = fread($client, 1024);
        if(empty($data)){
            continue;
        }

        $frameList = $frame->parse($data);
        foreach($frameList as $item){
            echo "<--: {$item->proto}\n";
            if($item->event == 'login'){
                $appId = $item->appId;
                fwrite($client, "550c082011ffc4000000000000049101{$appId}01aa");
                echo "-->: 550c082011ffc4000000000000049101{$appId}01aa\n";
                
                fwrite($client, "550c082011ffc4000000000000049202{$appId}01aa");
                fwrite($client, "550c082011ffc4000000000000049202{$appId}01aa");
                fwrite($client, "550c082011ffc4000000000000049202{$appId}01aa");
                echo "-->: 550c082011ffc4000000000000049202{$appId}01aa\n";
                echo "-->: 550c082011ffc4000000000000049202{$appId}01aa\n";
                echo "-->: 550c082011ffc4000000000000049202{$appId}01aa\n";
            }
        }
    }