<?php
//namespace App;
require_once 'config/database.php';
//use \PDO;
class USER
{
    private $conn;
private $conn1;
    public function __construct()
    {
        $database = new Database();
        $db = $database->dbConnection();
        $this->conn = $db;
    }

    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }
    public function query($statement){
        $req = $this->conn->query($statement);//lance la requete sql
        $data = $req->fetchAll(PDO::FETCH_OBJ); // stock le resulta de la requete dans $data
        return $data;
    }

    public function lastID()
    {
        $stmt = $this->conn->lastInsertId();
        return $stmt;
    }
    public function is_logged_in()
    {
        if(isset($_SESSION['userSession']))
        {
            return true;
        }
    }

    public function redirect($url)
    {
        header("Location: $url");
    }

    public function logout()
    {
        session_destroy();
        $_SESSION['userSession'] = false;
    }
    public function register($uname,$email,$upass,$code)
    {
      try
      {
          $password = password_hash($upass, PASSWORD_DEFAULT);
          $stmt = $this->conn->prepare("INSERT INTO tbl_users(userName,userEmail,userPass,tokenCode)
                                                VALUES(:user_name, :user_mail, :user_pass, :active_code)");
          $stmt->bindparam(":user_name",$uname);
          $stmt->bindparam(":user_mail",$email);
          $stmt->bindparam(":user_pass",$password);
          $stmt->bindparam(":active_code",$code);
          $stmt->execute();
          return $stmt;
      }
      catch(PDOException $ex)
      {
          echo $ex->getMessage();
      }
    }

    public function login($email,$upass)
    {
        try
        {
            $stmt = $this->conn->prepare("SELECT * FROM tbl_users WHERE userEmail=:email_id");
            $stmt->execute(array("email_id"=>$email));
            $userRow=$stmt->fetch(PDO::FETCH_ASSOC);

            if($stmt->rowCount() == 1) //si c'est un email unique
            {
                if($userRow['userStatus']=="Y") // si le mail et valide
                {
                    if(password_verify($upass, $userRow['userPass'])) // si c'est le bon mdp
                    {
                        $_SESSION['userSession'] = $userRow['userID'];
                        return true;
                    }
                    else //si pas bon mdp
                    {
                        header("Location: index.php?error");
                        exit;
                    }
                }
                else //si compte invalide
                {
                    header("Location: index.php?inactive");
                    exit;
                }
            }
            else //si l'email n'est pas unique
            {
                header("Location: index.php?error");
                exit;
            }
        }
        catch(PDOException $ex)
        {
            echo $ex->getMessage();
        }
    }


//======L'envoi des e-mails====================
function r_mail($email,$subject,$mes_txt,$mes_html)
{
    //=========Saut a la ligne==============
    $pl = "\r\n";
    //======================================
    //=====Déclaration des messages au format texte et au format HTML.======
    $message_txt = $mes_txt;
    $message_html = $mes_html;
    //======================================================================

    //=====Création de la frontiere====
    $boundary = "-----=".md5(rand());
    //=================================

    //=====Définition du sujet.========
    $sujet = $subject;
    //=================================

    //===============Création du header de l'e-mail==========
    $header = "From: \"kerkeb\"<kerkeb@gmail.com>".$pl;
    $header = "Reply-to: \"kerkeb\"<kerkeb@gmail.com>".$pl;
    $header.= "MIME-Version: 1.0".$pl;
    $header.= "Content-Type: multipart/alternative;".$pl." boundary=\"$boundary\"".$pl;
    //=======================================================

    //=====Création du message.==============================
    $message = $pl."--".$boundary.$pl;
    //=====Ajout du message au format texte.=================
    $message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$pl;
    $message.= "Content-Transfer-Encoding: 8bit".$pl;
    $message.= $pl.$message_txt.$pl;
    //=======================================================
    $message = $pl."--".$boundary.$pl;
    //=====Ajout du message au format HTML===================
    $message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$pl;
    $message.= "Content-Transfer-Encoding: 8bit".$pl;
    $message.= $pl.$message_html.$pl;
    //=======================================================
    //======Fermeture des frontiere==========================
    $message.= $pl."--".$boundary."--".$pl;
    $message.= $pl."--".$boundary."--".$pl;
    //=======================================================

    //======Envoi de l'e-mail.===============================
    mail($email,$sujet,$message,$header);
    //=======================================================

  }
}
