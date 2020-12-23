<?php
    class BaseModel {

        protected $connection;

        public function __construct() {
            $db = new DB();

            $this->connection = $db->connect();
        }
    }