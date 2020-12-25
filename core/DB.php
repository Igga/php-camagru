<?php
    class DB {
        protected $connection;

        protected function connect() {
            try {
                $this->connection = new PDO(dsn, username, password);
            } catch (PDOException $e) {
                echo "Error: {$e->getMessage()}";
            }

            return $this->connection;
        }
    }
