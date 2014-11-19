<?php
    class DB extends Swoole\Database{
        public $dbConfig = array(
                'host'     => '127.0.0.1',
                'user'     => 'root',
                'password' => '',
                'dbname'   => 'actl',
                'type'     => '',
        );

        public function __construct(){
            parent::__construct($this->dbConfig);
        }
    }