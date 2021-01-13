<?php
include 'DBconn.php';

class client extends DBconn{

    public function getClients($filtro){
        $sql = 'SELECT * FROM clients'.$filtro;
        $result = $this->connect()->query($sql);
        $this->disconnect();
        return $result;
    }

    public function insert($val1, $val2, $val3 ,$val4){
        $sql = 'INSERT INTO clients VALUES ('.$val1.',"'.$val2.'","'.$val3.'",'.$val4.')';
        $result = $this->connect()->query($sql);
        $this->disconnect();
        return $result;
    }
}

?>