<?php
    class ehomeFrame{

        public function __construct(){
            $this->head = '55';
            $this->tail = 'aa';

            $this->event       = '';
            $this->systemId    = '';
            $this->roomId      = '';
            $this->deviceType  = '';
            $this->deviceId    = '';
            $this->dataLength  = '';
            $this->dataType    = '';
            $this->dataContent = '';
            $this->checkCrc32  = '';
            $this->appId       = '';
        }

        public function setAppId($val){
            $this->app_id      = $val;
            // 更新frame的str和proto
            $this->str   = substr_replace($this->str, str_pad(base_convert($val, 10, 16), 2, '0', STR_PAD_LEFT), -6, 2);
            // $this->proto = pack("H*", $this->str);
            $this->proto = $this->str;
            return true;
        }

        private function parseFrame($frame){
            // 检查是否以'aa'结尾
            if(substr($frame, -2, 2) == 'aa'){
                // 对ehome各属性赋值
                $this->str         = $frame;
                $this->proto       = $frame;
                // 处理帧信息
                $this->systemId    = substr($frame, 2, 12);
                $this->roomId      = substr($frame, 14, 4);
                $this->deviceType  = substr($frame, 18, 4);
                $this->deviceId    = substr($frame, 22, 2);
                $this->dataLength  = substr($frame, 24, 4);
                $this->dataType    = substr($frame, 28, 2);
                $this->dataContent = substr($frame, 30, 2 * ($this->dataLength - 3));
                // 倒切
                $this->checkCrc32  = substr($frame, -4, 2);
                $this->appId       = substr($frame, -6, 2);

                // 处理帧触发的事件
                switch($this->dataType){
                    case '95':
                        echo "receive event: main_connect\n";
                        $this->event = 'main_connect';
                        break;
                    case '90':
                        $this->event = 'register';
                        break;
                    case '11':
                    case '91':
                        echo "receive event: login\n";
                        $this->event = 'login';
                        break;
                    default:
                        echo "receive event: unknown\n";
                        break;
                }
                return true;
            }else{
                return false;
            }
        }

        public function parse($data){
            $frameList = array();

            while(1){
                $frameStart = strpos($data, $this->head);
                if($frameStart !== false){
                    // 计算数据部分的长度
                    $dataLengthStart = $frameStart + 2*(1+6+2+2+1);
                    $dataLength = intval(substr($data, $dataLengthStart, 4), 16);
                    // 截取
                    $frameLength = 2*(1+6+2+2+1+1+1+1+$dataLength);
                    $frame = substr($data, $frameStart, $frameLength);

                    $ehomeFrame = new ehomeFrame();
                    $retParse = $ehomeFrame->parseFrame($frame);
                    if($retParse !== false){
                        $frameList[] = $ehomeFrame;
                        $data = substr($data, $frameStart+$frameLength);
                    }else{
                        $data = substr($data, $frameStart+2);
                    }
                }else{
                    break;
                }
            }
            return $frameList;
        }
    }

    /* test */
    // $data = "5555123456789abc123412341200051212340012aaxxx550c082011ffc40001000000000992e58da7e5aea40201aaxxxxxxx55123456789abc123412341200051212340012aabb";

    // $frame = new ehomeFrame();
    // $frameList = $frame->parse($data);
    // print_r($frameList);


    // $frameStart = strpos($data, '55');
    // if($frameStart !== false){
    //     // 计算数据部分的长度
    //     $dataLengthStart = $frameStart + 2*(1+6+2+2+1);
    //     $dataLength = intval(substr($data, $dataLengthStart, 4), 16);
    //     // 截取
    //     $frameLength = 2*(1+6+2+2+1+1+1+1+$dataLength);
    //     $frame = substr($data, $frameStart, $frameLength);

    //     $tempFrame = new ehomeFrame($frame);
    //     if($tempFrame->valid !== false){
    //         echo $frame."\n";
    //         echo "------------------------";
    //         $frameList[] = $tempFrame;
    //         $data = substr($data, $frameStart+$frameLength);
    //     }else{
    //         echo "+++++++++++++";
    //         $data = substr($data, $frameStart+2);
    //     }
    // }else{
    //     break;
    // }
    // echo $data."\n";