<?php
    class MainProtocol extends Swoole\Protocol\Base{
        private $portMap = array(
            '9500' => 'admin',
            '9501' => 'ehome',
            '9502' => 'eschool',
        );
        public $projectList = array();

        /* Main */
        public function onStart($server){
        }
        public function onConnect($server, $client_id, $from_id){
        }
        public function onClose($server, $client_id, $from_id){
            $connInfo = $server->connection_info($client_id);
            echo "onClose: clinet_id[$client_id] from_port[{$connInfo['from_port']}]\n";
            $this->getProject($server, $connInfo['from_port'])->close($client_id);
        }
        public function onShutdown($server){
        }
        /* Task */
        public function onTask($server, $task_id, $from_id, $data){
        }
        public function onFinish($server, $task_id, $data){
        }

        /* 核心 */
        public function onReceive($server, $client_id, $from_id, $data){
            try{
                $connInfo = $server->connection_info($client_id);
                echo "onReceive: clinet_id[$client_id] from_port[{$connInfo['from_port']}] data[$data]\n";
                $this->getProject($server, $connInfo['from_port'])->handle($client_id, $data);
            }catch(Exception $e){
                echo "{$e->getMessage()}\n";
            }
        }

        /* Help */
        private function getProject($server, $port){
            if(!array_key_exists($port, $this->projectList)){
                $this->projectList[$port] = Project::getInstance($server, $this->portMap[$port]);
            }
            return $this->projectList[$port];
        }
    }