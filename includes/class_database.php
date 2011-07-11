<?php

    class database {
        private $connect;
    
        public function connect($host, $user, $pass, $db)
        {
            $this->connect = mysql_connect($host, $user, $pass);
            
            if($this->connect){
                $selectDB = mysql_select_db($db);
                
                if(!$selectDB){
                    $error = new error('Error: Couldn\'t select the MYSQL database');
                }
            } else {
                $error = new error('Error:  Couldn\'t connect to MYSQL database');
            }
        }
        
        public function disconnect()
        {
            mysql_close($this->connect);
            unset($connect);
        }
    }

?>