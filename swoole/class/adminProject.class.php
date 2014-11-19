<?php
    class adminProject extends Project{
        public function run($curConn){
            global $server;
            global $mainProtocol;

            echo "----------------- SERVER STATE -----------------\n";

            $curPorject = $mainProtocol->projectList;
            foreach($curPorject as $project){
                echo "alive project [{$project->pName}]\n";
                foreach($project->connList as $conn){
                    echo "\t has conn[{$conn->clientId}]\n";
                    foreach($conn->frameList as $frame){
                        echo "\t\t has frame[{$frame->str}]\n";
                    }
                }
            }

            echo "--------------------- END ----------------------\n";

            if($server_reload == true){
                $server->reload();
            }
            if($server_shutdown == true){
                $server->shutdown();
            }
        }
    }