<?php
    class BaseModel extends DB {

        public function __construct() {
            $this->connect();
        }
    }
