<?php
    class Connect{
        public $frameList = array();
        public $clientId = null;

        public function __construct($clientId){
            $this->clientId = $clientId;
        }

        public static function getConn($pName, $clientId){
            $className = $pName."Connect";
            if(class_exists($className)){
                if(method_exists($className, 'getInstance')){
                    $conn = $className::getInstance($clientId);
                }else{
                    $conn = new $className($clientId);
                }
            }else{
                $conn = new Connect($clientId);
            }
            return $conn;
        }

        public function addFrame($frameList){
            $this->frameList = array_merge($this->frameList, $frameList);
            return true;
        }
    }