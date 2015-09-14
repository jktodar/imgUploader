
<?php

class Connector
{


   const dbHost = 'localhost';
  const dbUser = 'root';
  const dbPass = '';
  const dbName = '';

  public $connection;
  public $qSelCat = "SELECT * FROM photocat";
  public $qSelPhoto = "SELECT * FROM photos";

  public function __construct(){


    $this->connection = new mysqli(self::dbHost, self::dbUser, self::dbPass, self::dbName);
    if ($this->connection->connect_errno == true) {
     echo " failed to connected" . $this->connection->connect_errno . ") " . $this->connection->connect_error;
     } //else{
    //  echo "Connected!.,,,,,,,..........!<br>";
    // }
  }



}//Connector
?>
