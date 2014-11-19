<?php
    class ehomeProject extends Project{
        public $mainConn = null;

        public function run($curConn){
            if($this->mainConnCheck($curConn) == false){
                throw new Exception("主连接尚未建立", 1);
                return;
            }
            
            $this->setFrameAppId($curConn);
            if($this->fromMainConn($curConn) == true){
                $this->mainConn($curConn);
            }else{
                $this->clientConn($curConn);
            }
            $curConn->frameList = array();
        }

        public function close($clientId){
            $conn = $this->connList[$clientId];
            $conn->retAppId();
            if(!is_null($this->mainConn) && $conn->clientId==$this->mainConn->clientId){
                $this->mainConn = null;
                echo "destroy main connect client[{$conn->clientId}]\n";
            }
            unset($this->connList[$clientId]);
            return;
        }

        private function mainConn($curConn){
            $frameList = $curConn->frameList;
            foreach($frameList as $frame){
                if($frame->appId > 0){
                    // 过滤事件
                    if($frame->event=='login' && $frame->dataContent=='01'){
                        $this->getConnByAppId($frame->appId)->valid = true;
                        echo "client[{$this->getConnByAppId($frame->appId)->appId}] pass ok\n";
                    }
                    $this->send($frame);
                }else{
                    // TODO: mainConn与server通信
                }
            }
            return;
        }

        private function clientConn($curConn){
            foreach($curConn->frameList as $frame){
                if($curConn->valid==true || in_array($frame->event, array('register', 'login'))){
                    $this->send($frame, true);
                    echo "send to main [{$frame->proto}]\n";
                }else{
                    throw new Exception("连接尚未通过验证", 1);
                }
            }
            return;
        }

        private function send($frame, $toMain=false){
            if($toMain == false){
                $conn = $this->getConnByAppId($frame->appId);
                if(is_null($conn)){
                    throw new Exception("帧错误：未找到对应appid: {$frame->appId}", 1);
                    return false;
                }
            }else{
                if(!is_null($this->mainConn)){
                    $conn = $this->mainConn;
                }else{
                    throw new Exception("主连接尚未建立", 1);
                    return false;
                }
            }
            return $this->server->send($conn->clientId, $frame->proto); // boolean
        }

        private function getConnByAppId($appId){
            foreach($this->connList as $connItem){
                if($connItem->appId == $appId){
                    return $connItem;
                }
            }
            return null;
        }

        /* main连接控制部分 */
        private function fromMainConn($conn){
            if(!is_null($this->mainConn) && $this->mainConn->clientId == $conn->clientId){
                return true;
            }else{
                return false;
            }
        }
        private function mainConnCheck($curConn){
            if(!is_null($this->mainConn)){
                return true;
            }

            $frameList = $curConn->frameList;
            // 检查是否有涉及建立主连接的帧
            foreach($frameList as $frame){
                if($frame->event == 'main_connect'){
                    $this->mainConn = $curConn;
                    $this->mainConn->valid = true;
                    echo "build main connect client[{$curConn->clientId}]\n";
                    $this->server->send($curConn->clientId, "build main ok\n");
                    return true;
                }
            }
            return false;
        }
        private function setFrameAppId($curConn){
            if($curConn->clientId != $this->mainConn->clientId){
                foreach($curConn->frameList as &$frame){
                    $frame->setAppId($curConn->appId);
                }
            }
        }
    }