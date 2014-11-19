<?php
    $client = stream_socket_client('tcp://10.0.2.15:9500', $errno, $errstr, 30);

    fwrite($client, "550c082011ffc40000000000000b1175736572267077640001aa");  // login
    echo "-->: 550c082011ffc40000000000000b1175736572267077640001aa\n";
