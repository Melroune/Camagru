<?php

class Database
{
  private $DB_DSN = "mysql:host=localhost;dbname=camagru";
  private $DB_USER = "root";
  private $DB_PASSWORD = "1123581321e";

    public $conn;

    public function dbConnection()
    {

        $this->conn = null;
        try
        {
            // $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            // var_dump($this->$DB_DSN);
            // $this->conn = new PDO($this->DB_DSNs ,$this->DB_USERs, $this->DB_PASSWORDs);
            $this->conn = new PDO($this->DB_DSN ,$this->DB_USER, $this->DB_PASSWORD);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return($this->conn);
        }
        catch(PDOException $exception)
        {
            // echo "Connection error: " . $exception->getMessage();
            if ($exception->getMessage() ==="SQLSTATE[HY000] [1049] Unknown database 'camagru'")
            {
              // $pberror = "ok";
              echo 'aucune bdd presente : <a href="config/setup.php">pour installer la bdd cliquer ici</a>';
              // return "ok";
            }
            else{
              echo "Connection error: " . $exception->getMessage();
            }
          }
        // return $this->conn;
    }
}
?>
