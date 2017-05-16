<?php
//Old way, less scalable
  
  $DBhost = "10.107.1.23";
  $DBuser = "root";
  $DBpass = "root";
  $DBname = "generic_db";
  $DBport = "3306";
  define('URL','http://10.107.1.23:85/');
  $DBcon = new MySQLi($DBhost,$DBuser,$DBpass,$DBname,$DBport);
    //Error if something goes wrong
     if ($DBcon->connect_errno) {
         die("ERROR : -> " . $DBcon->connect_error);
     }
