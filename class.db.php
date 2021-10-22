<?php
class DB {

    public function __construct() {
        $this->_connect();
    }

    private function _connect() {
        // Create connection
        $this->conn = new mysqli('remotemysql.com', 'TxYUjIJn3p', '8zmYf05oYw', 'TxYUjIJn3p');

        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        return $this->conn;
    }

    public function _select($sql) {
        $result = $this->conn->query($sql);
        
        $_Aresult = array();
        if ($result->num_rows > 0) {
          // output data of each row
          while($row = $result->fetch_assoc())
            $_Aresult[] = $row;
        }
        return $_Aresult;
    }

    public function _query($sql) {
        $result = $this->conn->query($sql);
        if ($result === TRUE) {
            return true;
        } else {
            die("Error: " . $sql . "<br>" . $this->conn->error);
        }
        return false;
    }

    public function __destruct() {
        return $this->conn->close();
    }
}