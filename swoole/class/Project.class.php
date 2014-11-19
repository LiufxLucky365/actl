<?php
    class Project{
        public $server = null;
        public $pName = '';
        public $frameParser = '';
        public $db = null;
        public $connList = array();

        public function __construct($server, $pName){
            $this->server = $server;
            $this->pName = $pName;
            $this->frameParser = Frame::getInstance($pName);
            $this->db = new DB();
        }

        public function handle($clientId, $data){
            $conn = $this->getConn($clientId);
            $frameList = $this->frameParser->parse($data);
            $conn->addFrame($frameList);
            $this->run($conn);
        }

        public function close($clientId){
            unset($this->connList[$clientId]);
            return;
        }

        /* Help */
        public static function getInstance($server, $pName){
            $className = $pName."Project";
            $className = class_exists($className)? $className: 'Project';
            $project = new $className($server, $pName);
            return $project;
        }

        public function getConn($connId){
            if(!array_key_exists($connId, $this->connList)){
                $this->connList[$connId] = Connect::getConn($this->pName, $connId);
            }
            return $this->connList[$connId];
        }

        private function dumpConn(){
            echo "Project[{$this->pName}]:\n";
            foreach($this->connList as $connId => $conn){
                echo "\tconn[{$connId}]:\n";
                foreach($conn->frameBuff as $frame){
                    echo "\t\t$frame\n";
                }
            }
            echo "\n";
        }

        /* db相关 */
        function __call($method, $args=array()){
            if(method_exists($this->db, $method)){
                return call_user_func_array(array($this->db, $method), $args);
            }else{
                throw new Exception("Project has no method: $method", 1);
            }
        }
        // public function query($sql){
        //     return $this->db->query($sql);
        // }
        // public function exec($sql){
        //     return $this->db->exec($sql);
        // }
        // public function trans(){
        //     return $this->db->beginTransaction();
        // }
        // public function commit(){
        //     return $this->db->commit();
        // }
        // public function rollback(){
        //     return $this->db->rollBack();
        // }

        /* 子类实现 */
        public function run($curConn){
            $this->dumpConn();
        }

    }