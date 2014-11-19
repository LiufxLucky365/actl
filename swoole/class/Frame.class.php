<?php
    class Frame{
        public $proto = '';
        public $str = '';
        public $head = '';
        public $tail = '';

        public function __construct($data){
            $this->proto = $data;
            $this->str = $data;
        }

        public static function getInstance($pName){
            $className = $pName."Frame";
            $className = class_exists($className)? $className: 'Frame';
            $frame = new $className;
            return $frame;
        }

        /* 子类实现 */
        public function parse($data){
            $frameList[] = new Frame($data);
            return $frameList;
        }
    }