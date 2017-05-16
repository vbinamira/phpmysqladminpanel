<?php
// OOP way of connection set up, better way
define('URL','http://10.107.1.23:85/');

class database {
  
    private $DBhost;
    private $DBuser;
    private $DBpass;
    private $DBname;
    private $DBport;
    private $DBcon;

    public function dbconnect() {
      $this->DBhost = "10.107.1.23:85"; // ENTER YOUR HOST NAME
      $this->DBuser = "root"; // ENTER YOUR DATABASE USERNAME
      $this->DBpass = "root"; // ENTER YOUR DATABASE PASSWORD
      $this->DBname = "generic_db"; // ENTER YOUR DATABASE NAME
      $this->DBport = "3306"; // ENTER YOUR DATABASE PORT (OPTIONAL)
      $this->DBcon = new mysqli($this->DBhost,$this->DBuser,$this->DBpass,$this->DBname,$this->DBport);
      //Error if something goes wrong
      if ($this->DBcon->connect_errno) {
        die("ERROR : -> " . $this->DBcon->connect_error);
    }

      return $this->DBcon;
    }
  
  public function query($query)
      {
          return $this->DBcon->query($query);
      }
}