<?php
    class ehomeConnect extends Connect{
        public $appId = '';
        public $valid = false;

        public function __construct($clientId){
            parent::__construct($clientId);
            $this->appId = $this->getAppId();
            echo "give appId[{$this->appId}]\n";
        }

        public static function getInstance($clientId){
            $conn = new ehomeConnect($clientId);
            if($conn->appId === false){
                $conn = null;
                throw new Exception("app id 已满", 1);
            }
            return $conn;
        }

        /* app管理 */
        public static $appNum = 255;
        public static $appIdList = null;
        public function getAppId(){
            if(is_null(self::$appIdList)){
                for($i=1; $i<self::$appNum; $i++){
                    self::$appIdList[] = str_pad(base_convert($i, 10, 16), 2, "0", STR_PAD_LEFT);;
                }
            }
            $appId = array_shift(self::$appIdList);
            $ret = is_null($appId)? false: $appId;
            return $ret;
        }
        public function retAppId(){
            self::$appIdList[] = $this->appId;
            return;
        }
    }