<?php
    class Connection {
        private $host = 'localhost';
        private $db_name = 'students';
        private $username = 'default';
        private $password = 'default';
        private $connection_string;

        public function connect() {
            $this -> connection_string = null;
            try {
                $this -> connection_string = new mysqli($this -> host,$this -> username,$this -> password,$this -> db_name);
            }
            catch (Throwable $err) {
                echo 'Connection Error: ' . $e -> getMessagea();
            }
            return $this -> connection_string;
        }
    }
?>